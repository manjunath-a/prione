<?php

class RequestController extends BaseController {

  /**
   * construct the models.
   */
  public function __construct()
  {
      parent::__construct();
      // echo Config::get('aws-sdk-php-laravel.bucketname');

      // echo $s3->getCredentials()->getAccessKeyId();exit;
  }

	/**
	 * Returns Request Form.
	 *
	 * @return View
	 */
	public function getIndex()
	{

    // Get all the available city
    $cities = City::all();
    foreach ($cities as $key => $cityArray) {
        $city[$cityArray['id']] = $cityArray['city_name'];
    }
    // Get all the available Sales Channel
    $salesChannels = SalesChannel::all();
    foreach ($salesChannels as $key => $channelArray) {
        $salesChannel[$channelArray['id']] = $channelArray['channel_name'];
    }

    // Get all the available Category
    $categorys = Category::all();
    foreach ($categorys as $key => $categoryArray) {
        $category[$categoryArray['id']] = $categoryArray['category_name'];
    }

		// Show the page
		return View::make('request/index', compact('city', 'salesChannel', 'category'))
          ->with('route', 'request')
          ->with('request_id', null)
          ->with('data',array());
	}
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store() {
      // Validate the input values
      $validator = Validator::make($data = Input::all(), SellerRequest::$rules);


      // validation fails redirect to form with error
      if ($validator->fails()) {
         // var_dump($data);exit;
          // return Redirect::back()->withErrors($validator)->withInput();
      }

      try {
          $requestData['seller_name'] =  $data['seller_name'];
          $requestData['email'] = $data['email'];
          $requestData['contact_number'] = $data['contact_number'];
          $requestData['merchant_name'] = $data['merchant_name'];
          $requestData['merchant_id'] = $data['merchant_id'];
          $requestData['merchant_city_id'] = $data['merchant_city_id'];
          $requestData['merchant_sales_channel_id'] = $data['merchant_sales_channel_id'];
          $requestData['poc_name'] = $data['poc_name'];
          $requestData['poc_email'] = $data['poc_email'];
          $requestData['poc_number'] = $data['poc_number'];
          $requestData['category_id'] = $data['category_id'];
          $requestData['total_sku'] = $data['total_sku'];
          $requestData['image_available'] = $data['image_available'];
          $requestData['comment'] = $data['comment'];
          $ticket = SellerRequest::createRequest($requestData);

      } catch (Exception $e) {
          return Redirect::back()->withErrors($e->getMessage())->withInput();
      }
      return Redirect::to('request/success/'.$ticket->id);
  }

  public function updateRequest() {

      $ticketData = Input::all();
      $ticketTransactionId = $ticketData['transaction_id'];
      $ticketId = $ticketData['ticket_id'];

        if($ticketData['group_id'] == 2){
            if($ticketTransactionId) {
                $ticketTransaction = Ticket::updateEditingManager($ticketTransactionId, $ticketId, $ticketData);
            }
        }else {
            if($ticketTransactionId) {
                $ticketTransaction = Ticket::assignTicket($ticketTransactionId, $ticketId, $ticketData);
            }
        }

      return $ticketTransaction;
  }

   public function updatePhotographer() {
    $ticketData = Input::all();
    $ticketTransactionId = $ticketData['transaction_id'];
    $ticketId = $ticketData['ticket_id'];
    if($ticketTransactionId) {
      $ticketTransaction = Ticket::updatePhotographer($ticketTransactionId, $ticketId, $ticketData);
    }
    return $ticketTransaction;
  }

    public function updateMIF() {
        $ticketData = Input::all();
        $ticketTransactionId = $ticketData['transaction_id'];
        $ticketId = $ticketData['ticket_id'];

        if($ticketTransactionId) {
            $ticketTransaction = Ticket::updateMIF($ticketTransactionId, $ticketId, $ticketData);
        }
        return $ticketTransaction;
    }

  /**
   * Returns sucess Page.
   *
   * @return View
   */
  public function success( $ticket )
  {
    // Show the page
    return View::make('request/success')
           ->with('ticketid',$ticket);
  }
}
