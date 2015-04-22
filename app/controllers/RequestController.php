<?php

class RequestController extends BaseController {

    /**
     * TicketValidator
     * @var validateTicket
     */
    protected $validateTicket;


    /**
     * construct the models.
     */
    public function __construct()
    {
        parent::__construct();
        $this->validateTicket = App::make('ticketValidator');
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
    public function store()
    {
        // Begin DB transaction
        DB::beginTransaction();
        // Validate the input values
        $validator = Validator::make($data = Input::all(), SellerRequest::$rules);

        // validation fails redirect to form with error
        if ($validator->fails()) {
            if(isset($data['google_form'])) {
              return json_encode(array( 'status' => false, 'message' => $validator->getMessages() ));
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
            // Push to DB
            DB::commit();
            if(isset($data['google_form']))
                return json_encode(array( 'status' => true, 'message' => 'Tickect ID = '.$ticket->id ));
        } catch (Exception $e) {
            // RollBack Entry from Trancation
            DB::rollback();
            if(isset($data['google_form']))
                return json_encode(array( 'status' => false, 'message' => 'Exception '.$e->getMessage() ));
            // DB::rollback();
            return Redirect::back()->withErrors($e->getMessage())->withInput();
        }
        return Redirect::to('request/success/'.$ticket->id);
    }

    public function updateRequest()
    {
       // Begin DB transaction
       DB::beginTransaction();
       try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            if($ticketTransactionId) {
                  if($ticketData['group_id'] == 2) {
                      $this->validateTicket->localLeadToEditingManagerFlow($ticketData);
                      $ticketTransaction = Ticket::assignEditingManager($ticketTransactionId,
                        $ticketId, $ticketData);
                  } else if($ticketData['group_id'] == 1) {
                      // Check for Local Lead update Process
                      $this->validateTicket->localLeadFlow($ticketData);
                      $ticketTransaction = Ticket::assignTicket($ticketTransactionId, $ticketId, $ticketData);
                  } else if($ticketData['group_id'] == 3) {
                      $this->validateTicket->localLeadToCatalogingManagerFlow($ticketData);
                      $ticketTransaction = Ticket::assignCatalogingManager($ticketTransactionId,
                        $ticketId, $ticketData);
                  }
            }
            // Push to DB
            DB::commit();
        } catch (Exception $e) {
            // RollBack Entry from Trancation
            DB::rollback();
            $errorMsg = json_encode(array( 'status' => false, 'message' => $e->getMessage() ));
            return $errorMsg;
        }
        return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
    }

    public function updatePhotographer()
    {
          // Begin DB transaction
          DB::beginTransaction();
          try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            if($ticketTransactionId) {
              $this->validateTicket->photographerFlow($ticketData);
              $ticketTransaction = Ticket::updatePhotographer($ticketTransactionId, $ticketId, $ticketData);
            }
            // Push to DB
            DB::commit();
          } catch (Exception $e) {
            // RollBack Merges
            // DB::rollback();
            $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
            return $errorMsg;
          }
          return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
    }

    public function updateMIF()
    {
        // Begin DB transaction
        DB::beginTransaction();
        try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];

            if($ticketTransactionId) {
                $this->validateTicket->servicesAssociateFlow($ticketData);
                $ticketTransaction = Ticket::updateMIF($ticketTransactionId, $ticketId, $ticketData);
            }
            // Push to DB
            DB::commit();
        } catch (Exception $e) {
          // RollBack Merges
          DB::rollback();
          $errorMsg = json_encode(array('status' => false, 'message' => $e->getMessage() ));
          return $errorMsg;
        }
        return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
    }

    public function updateEditingManager()
    {
      // Begin DB transaction
      DB::beginTransaction();
      try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            if($ticketTransactionId) {
                $this->validateTicket->editingManagerFlow($ticketData);
                $ticketTransaction = Ticket::updateEditingManager($ticketTransactionId, $ticketId, $ticketData);
            }
             // Push to DB
            DB::commit();
         } catch (Exception $e) {
            // RollBack Merges
            DB::rollback();
            $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
            return $errorMsg;
        }
        return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
    }

    public function updateEditingTeamLead()
    {
        // Begin DB transaction
        DB::beginTransaction();
        try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            if($ticketTransactionId) {
                if($ticketData['group_id'] == 3) {
                    $ticketTransaction = Ticket::updateCatalogManager($ticketTransactionId, $ticketId, $ticketData);
                } else {
                    $this->validateTicket->editingTeamLeadFlow($ticketData);
                    $ticketTransaction = Ticket::updateAssignEditor($ticketTransactionId, $ticketId, $ticketData);
                }
            }
            // Push to DB
            DB::commit();
        } catch (Exception $e) {
            // RollBack Merges
            DB::rollback();
            $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
            return $errorMsg;
        }
        return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
    }

    public function updateEditingComplete()
    {
        // Begin DB transaction
        DB::beginTransaction();
        try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            if($ticketTransactionId) {
                $this->validateTicket->editorFlow($ticketData);
                $ticketTransaction = Ticket::updateEditingComplete($ticketTransactionId, $ticketId, $ticketData);
            }
            // Push to DB
            DB::commit();
        } catch (Exception $e) {
            // RollBack Merges
            DB::rollback();
            $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
            return $errorMsg;
        }

        return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
    }

    public function updateAssignCatalogTeamLead()
    {
        // Begin DB transaction
        DB::beginTransaction();
        try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            if($ticketTransactionId) {
                $this->validateTicket->catalogingManagerFlow($ticketData);
                $ticketTransaction = Ticket::updateAssignCatalogTeamLead($ticketTransactionId, $ticketId, $ticketData);
            }
             // Push to DB
            DB::commit();
        } catch (Exception $e) {
             // RollBack Merges
            DB::rollback();
            $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
            return $errorMsg;
        }

        return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
    }

    public function updateCatalogTeamLead()
    {
        // Begin DB transaction
        DB::beginTransaction();
        try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            if($ticketTransactionId) {
                $this->validateTicket->catalogingTeamLeadFlow($ticketData);
                $ticketTransaction = Ticket::updateCatalogTeamLead($ticketTransactionId, $ticketId, $ticketData);
            }
            // Push to DB
            DB::commit();
        } catch (Exception $e) {
            // RollBack Merges
            DB::rollback();
            $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
            return $errorMsg;
        }
        return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
    }

    public function updateCataloger()
    {
        // Begin DB transaction
        DB::beginTransaction();
        try {
            $ticketData = Input::all();
            $ticketTransactionId = $ticketData['transaction_id'];
            $ticketId = $ticketData['ticket_id'];
            if($ticketTransactionId) {
                $this->validateTicket->catalogerFlow($ticketData);
                $ticketTransaction = Ticket::updateCataloguer($ticketTransactionId, $ticketId, $ticketData);
            }
            // Push to DB
            DB::commit();
        } catch (Exception $e) {
            // RollBack Merges
            DB::rollback();
            $errorMsg = json_encode(array('status'=>false, 'message' => $e->getMessage() ));
            return $errorMsg;
        }
        return json_encode(array( 'status' => true, 'message' => 'Ticket updated Successfully'));
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