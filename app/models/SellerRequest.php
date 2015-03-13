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

    /**
     * Get Seller request by Seller Name
     * @param $sellerName
     * @return mixed
     */
    public function getSellerByname( $sellerName )
    {
        return $this->where('seller_name', '=', $sellerName)->first();
    }

    public static function freshDesk($requestData) {

        $fd_domain = Config::get('freshdesk.domain');
        $token = Config::get('freshdesk.token');
        $password = Config::get('freshdesk.password');

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

        $json_body = json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
        $header[] = "Content-type: application/json";
        $connection = curl_init("$fd_domain/helpdesk/tickets.json");
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
        curl_setopt($connection, CURLOPT_HEADER, false);
        curl_setopt($connection, CURLOPT_USERPWD, "$token:$password");
        curl_setopt($connection, CURLOPT_POST, true);
        curl_setopt($connection, CURLOPT_POSTFIELDS, $json_body);
        curl_setopt($connection, CURLOPT_VERBOSE, 1);
        $ticketResponse = curl_exec($connection);
        $ticketArray = json_decode(  $ticketResponse );

        return $ticketArray->helpdesk_ticket;
    }

    public static function createRequest( $requestData ) {

        $request = SellerRequest::create($requestData);
        $ticket = SellerRequest::freshDesk( $requestData );
        if($ticket->display_id) {
            $ticketData['request_id'] = $request->id;
            $ticketData['freshdesk_ticket_id'] = $ticket->display_id;
            $ticketData['email'] = $requestData['email'];
            $ticketData['subject'] = $ticket->subject;
            $ticketData['description'] = $ticket->description;
            $ticketData['s3_url'] = 's3.prion.com';
            $ticketData['assigned_to'] = 1;
            $ticketData['priority'] = 1;
            $ticketData['pending_reason'] = ' Just now  Ticket created ';
            $ticketData['status_id'] = 1;
            return Ticket::create($ticketData);
        }

    }

//    public function tickect()
//    {
//        return $this->hasOne('Ticket');
//    }
//

}
