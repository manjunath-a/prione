<?php

class RequestController extends BaseController {

  /**
   * construct the models.
   */
  public function __construct()
  {
      parent::__construct();

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
          if(isset($data['google_form']))
          {
            return json_encode(array( 'status' => false, 'message' => 'Data Error' ));
          }
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
          if(isset($data['google_form']))
              return json_encode(array( 'status' => true, 'message' => 'Tickect ID = '.$ticket->id ));

      } catch (Exception $e) {
          if(isset($data['google_form']))
              return json_encode(array( 'status' => false, 'message' => 'Exception '.$e->getMessage() ));
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
          if($ticketData['group_id'] == 2){
              $ticketTransaction = Ticket::updateEditingManager($ticketTransactionId, $ticketId, $ticketData);
          }else if($ticketData['group_id'] == 1) {
              $ticketTransaction = Ticket::assignTicket($ticketTransactionId, $ticketId, $ticketData);
          } else {
              $errorMsg = json_encode( array("message" => "User did not have permission to move Cataloging"));
              throw new Exception( $errorMsg );
          }
        }
      } catch (Exception $e) {
        // RollBack Merges
        // DB::rollback();
        $errorMsg = json_encode(array( 'status' => false, 'message' => $e->getMessage() ));
        return $errorMsg;
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
        $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
        return $errorMsg;
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
          $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
          return $errorMsg;
        }
        return $ticketTransaction;
    }

    public function updateEditingManager() {
        $ticketData = Input::all();
        $ticketTransactionId = $ticketData['transaction_id'];
        $ticketId = $ticketData['ticket_id'];
        if($ticketTransactionId) {
            $ticketTransaction = Ticket::updateEditor($ticketTransactionId, $ticketId, $ticketData);
        }
        return $ticketTransaction;
    }

    public function updateEditor() {
        try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            //print_r($ticketData);exit;
            if($ticketTransactionId) {
                if($ticketData['group_id'] == 3){
                    $ticketTransaction = Ticket::updateCatalogManager($ticketTransactionId, $ticketId, $ticketData);
                }else {
                    $ticketTransaction = Ticket::updateAssignEditor($ticketTransactionId, $ticketId, $ticketData);
                }

            }
        } catch (Exception $e) {
            // RollBack Merges
            // DB::rollback();
            $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
            return $errorMsg;
        }
        return $ticketTransaction;
    }

    public function updateEditingComplete() {
        $ticketData = Input::all();
        $ticketTransactionId = $ticketData['transaction_id'];
        $ticketId = $ticketData['ticket_id'];
        if($ticketTransactionId) {
            $ticketTransaction = Ticket::updateEditingComplete($ticketTransactionId, $ticketId, $ticketData);
        }
        return $ticketTransaction;
    }

    public function updateAssignCatalogue() {
        $ticketData = Input::all();
        $ticketTransactionId = $ticketData['transaction_id'];
        $ticketId = $ticketData['ticket_id'];
        if($ticketTransactionId) {
            $ticketTransaction = Ticket::updateCatalogTeamLead($ticketTransactionId, $ticketId, $ticketData);
        }
        return $ticketTransaction;
    }

    public function updateCatalogueTeamLead() {
        $ticketData = Input::all();
        $ticketTransactionId = $ticketData['transaction_id'];
        $ticketId = $ticketData['ticket_id'];
        if($ticketTransactionId) {
            $ticketTransaction = Ticket::updateCatalogTeamLead($ticketTransactionId, $ticketId, $ticketData);
        }
        return $ticketTransaction;
    }

    public function updateCataloguer() {
        $ticketData = Input::all();
        $ticketTransactionId = $ticketData['transaction_id'];
        $ticketId = $ticketData['ticket_id'];
        if($ticketTransactionId) {
            $ticketTransaction = Ticket::updateCataloguer($ticketTransactionId, $ticketId, $ticketData);
        }
        return $ticketTransaction;
    }

    public function updateCatalogingComplete() {
        $ticketData = Input::all();
        $ticketTransactionId = $ticketData['transaction_id'];
        $ticketId = $ticketData['ticket_id'];
        if($ticketTransactionId) {
            $ticketTransaction = Ticket::updateCatalogingComplete($ticketTransactionId, $ticketId, $ticketData);
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

  public function getStatus(){

  }

}
