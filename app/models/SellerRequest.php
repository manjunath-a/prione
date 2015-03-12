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

    public static function feshDesk($requestData) {
        $fd_domain = "https://compassinc.freshdesk.com";
        $token = "H3NALV2F2poFiOZRnt";
        $password = "X";
        $data = array(
            "helpdesk_ticket" => array(
                "description" => $requestData['comment'],
                "subject" => "Seller Request Created",
                "email" => $requestData['email'],
                "priority" => 1,
                "status" => 2
            ),
            "cc_emails" => "subramania.bharathy@compassitesinc.com, b.arasu@compassitesinc.com"
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
        $response = curl_exec($connection);
        return $response;
    }
}
