<?php


class Ticket extends Eloquent  {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'ticket';

  /**
   * Primary key for the table.
   *
   * @var string
   */
  protected $primaryKey = 'id';

  protected $guarded = array('id');

  public function request() {
      return $this->belongsTo('SellerRequest');
  }

  public static function assignTicket($ticketTransactionId, $ticketId, $data) {

    $ticketTransaction = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));

    $ticketData = Ticket::ticketData($data['stage_id'], 1, $data);

    if($data['status_id'] == 1)
    {
        if(isset($data['photographer_id'])) {
            if($data['photographer_id']) {
                $ticketData['photographer_id']    = $data['photographer_id'];
                $ticketData['photoshoot_location']= $data['photoshoot_location'];
                $ticketData['assigned_to']        = $data['photographer_id'];
                $ticketData['photoshoot_date']    = $data['photoshoot_date'];
                $photographerTransaction = TicketTransaction::updateTicket($ticketData);
            }
        }

        // Assgining to Service Assiocate
        $ticketData['assigned_to']      = $data['mif_id'];
        $serviceAssociateTransaction    = TicketTransaction::updateTicket($ticketData);
    }

    // Assgining to Local Team Lead from Session user
    $ticketData['assigned_to'] = Auth::user()->id;
    $leadTransaction = TicketTransaction::updateTicket($ticketData);

    // Update Team Lead
//    $ticketTransaction          = TicketTransaction::find($ticketTransactionId);
//    $ticketTransaction->active  = 0;
//    $ticketTransaction->save();

    return $leadTransaction->id;
  }

  public static function updatePhotographer($ticketTransactionId, $ticketId, $data) {

      $cityId =  Auth::user()->city_id;

          // Update Team Lead
      $ticketTransaction = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));

      //  'Model Ticket';
      if($data['mif_id'] == 0) {
        throw new Exception("Service Associates is required ");
      }

      if( $data['pending_reason_id'] != 0 ) {
          $photoStage = Stage::where('stage_name', '(Local) Associates Not Assigned')->first();
      } else {
          $photoStage = Stage::where('stage_name', '(Local) Photoshoot Completed / Seller Images Provided')->first();
      }

      $ticketData = Ticket::TicketData($photoStage->id, 1, $data);
      $ticketData['photographer_id']     =  Auth::user()->id;
      $ticketData['photoshoot_location'] =  $data['photoshoot_location'];
      $ticketData['photoshoot_date']     = $data['photoshoot_date'];

      // Assgining to Local Team Lead
      $ticketData['assigned_to'] = Ticket::findUserByRoleAndCity('Local Team Lead', $cityId);;
      $leadTransaction           = TicketTransaction::updateTicket($ticketData);

      // Assgining to service Assiocate
      $ticketData['assigned_to']   = $data['mif_id'];
      $serviceAssociateTransaction = TicketTransaction::updateTicket($ticketData);

      // Check for Poto Grapher assigned
      if (Auth::user()->id) {
          $ticketData['assigned_to'] = Auth::user()->id;
          $ticketData['active']      = 0;
          $photographerTransaction   = TicketTransaction::updateTicket($ticketData);
      }

      return $leadTransaction->id;
  }


  public static function updateMIF($ticketTransactionId, $ticketId, $data) {

      //  'Validate Ticket';
      if($data['stage_id'] <= 2) {
          throw new Exception("Photoshoot need to be completed before Service Associates Complete");
      }elseif($data['mif_id'] == 0) {
              throw new Exception("Service Associates is required ");
          }

      $cityId =  Auth::user()->city_id;
      // Update Team Lead
      $ticketTransaction = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));

      if( $data['pending_reason_id'] != 0 ) {
          $mifStage = Stage::where('stage_name', '(Local) Photoshoot Completed / Seller Images Provided')->first();
      } else {
          $mifStage = Stage::where('stage_name', '(Local) MIF Completed')->first();
      }

      $ticketData = Ticket::ticketData($mifStage->id, 1, $data);
      $ticketData['photographer_id']     = $data['photographer_id'];
      $ticketData['photoshoot_location'] = $data['photoshoot_location'];
      $ticketData['photoshoot_date']     = $data['photoshoot_date'];

      // Assgining to Local Team Lead
      $ticketData['assigned_to'] = Ticket::findUserByRoleAndCity('Local Team Lead', $cityId);
      $leadTransaction = TicketTransaction::updateTicket($ticketData);

      // Assgining to Service Assiocate as current Session user
      if (Auth::user()->id) {
          $ticketData['mif_id']        = Auth::user()->id;
          $ticketData['assigned_to']   = Auth::user()->id;
          $ticketData['active']        = 0;
          $serviceAssociateTransaction = TicketTransaction::updateTicket($ticketData);
      }

      return $leadTransaction->id;
    }

  public static function updateEditingManager($ticketTransactionId, $ticketId, $data) {

      $cityId         =  Auth::user()->city_id;
      $localCompleted = Stage::where('stage_name', '(Local) MIF Completed')->first();
      $ticketData     = Ticket::ticketData($localCompleted->id, 1, $data);
      $ticketData['photographer_id']      = $data['photographer_id'];
      $ticketData['photoshoot_location']  = $data['photoshoot_location'];
      $ticketData['photoshoot_date']      = $data['photoshoot_date'];

      // // Assgining to service Assiocate
      // $ticketData['assigned_to'] = $data['mif_id'];
      // $serviceAssociateTransaction = TicketTransaction::updateTicket($ticketData);

       // Assgining Local Team lead
      // Update Team Lead
      $ticketTransaction          = TicketTransaction::where('ticket_id', '=' , $ticketId)->update(array('active' => 0));
      $ticketData['assigned_to']  = Ticket::findUserByRoleAndCity('Local Team Lead', $cityId);
      $leadTransaction            = TicketTransaction::updateTicket($ticketData);

      // Assgining Editing Manager
      $user = new User;
      $editingManager            = $user->findUserByRoleName('Editing Manager');
      $ticketData['assigned_to'] = $editingManager[0]->id;
      $leadTransaction           = TicketTransaction::updateTicket($ticketData);

      return $leadTransaction->id;
  }

  public static function updateEditor($ticketTransactionId, $ticketId, $data) {

      $user               = new User;
      $editingManager     = $user->findUserByRoleName('Editing Manager');
      $localCompleted     = Stage::where('stage_name', '(Local) MIF Completed')->first();
      $ticketData         = Ticket::ticketData($localCompleted->id, 1, $data);
      $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)
                              ->where('assigned_to','=',$editingManager[0]->id)
                              ->update(array('active' => 0,'editingteamlead_id' => $data['editingteamlead']));

      $ticketData['photographer_id']      = $data['photographer_id'];
      $ticketData['photoshoot_location']  = $data['photoshoot_location'];
      $ticketData['photoshoot_date']      = $data['photoshoot_date'];
      $ticketData['editingteamlead_id']   = $data['editingteamlead'];

      // Assgining Editing Manager
      $ticketData['assigned_to'] = $editingManager[0]->id;
      $leadTransaction           = TicketTransaction::updateTicket($ticketData);

      // Assgining Editing Team Lead
      $ticketData['assigned_to']          = $data['editingteamlead'];
      $editorTransaction                  = TicketTransaction::updateTicket($ticketData);

      return $editorTransaction->id;
  }

  public static function updateAssignEditor($ticketTransactionId, $ticketId, $data) {
     // print_r($data);exit;
      $seller             = SellerRequest::find($ticketId)->toArray();
      // Deactivate old tickect transaction
      $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
      $localCompleted     = Stage::where('stage_name', '(Local) MIF Completed')->first();
      $ticketData         = Ticket::ticketData($localCompleted->id, 1, $data);

      if( $data['pending_reason_id'] == 6 ) {
          $data['group_id']  = 1;
          $data['priority']  = 3;
      }

      // Assgining to Local Team Lead
      $ticketData['assigned_to']          = Ticket::findUserByRoleAndCity('Local Team Lead', $seller['merchant_city_id']);;
      $ticketData['photographer_id']      = $data['photographer_id'];
      $ticketData['photoshoot_location']  = $data['photoshoot_location'];
      $ticketData['photoshoot_date']      = $data['photoshoot_date'];
      $ticketData['editingteamlead_id']   = Auth::user()->id;
      $ticketData['editor_id']            = $data['editor'];
      $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

      if( $data['pending_reason_id'] != 6 ) {
          // Assgining Editing Team Lead
          $ticketData['assigned_to']          = Auth::user()->id;
          $editorTransaction                  = TicketTransaction::updateTicket($ticketData);
          // Assgining Editor
          $ticketData['assigned_to']          = $data['editor'];
          $editorTransaction                  = TicketTransaction::updateTicket($ticketData);
      }

      return $editorTransaction->id;
  }

  public static function updateCatalogManager($ticketTransactionId, $ticketId, $data) {
      //print_r($data);exit;
      $seller             = SellerRequest::find($ticketId)->toArray();
      // Deactivate old tickect transaction
      $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
      $editingCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
      $ticketData         = Ticket::ticketData($editingCompleted->id, 1, $data);

      // Assgining to Local Team Lead
      $ticketData['assigned_to']          = Ticket::findUserByRoleAndCity('Local Team Lead', $seller['merchant_city_id']);;
      $ticketData['photographer_id']      = $data['photographer_id'];
      $ticketData['photoshoot_location']  = $data['photoshoot_location'];
      $ticketData['photoshoot_date']      = $data['photoshoot_date'];
      $ticketData['editingteamlead_id']   = Auth::user()->id;
      $ticketData['editor_id']            = $data['editor'];
      $leadTransaction                    = TicketTransaction::updateTicket($ticketData);
      // Assgining Editing Team Lead
      $ticketData['assigned_to']          = Auth::user()->id;
      $editingTransaction                 = TicketTransaction::updateTicket($ticketData);

      // Assgining catalogue Manager
      $user = new User;
      $catalogueManager          = $user->findUserByRoleName('Catalogue Manager');
      $ticketData['assigned_to'] = $catalogueManager[0]->id;
      $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

      return $catalogueTransaction->id;
  }

  public static function updateEditingComplete($ticketTransactionId, $ticketId, $data) {
      //print_r($data);exit;
      $seller             = SellerRequest::find($ticketId)->toArray();
      // Deactivate old tickect transaction
      $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));

      if( $data['pending_reason_id'] == 6 ) {
          $editingStage     = Stage::where('stage_name', '(Local) MIF Completed')->first();
          $data['group_id'] = 1;
          $data['priority'] = 3;
      } else {
          $editingStage = Stage::where('stage_name', '(Central) Editing Completed')->first();
      }

      $ticketData       = Ticket::ticketData($editingStage->id, 1, $data);
      // Assgining to Local Team Lead
      $ticketData['assigned_to']          = Ticket::findUserByRoleAndCity('Local Team Lead', $seller['merchant_city_id']);;
      $ticketData['photographer_id']      = $data['photographer_id'];
      $ticketData['photoshoot_location']  = $data['photoshoot_location'];
      $ticketData['photoshoot_date']      = $data['photoshoot_date'];
      $ticketData['editingteamlead_id']   = $data['editingteamlead'];
      $ticketData['editor_id']            = $data['editor'];
      $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

      if( $data['pending_reason_id'] == 0 ) {

          // Assgining Editing Manager
          $user = new User;
          $editingManager            = $user->findUserByRoleName('Editing Manager');
          $ticketData['assigned_to'] = $editingManager[0]->id;
          $leadTransaction           = TicketTransaction::updateTicket($ticketData);

          // Assgining Editing Team Lead
          $ticketData['assigned_to'] = $data['editingteamlead'];
          $editorTransaction         = TicketTransaction::updateTicket($ticketData);
      }

      // Assgining Editor
      $ticketData['assigned_to']     = $data['editor'];
      $ticketData['active']          = 0;
      $editorTransaction             = TicketTransaction::updateTicket($ticketData);

      return $editorTransaction->id;
  }

    public static function updateAssignCatalogTeamLead($ticketTransactionId, $ticketId, $data) {
        //print_r($data);exit;
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
        $editingCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
        $ticketData         = Ticket::ticketData($editingCompleted->id, 1, $data);

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = Ticket::findUserByRoleAndCity('Local Team Lead', $seller['merchant_city_id']);;
        $ticketData['photographer_id']      = $data['photographer_id'];
        $ticketData['photoshoot_location']  = $data['photoshoot_location'];
        $ticketData['photoshoot_date']      = $data['photoshoot_date'];
        $ticketData['editingteamlead_id']   = $data['editingteamlead'];
        $ticketData['editor_id']            = $data['editor'];
        $ticketData['catalogingteamlead_id']= $data['catalogueTeamLead'];
        $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Manager
        $user = new User;
        $editingManager            = $user->findUserByRoleName('Editing Manager');
        $ticketData['assigned_to'] = $editingManager[0]->id;
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Team Lead
        $ticketData['assigned_to'] = $data['editingteamlead'];
        $editingTransaction        = TicketTransaction::updateTicket($ticketData);

        // Assgining catalogue Manager
        $catalogueManager          = $user->findUserByRoleName('Catalogue Manager');
        $ticketData['assigned_to'] = $catalogueManager[0]->id;
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        // Assgining catalogue TeamLead
        $ticketData['assigned_to'] = $data['catalogueTeamLead'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        return $catalogueTransaction->id;
    }


  public static function updateCatalogTeamLead($ticketTransactionId, $ticketId, $data) {
      //print_r($data);exit;
      $seller             = SellerRequest::find($ticketId)->toArray();
      // Deactivate old tickect transaction
      $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
      $editingCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
      $ticketData         = Ticket::ticketData($editingCompleted->id, 1, $data);

      if( $data['pending_reason_id'] == 8 ) {
          $data['group_id']  = 1;
          $data['priority']  = 3;
      }

      // Assgining to Local Team Lead
      $ticketData['assigned_to']          = Ticket::findUserByRoleAndCity('Local Team Lead', $seller['merchant_city_id']);;
      $ticketData['photographer_id']      = $data['photographer_id'];
      $ticketData['photoshoot_location']  = $data['photoshoot_location'];
      $ticketData['photoshoot_date']      = $data['photoshoot_date'];
      $ticketData['editingteamlead_id']   = $data['editingteamlead'];
      $ticketData['editor_id']            = $data['editor'];
      $ticketData['catalogingteamlead_id']= Auth::user()->id;
      $ticketData['cataloguer_id']        = $data['cataloguer'];
      $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

      if( $data['pending_reason_id'] != 8 ) {

          // Assgining Editing Manager
          $user = new User;
          $editingManager            = $user->findUserByRoleName('Editing Manager');
          $ticketData['assigned_to'] = $editingManager[0]->id;
          $leadTransaction           = TicketTransaction::updateTicket($ticketData);

          // Assgining Editing Team Lead
          $ticketData['assigned_to'] = $data['editingteamlead'];
          $editingTransaction        = TicketTransaction::updateTicket($ticketData);

          // Assgining catalogue Manager
          $catalogueManager          = $user->findUserByRoleName('Catalogue Manager');
          $ticketData['assigned_to'] = $catalogueManager[0]->id;
          $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

          // Assgining catalogue Team Lead
          $ticketData['assigned_to'] = Auth::user()->id;
          $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

          // Assgining cataloguer
          $ticketData['assigned_to'] = $data['cataloguer'];
          $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);
      }

      return $leadTransaction->id;
  }

  public static function updateCataloguer($ticketTransactionId, $ticketId, $data) {
      //print_r($data);exit;
      $seller             = SellerRequest::find($ticketId)->toArray();
      // Deactivate old tickect transaction
      $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
      $catalogCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();


      if( $data['pending_reason_id'] == 8 ) {
          $data['group_id']  = 1;
          $data['priority']  = 3;
      }elseif($data['stage_id'] == 6 ) { #TODO has to remove this value check
          $catalogCompleted   = Stage::where('stage_name', '(Central) Cataloging Completed')->first();
          $data['status_id']  = 3;
      }elseif($data['stage_id'] == 8 ) { #TODO has to remove this value check
          $data['status_id']  = 4;
          $catalogCompleted   = Stage::where('stage_name', '(Central) ASIN Created')->first();
      }

      $ticketData         = Ticket::ticketData($catalogCompleted->id, 1, $data);

      // Assgining to Local Team Lead
      $ticketData['assigned_to']          = Ticket::findUserByRoleAndCity('Local Team Lead', $seller['merchant_city_id']);;
      $ticketData['photographer_id']      = $data['photographer_id'];
      $ticketData['photoshoot_location']  = $data['photoshoot_location'];
      $ticketData['photoshoot_date']      = $data['photoshoot_date'];
      $ticketData['editingteamlead_id']   = $data['editingteamlead'];
      $ticketData['editor_id']            = $data['editor'];
      $ticketData['catalogingteamlead_id']= Auth::user()->id;
      $ticketData['cataloguer_id']        = $data['cataloguer'];
      $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

      if( $data['pending_reason_id'] != 8 or $data['status_id']  != 4) {

          // Assgining Editing Manager
          $user = new User;
          $editingManager            = $user->findUserByRoleName('Editing Manager');
          $ticketData['assigned_to'] = $editingManager[0]->id;
          $leadTransaction           = TicketTransaction::updateTicket($ticketData);

          // Assgining Editing Team Lead
          $ticketData['assigned_to'] = $data['editingteamlead'];
          $editingTransaction        = TicketTransaction::updateTicket($ticketData);

          // Assgining catalogue Manager
          $catalogueManager          = $user->findUserByRoleName('Catalogue Manager');
          $ticketData['assigned_to'] = $catalogueManager[0]->id;
          $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

          // Assgining catalogue Team Lead
          $ticketData['assigned_to'] = Auth::user()->id;
          $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

          // Assgining cataloguer
          $ticketData['assigned_to'] = $data['cataloguer'];
          $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);
      }

      return $leadTransaction->id;
  }


  public static function updateCatalogingComplete($ticketTransactionId, $ticketId, $data) {
        // print_r($data);exit;
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
        $editingCompleted   = Stage::where('stage_name', '(Central) Cataloging Completed')->first();
        $ticketData         = Ticket::ticketData($editingCompleted->id, 1, $data);

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = Ticket::findUserByRoleAndCity('Local Team Lead', $seller['merchant_city_id']);;
        $ticketData['photographer_id']      = $data['photographer_id'];
        $ticketData['photoshoot_location']  = $data['photoshoot_location'];
        $ticketData['photoshoot_date']      = $data['photoshoot_date'];
        $ticketData['editingteamlead_id']   = $data['editingteamlead'];
        $ticketData['editor_id']            = $data['editor'];
        $ticketData['catalogingteamlead_id']= $data['catalogueTeamLead'];
        $ticketData['cataloguer_id']        = Auth::user()->id;
        $leadTransaction                    = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Team Lead
        $ticketData['assigned_to']          = $data['editingteamlead'];
        $editingTransaction                 = TicketTransaction::updateTicket($ticketData);

        // Assgining catalogue Team Lead
        $ticketData['assigned_to'] = $data['catalogueTeamLead'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);
        // Assgining cataloguer
        $ticketData['assigned_to'] = Auth::user()->id;
        $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);

        return $cataloguerTransaction->id;
  }

  public static function findUserByRoleAndCity($role = 'Local Team Lead', $cityId) {
    $user       = new User;
    $loalLead   = $user->findAllByRoleAndCity($role, $cityId);
    return $loalLead[0]->id;
  }

  /**
   * Build Ticket Data
   * @var stageId Integer
   * @var status Integer
   * @var data Array
   *
   */
  public static function ticketData($stageId, $status, $data) {

      $ticketTransactionRule = TicketTransaction::$rules;

      // Add custom validation for date
      if( Input::has('photoshoot_date') ) {
          $ticketTransactionRule['photoshoot_date'] = 'After:'.Date('Y-m-d');
      }
      if( Input::has('mif_id') ) {
          $ticketTransactionRule['mif_id'] = 'required';
      }

      $validator = Validator::make($data, $ticketTransactionRule);
      // validation fails redirect to form with error
      if ($validator->fails()) {
        $errors = '';
        $messages = $validator->messages();
        foreach ($messages->all('<li>:message</li>') as $message)
        {
           $errors .= $message;
        }
        $errorMsg = json_encode(array('status'=>false, 'message' => $errors));
        throw new Exception($errorMsg);
      }

      $ticketData['ticket_id']    = $data['ticket_id'];
      $ticketData['status_id']    = $data['status_id'];
      $ticketData['stage_id']     = $stageId;
      $ticketData['priority']     = $data['priority'];
      $ticketData['group_id']     = $data['group_id'];
      $ticketData['active']       = $status;
      $ticketData['mif_id']       = $data['mif_id'];
      $ticketData['sa_sku']       = $data['sa_sku'];
      $ticketData['sa_variation'] = $data['sa_variation'];
      $ticketData['total_sku']    = ($data['total_sku'])?$data['total_sku']:NULL;
      $ticketData['total_images'] = ($data['total_images'])?$data['total_images']:NULL;
      $ticketData['notes']        = ($data['comment'])?$data['comment']:NULL;
      if(isset($data['pending_reason_id']))
      {
          $ticketData['pending_reason_id'] = ($data['pending_reason_id'])?$data['pending_reason_id']:NULL;
      }
      return $ticketData;
  }
}