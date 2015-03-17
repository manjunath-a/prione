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
        'poc_name' => 'required|alpha|min:5',
        'poc_email' => 'required|email',
        'poc_number' => 'required',
        'category_id' => 'required',
        'total_sku' => 'required',
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
        // var_dump($freshdesk);

        $merchantCity = City::find($requestData['merchant_city_id'])->toArray();
        $merchantCategory = Category::find($requestData['category_id'])->toArray();

        $subject = implode(' // ',array($requestData['seller_name'],
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


        $data = array (
            "helpdesk_ticket" => array(
                "description" => $description,
                "subject" => $subject,
                "email" => $requestData['email'],
                "priority" => 1,
                "status" => 2
                ),
            "cc_emails" => Config::get('mail.cc_email'),
        );
        return $freshdesk->createTicket( $data );

    }

    public static function createRequest( $requestData ) {

        $sellerRequest = SellerRequest::create($requestData);

        $fdTicket = SellerRequest::buildTicket($requestData);
        var_dump($requestData);

        $cityLead = User::where('city_id', '=', $requestData['merchant_city_id'])->first();
        // $requestData['merchant_city_id']);
        // var_dump($cityLead->id); exit;
        if($fdTicket->display_id) {
            $ticketData['request_id'] = $sellerRequest->id;
            $ticketData['freshdesk_ticket_id'] = $fdTicket->display_id;
            $ticketData['email'] = $requestData['email'];
            $ticketData['subject'] = $fdTicket->subject;
            $ticketData['description'] = $fdTicket->description;
            $ticketData['s3_url'] = 's3.prion.com';
            $ticketData['assigned_to'] = $cityLead->id;
            $ticketData['pending_reason'] = ' Just now  Ticket created ';
            $ticketData['status_id'] = Config::get('ticket.default_status');

            // $group = Config::get('ticket.default_group');
            $ticket= Ticket::create($ticketData);

            // Ticket Transaction
            $ticketTransactioData['ticket_id'] = $ticket->id;
            $ticketTransactioData['assigned_to'] = $cityLead->id;
            $ticketTransactioData['priority'] = Config::get('ticket.default_priority');
            $ticketTransactioData['status_id'] = Config::get('ticket.default_status');
            $ticketTransactioData['group_id'] = 1;
            $ticketTransactioData['stage_id'] = Config::get('ticket.default_stage');
            $ticketTransactioData['total_sku'] = $requestData['total_sku'];
            $ticketTransactioData['total_images'] = 0;
            $ticketTransaction = TicketTransaction::create($ticketTransactioData);

            // $s3 = App::make('aws')->get('s3');
            // // $s3 = AWS::get('s3');
            $folderName = $fdTicket->display_id.'_'.$requestData['seller_name'];
            // $result = $s3->putObject(array(
            //     'Bucket' => 'prionecataloguing',
            //     'Key'    => $folderName ,
            //     'Body'   => "",
            // ));
            return $ticket;
            // var_dump($result);exit;
        }

    }

}
