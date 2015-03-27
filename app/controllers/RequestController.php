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
      // DB::beginTransaction();
      // Validate the input values
      $validator = Validator::make($data = Input::all(), SellerRequest::$rules);


      // validation fails redirect to form with error
      if ($validator->fails()) {
          return Redirect::back()->withErrors($validator)->withInput();
      }

      try {
          $requestData['requester_name']   =  $data['requester_name'];
          $requestData['email']         = $data['email'];
          $requestData['contact_number'] = $data['contact_number'];
          $requestData['merchant_name'] = $data['merchant_name'];
          $requestData['merchant_id']   = (int)$data['merchant_id'];
          $requestData['merchant_city_id'] = (int)$data['merchant_city_id'];
          $requestData['merchant_sales_channel_id'] = (int)$data['merchant_sales_channel_id'];
          $requestData['poc_name']      = $data['poc_name'];
          $requestData['poc_email']     = $data['poc_email'];
          $requestData['poc_number']    = $data['poc_number'];
          $requestData['category_id']   = (int)$data['category_id'];
          $requestData['total_sku']     = (int)$data['total_sku'];
          $requestData['image_available'] = (int)$data['image_available'];
          $requestData['comment']       = $data['comment'];
          $ticket = SellerRequest::createRequest($requestData);

      } catch (Exception $e) {
          // DB::rollback();
          return Redirect::back()->withErrors($e->getMessage())->withInput();
      }
      return Redirect::to('request/success/'.$ticket->id);
  }

  public function updateRequest() {
     // Begin DB transaction
     // DB::beginTransaction();
     try {
      $ticketData = Input::all();
      $ticketTransactionId = $ticketData['transaction_id'];
      $ticketId = $ticketData['ticket_id'];
        if($ticketTransactionId) {
          if($ticketData['group_id'] == 2)
              $ticketTransaction = Ticket::updateEditingManager($ticketTransactionId, $ticketId, $ticketData);
          else
              $ticketTransaction = Ticket::assignTicket($ticketTransactionId, $ticketId, $ticketData);
        }
      } catch (Exception $e) {
        // RollBack Merges
        // DB::rollback();
        return $e->getMessage();
      }
      return $ticketTransaction;
  }

   public function updatePhotographer() {
       // Begin DB transaction
      // DB::beginTransaction();
      try {
        $ticketData = Input::all();
        $ticketTransactionId = $ticketData['transaction_id'];
        $ticketId = $ticketData['ticket_id'];
        if($ticketTransactionId) {
          $ticketTransaction = Ticket::updatePhotographer($ticketTransactionId, $ticketId, $ticketData);
        }
      } catch (Exception $e) {
        // RollBack Merges
        // DB::rollback();
        return $e->getMessage();
      }
    return $ticketTransaction;
  }

    public function updateMIF() {
        // Begin DB transaction
        // DB::beginTransaction();
        try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];

            if($ticketTransactionId) {
                $ticketTransaction = Ticket::updateMIF($ticketTransactionId, $ticketId, $ticketData);
            }
        } catch (Exception $e) {
          // RollBack Merges
          // DB::rollback();
          return $e->getMessage();
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
