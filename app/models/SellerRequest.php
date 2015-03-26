<?php


class SellerRequest extends Eloquent  {

    /**
    * The database table used by the model.
     *
    * @var string
    */
    protected $table = 'seller_request';

    /**
    * Primary key for the table.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $guarded = array('id');


    /**
     * Add your validation rules here
     *
     * @var string
     */
    public static $rules = [
        'seller_name' => 'required',
        'email' => 'required|email',
        'contact_number' => 'required',
        'merchant_name' => 'required',
        'merchant_id' => 'required',
        'merchant_city_id' => 'required',
        'merchant_sales_channel_id' => 'required',
        'poc_name' => 'required|string|min:3',
        'poc_email' => 'required|email',
        'poc_number' => 'required',
        'category_id' => 'required',
        'total_sku' => 'required|Integer',
        'image_available' => 'required',
        'comment' => 'required'
    ];

    public function ticket()
    {
        return $this->hasOne('Ticket', 'request_id');
    }

    /**
     * Get Seller request by Seller Name
     * @param $sellerName
     * @return mixed
     */
    public function getSellerByname( $sellerName )
    {
        return $this->where('seller_name', '=', $sellerName)->first();
    }


    /**
     *
     */
    public static function buildTicket($requestData) {

        $freshdesk = App::make('freshDesk');
        $merchantCity = City::find($requestData['merchant_city_id'])->toArray();
        $merchantCategory = Category::find($requestData['category_id'])->toArray();

        $subject = implode(' // ', array($requestData['seller_name'],
                            $merchantCity['city_name'],
                            $merchantCategory['category_name'],
                            "SKU# ".$requestData['total_sku'],
                        ));

        $description = "Category :: ". $merchantCategory['category_name']."
                        Number of Unique SKUs to be cataloged? :: ".$requestData['total_sku']."
                        Additional information:Flat file sent and Images uploaded in the S3.

                        Seller name:".$requestData['seller_name']."
                        City in which service is required:".$merchantCity['city_name']."

                        Contact person name:".$requestData['poc_name']."
                        Complete address of seller:NA
                        Contact number of seller:".$requestData['poc_number']."
                        Alternate contact number of seller:".$requestData['poc_number']."
                        Email ID of seller:".$requestData['poc_email']."
                        Comment :".$requestData['comment']."

                        Requester name:XXXXXX XXXX
                        Requester e-mail ID:XXXXXX@prione.in
                        Requester mobile number:XXXXXX
                        Requester sales channel:PRIONE";

        $data = array(
                "description" => $description,
                "subject" => $subject,
                "email" => $requestData['email'],
                "priority" => 1,
                "status" => 2
                );

        return $freshdesk->createTicket( $data );
    }

    public static function createRequest( $requestData ) {

        $user = new User;

         // $sellerRequest = DB::table('seller_request')->insert(
         //        $requestData
         //    );

        $sellerRequest = SellerRequest::create($requestData);

        $fdTicket = SellerRequest::buildTicket($requestData);

        $folder = SellerRequest::createFolderInAWS($fdTicket->display_id, $requestData['seller_name']);

        $merchantCity = City::find($requestData['merchant_city_id'])->toArray();
        $cityLead = $user->findAllByRoleAndCity('Local Team Lead', $merchantCity['id']);

        if($fdTicket->display_id) {

            $ticketData['request_id'] = $sellerRequest->id;
            $ticketData['freshdesk_ticket_id'] = $fdTicket->display_id;
            $ticketData['email'] = $requestData['email'];
            $ticketData['subject'] = $fdTicket->subject;
            $ticketData['description'] = $fdTicket->description;
            $ticketData['s3_url'] = $folder;

            $ticket = Ticket::create($ticketData);
            // Ticket Transaction
            $ticketTransactioData['ticket_id'] = $ticket->id;
            $ticketTransactioData['assigned_to'] = $cityLead[0]->id;
            $ticketTransactioData['priority'] = Config::get('ticket.default_priority');
            $ticketTransactioData['status_id'] = Config::get('ticket.default_status');
            $ticketTransactioData['active'] = 1;
            $ticketTransactioData['group_id'] = Config::get('ticket.default_group');

            // Default as (Local) Associates Not Assigned
            $stageId = Config::get('ticket.default_stage');
            if($requestData['image_available'] == 2) {
                $assignStage = Stage::where('stage_name',
                      '(Local) Photoshoot Completed / Seller Images Provided')->first();
                $stageId = $assignStage->id;
            }

            $ticketTransactioData['stage_id'] = $stageId;
            $ticketTransactioData['total_sku'] = $requestData['total_sku'];
            $ticketTransactioData['total_images'] = 0;
            $ticketTransaction = TicketTransaction::create($ticketTransactioData);

            SellerRequest::createFolderInAWS($fdTicket->display_id, $requestData['seller_name'] );
            return $ticket;
        }
        return false;
    }

    public static function createFolderInAWS($freshdeskDisplayId, $seller_name ) {
        $folderName = $freshdeskDisplayId.'_'.$seller_name.'/';
        $local = $freshdeskDisplayId.'_'.$seller_name.'/local/';
        $editing = $freshdeskDisplayId.'_'.$seller_name.'/editing/';
        $cataloging = $freshdeskDisplayId.'_'.$seller_name.'/cataloging/';
        $s3 = App::make('aws')->get('s3');
        $result = $s3->putObject(array(
            'Bucket' => 'prionecataloguing',
            'Key'    => $folderName ,
            'Body' => ''
        ));

        $result = $s3->putObject(array(
            'Bucket' => 'prionecataloguing',
            'Key'    => $local ,
            'Body' => ''
        ));
        $result = $s3->putObject(array(
            'Bucket' => 'prionecataloguing',
            'Key'    => $editing ,
            'Body' => ''
        ));
        $result = $s3->putObject(array(
            'Bucket' => 'prionecataloguing',
            'Key'    => $cataloging ,
            'Body' => ''
        ));
        return $folderName;
    }
}
