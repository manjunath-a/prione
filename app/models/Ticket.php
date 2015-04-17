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

    public function request()
    {
        return $this->belongsTo('SellerRequest');
    }

    /**
     * AssignTicket
     *  Assgining from Local Team Lead to Photographer and service associates
     */

    public static function assignTicket($ticketTransactionId, $ticketId, $data)
    {

      $ticketTransaction = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));

      $ticketData = Ticket::ticketData($data['stage_id'], 1, $data);

      // Checks for Open Status
      if($ticketData['status_id'] == 1)   {
          if(isset($ticketData['photographer_id'])) {
              $ticketData['assigned_to']        = $data['photographer_id'];
              // Checks associate assigned stage else dont make entry in db
              if($ticketData['stage_id']==2) {
                $photographerTransaction = TicketTransaction::updateTicket($ticketData);
              }
          }
          if($ticketData['stage_id']!=4 && $ticketData['mif_id']) {
            // Assgining to Service Assiocate
            $ticketData['assigned_to']      = $data['mif_id'];
            $serviceAssociateTransaction    = TicketTransaction::updateTicket($ticketData);
          }
      }
      // Assgining to Local Team Lead from Session user
      $ticketData['assigned_to'] = Auth::user()->id;
      $leadTransaction = TicketTransaction::updateTicket($ticketData);

      return $leadTransaction->id;
    }

    public static function updatePhotographer($ticketTransactionId, $ticketId, $data)
    {

        $cityId =  Auth::user()->city_id;
        // Update Team Lead
        $ticketTransaction = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
        $photoStage = Stage::where('stage_name', '(Local) Photoshoot Completed')->first();

        if( $data['pending_reason_id'] != 0 ) {
            $photoStage = Stage::where('stage_name', '(Local) Associates Assigned')->first();
        }

        $ticketData = Ticket::TicketData($photoStage->id, 1, $data);
        $ticketData['photographer_id']     =  Auth::user()->id;

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


    public static function updateMIF($ticketTransactionId, $ticketId, $data)
    {
        $ticketArray = TicketTransaction::find($data['transaction_id'])->toArray();
        $cityId =  Auth::user()->city_id;
        // Update Team Lead
        $ticketTransaction = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));

        if( $data['pending_reason_id'] != 0 ) {
            if($data['image_available']== 1)
                $mifStage = Stage::where('stage_name', '(Local) Photoshoot Completed')->first();
            else
                $mifStage = Stage::where('stage_name', '(Local) Seller Images Provided')->first();
        } else {
            $mifStage = Stage::where('stage_name', '(Local) MIF Completed')->first();
        }

        $ticketData = Ticket::ticketData($mifStage->id, 1, $data);
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

    public static function assignEditingManager($ticketTransactionId, $ticketId, $data)
    {

        $cityId         =  Auth::user()->city_id;
        $localCompleted = Stage::where('stage_name', '(Local) MIF Completed')->first();
        $ticketData     = Ticket::ticketData($localCompleted->id, 1, $data);

        $ticketTransaction          = TicketTransaction::where('ticket_id', '=' , $ticketId)
        ->update(array('active' => 0));

        $user = new User;
        $editingManager            = $user->findUserByRoleName('Editing Manager');
        $ticketData['editingmanager_id'] = $ticketData['assigned_to'] = $editingManager[0]->id;
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Local Team lead
        // Update Team Lead
        $ticketData['assigned_to']  = Ticket::findUserByRoleAndCity('Local Team Lead', $cityId);
        $leadTransaction            = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Manager
        return $leadTransaction->id;
    }

    /**
     * LTL to CM
     */
    public static function assignCatalogingManager($ticketTransactionId, $ticketId, $data)
    {

        $localCompleted = Stage::where('stage_name', '(Central) Editing Completed')->first();
        $ticketData     = Ticket::ticketData($localCompleted->id, 1, $data);

        $ticketTransaction          = TicketTransaction::where('ticket_id', '=' , $ticketId)
        ->update(array('active' => 0));

        $ticketData['editingmanager_id'] = ($data['editingmanager_id'])?$data['editingmanager_id']:NULL;
        if($data['editingmanager_id']) {
            // Assgining Editing Manager
            $ticketData['assigned_to'] = $data['editingmanager_id'];
            $editorTransaction          = TicketTransaction::updateTicket($ticketData);
        }
        $ticketData['editingteamlead_id'] = ($data['editingteamlead_id'])?$data['editingteamlead_id']:NULL;
        if($data['editingteamlead_id']) {
            // Assgining Editing Team Lead
            $ticketData['assigned_to'] = $data['editingteamlead_id'];
            $editorTransaction         = TicketTransaction::updateTicket($ticketData);
        }

        $ticketData['editor_id'] = ($data['editor_id'])?$data['editor_id']:NULL;

        $ticketData['catalogingmanager_id'] = $data['catalogingmanager_id'];
        if($data['catalogingmanager_id']) {
            //Assgining To cataloging Manager
            $ticketData['assigned_to']  = $ticketData['catalogingmanager_id'];
            $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        }

        // Assgining Local Team lead
        // Update Team Lead
        $ticketData['assigned_to']  = $ticketData['localteamlead_id'];
        $leadTransaction            = TicketTransaction::updateTicket($ticketData);
        return $leadTransaction->id;
    }

    public static function updateEditingManager($ticketTransactionId, $ticketId, $data)
    {

        $localCompleted     = Stage::where('stage_name', '(Local) MIF Completed')->first();
        $ticketData         = Ticket::ticketData($localCompleted->id, 1, $data);

        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)
                                ->update(array('active' => 0));
        $ticketData['editingmanager_id']    = $data['editingmanager_id'];
        $ticketData['editingteamlead_id']   = $data['editingteamlead_id'];

        // Assgining Editing Manager
        $ticketData['assigned_to'] = $data['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Local Team Lead
        $ticketData['assigned_to'] = $data['localteamlead_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Team Lead
        $ticketData['assigned_to']          = $data['editingteamlead_id'];
        $editorTransaction                  = TicketTransaction::updateTicket($ticketData);
        return $editorTransaction->id;
  }

    /**
     * ETL - Editing Team Lead
     */
    public static function updateAssignEditor($ticketTransactionId, $ticketId, $data)
    {
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' , $ticketId)->update(array('active' => 0));
        $localCompleted     = Stage::where('stage_name', '(Local) MIF Completed')->first();
        $ticketData         = Ticket::ticketData($localCompleted->id, 1, $data);
        // 6 = Raw Images QC Failed
        if( $data['pending_reason_id'] == 6 ) {
            $ticketData['group_id']  = 1;
            $ticketData['priority']  = 3;
            $stageNotAssigned  = Stage::where('stage_name', '(Local) Associates Not Assigned')->first();
            $ticketData['stage_id']     = $stageNotAssigned->id;
        }
        // 5 = Editing Images QC Failed
        if( $data['pending_reason_id'] == 5 ) {
            $ticketData['priority']  = 3;
            $stageMif  = Stage::where('stage_name', '(Local) MIF Completed')->first();
            $ticketData['stage_id']     = $stageMif->id;
        }

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $data['localteamlead_id'];

        $ticketData['editingmanager_id']    = $data['editingmanager_id'];
        $ticketData['editingteamlead_id']   = Auth::user()->id;
        if($data['editor']!=0) {
            $ticketData['editor_id']            = $data['editor'];
        }
        $editorTransaction                  = TicketTransaction::updateTicket($ticketData);

        if( $data['pending_reason_id'] != 6 ) {
            // Assgining Editing Manager
            $ticketData['assigned_to'] = $data['editingmanager_id'];
            $editorTransaction           = TicketTransaction::updateTicket($ticketData);

            // Assgining Editing Team Lead
            $ticketData['assigned_to']          = Auth::user()->id;
            $editorTransaction                  = TicketTransaction::updateTicket($ticketData);

            if($data['editor']!=0) {
              // Assgining Editor
              $ticketData['assigned_to']          = $data['editor'];
              $editorTransaction                  = TicketTransaction::updateTicket($ticketData);
            }
        }
        return $editorTransaction->id;
    }

    /**
     *  - Editor
     */
    public static function updateEditingComplete($ticketTransactionId, $ticketId, $data)
    {
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)
        ->update(array('active' => 0));

        if( $data['pending_reason_id'] == 6 ) {
            $editingStage     = Stage::where('stage_name', '(Local) Associates Not Assigned')->first();
            $data['group_id'] = 1;
            $data['priority'] = 3;
        } else {
            $editingStage = Stage::where('stage_name', '(Central) Editing Completed')->first();
        }

        $ticketData       = Ticket::ticketData($editingStage->id, 1, $data);
        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $data['localteamlead_id'];

        $ticketData['editingmanager_id']    = $data['editingmanager_id'];
        $ticketData['editingteamlead_id']   = $data['editingteamlead_id'];
        $ticketData['editor_id']            = $data['editor'];
        $editorTransaction                    = TicketTransaction::updateTicket($ticketData);

        // If not the pending reason update  EM, ETL and ED
        if( $data['pending_reason_id'] == 0 ) {

            // Assgining Editing Manager
            $ticketData['assigned_to'] = $data['editingmanager_id'];
            $editorTransaction           = TicketTransaction::updateTicket($ticketData);
            // Assgining Editing Team Lead
            $ticketData['assigned_to'] = $data['editingteamlead_id'];
            $editorTransaction         = TicketTransaction::updateTicket($ticketData);
            // Assgining Editor
            $ticketData['assigned_to']     = $data['editor'];
            $ticketData['active']          = 0;
            $editorTransaction             = TicketTransaction::updateTicket($ticketData);
        }
        return $editorTransaction->id;
    }

    /**
     * ETL  To CM - CatalogManager
     */
    public static function updateCatalogManager($ticketTransactionId, $ticketId, $data)
    {
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
        $editingCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
        $ticketData         = Ticket::ticketData($editingCompleted->id, 1, $data);
        $user = new User;
        $catalogueManager          = $user->findUserByRoleName('Catalogue Manager');
        $ticketData['catalogingmanager_id']    = $catalogueManager[0]->id;

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $data['localteamlead_id'];
        $ticketData['editingmanager_id']    = $data['editingmanager_id'];
        $ticketData['editingteamlead_id']   = $data['editingteamlead_id'];
        $ticketData['editor_id']            = $data['editor'];
        $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

         // Assgining catalogue Manager

        $ticketData['assigned_to'] = $catalogueManager[0]->id;
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Manager
        $ticketData['assigned_to'] = $data['editingmanager_id'];
        $editorTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Team Lead
        $ticketData['assigned_to']          = $ticketData['editingteamlead_id'];
        $editingTransaction                 = TicketTransaction::updateTicket($ticketData);
        return $catalogueTransaction->id;
    }

    /**
     * CM to CTL - Catalog Team Lead
     */

    public static function updateAssignCatalogTeamLead($ticketTransactionId, $ticketId, $data)
    {
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
        $editingCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
        $ticketData         = Ticket::ticketData($editingCompleted->id, 1, $data);

       // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $data['localteamlead_id'];
        $ticketData['catalogingmanager_id']   = $data['catalogingmanager_id'];
        $ticketData['editingmanager_id']   = $data['editingmanager_id'];
        $ticketData['editingteamlead_id']   = $data['editingteamlead_id'];
        $ticketData['editor_id']            = $data['editor'];
        $ticketData['catalogingteamlead_id']= $data['catalogingteamlead_id'];
        $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Manager
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Team Lead
        $ticketData['assigned_to'] = $data['editingteamlead_id'];
        $editingTransaction        = TicketTransaction::updateTicket($ticketData);

        // Assgining catalogue Manager
        $ticketData['assigned_to'] = $data['catalogingmanager_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        // Assgining catalogue TeamLead
        $ticketData['assigned_to'] = $data['catalogingteamlead_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        return $catalogueTransaction->id;
    }

    /**
     *  CTL - Catalog Team Lead
     */

    public static function updateCatalogTeamLead($ticketTransactionId, $ticketId, $data)
    {
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
        $catalogStage = Stage::where('stage_name', '(Central) Editing Completed')->first();

        if( $data['stage_id'] == 7 ) {
            $catalogStage     = Stage::where('stage_name', '(Central) QC Completed')->first();
        }
        if( $data['stage_id'] == 8 ) {
            $catalogStage     = Stage::where('stage_name', '(Central) ASIN Created')->first();
        }

        if( $data['pending_reason_id'] == 7 ) {
            $catalogStage = Stage::where('stage_name', '(Central) Editing Completed')->first();
            $data['group_id']  = 3;
            $data['priority']  = 3;
            $data['status_id']  = 1;
        }

        if( $data['pending_reason_id'] == 8 ) {
            $catalogStage = Stage::where('stage_name', '(Local) Seller Images Provided')->first();

            if($data['image_available']==1)
                $catalogStage = Stage::where('stage_name', '(Local) Photoshoot Completed')->first();

            $data['group_id']  = 1;
            $data['priority']  = 3;
            $data['status_id']  = 1;
        }

        $ticketData         = Ticket::ticketData($catalogStage->id, 1, $data);

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $data['localteamlead_id'];

        $ticketData['catalogingmanager_id'] = $data['catalogingmanager_id'];
        $ticketData['editingmanager_id']    = $data['editingmanager_id'];
        $ticketData['editingteamlead_id']   = $data['editingteamlead_id'];
        $ticketData['catalogingteamlead_id'] = $data['catalogingteamlead_id'];
        $ticketData['editor_id']            = $data['editor'];

        if($data['cataloguer']) {
            $ticketData['catalogingteamlead_id']= Auth::user()->id;
            $ticketData['cataloguer_id']        = $data['cataloguer'];
            $leadTransaction                    = TicketTransaction::updateTicket($ticketData);
        }
        // check for Pending action
        if( $data['pending_reason_id'] != 8 ) {

            // Assgining Editing Manager
            $ticketData['assigned_to'] = $data['editingmanager_id'];
            $leadTransaction           = TicketTransaction::updateTicket($ticketData);

            // Assgining Editing Team Lead
            $ticketData['assigned_to'] = $data['editingteamlead_id'];
            $editingTransaction        = TicketTransaction::updateTicket($ticketData);

            // Assgining catalogue Manager
            $ticketData['assigned_to'] = $data['catalogingmanager_id'];
            $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

            // Assgining catalogue Team Lead
            $ticketData['assigned_to'] = $data['catalogingteamlead_id'];
            $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

            if($data['cataloguer']) {
                // Assgining cataloguer
                $ticketData['assigned_to'] = $data['cataloguer'];
                $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);
            }
        }
        return $leadTransaction->id;
    }

    public static function updateCataloguer($ticketTransactionId, $ticketId, $data)
    {

        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));
        $catalogCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
        // Default Active status =1
        $status =1;
        // 8 == Flat File MIF QC Failed (Rejection)
        if( $data['pending_reason_id'] == 8 ) {
            if($data['image_available']==1)
                $catalogCompleted = Stage::where('stage_name', '(Local) Photoshoot Completed')->first();
            else
                $catalogCompleted = Stage::where('stage_name', '(Central) Editing Completed')->first();
            $data['group_id']  = 1; //Local Group
            $data['priority']  = 3; // Urgent

        } elseif($data['stage_id'] == 6 ) {
            $catalogCompleted   = Stage::where('stage_name', '(Central) Cataloging Completed')->first();
            $data['status_id']  = 3; // Resolved

        } elseif($data['stage_id'] == 8 ) {
            $data['status_id']  = 4; // Closed
            $status = 0;
            $catalogCompleted   = Stage::where('stage_name', '(Central) ASIN Created')->first();
        }

        $ticketData         = Ticket::ticketData($catalogCompleted->id, $status, $data);

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $data['localteamlead_id'];

        $ticketData['catalogingmanager_id'] = $data['catalogingmanager_id'];
        $ticketData['editingmanager_id']    = $data['editingmanager_id'];
        $ticketData['editingteamlead_id']   = $data['editingteamlead_id'];
        $ticketData['catalogingteamlead_id'] = $data['catalogingteamlead_id'];
        $ticketData['editor_id']            = $data['editor'];
        $ticketData['cataloguer_id']        = $data['cataloguer'];
        $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

        if( $data['pending_reason_id'] != 8 ) {
            // Assgining Editing Manager
            $ticketData['assigned_to'] = $data['editingmanager_id'];
            $leadTransaction           = TicketTransaction::updateTicket($ticketData);
            // Assgining Editing Team Lead
            $ticketData['assigned_to'] =  $data['editingteamlead_id'];
            $editingTransaction        = TicketTransaction::updateTicket($ticketData);
            // Assgining catalogue Manager
            $ticketData['assigned_to'] = $data['catalogingmanager_id'];
            $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);
            // Assgining catalogue Team Lead
            $ticketData['assigned_to'] = $data['catalogingteamlead_id'];
            $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);
            // Assgining cataloguer
            $ticketData['assigned_to'] = $data['cataloguer'];
            $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);
        }
        return $leadTransaction->id;
    }

    public static function findUserByRoleAndCity($role = 'Local Team Lead', $cityId)
    {
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
    public static function ticketData($stageId, $status, $data)
    {

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
          throw new Exception($errors);
        }

        $ticketData['ticket_id']    = $data['ticket_id'];
        $ticketData['status_id']    = $data['status_id'];
        $ticketData['stage_id']     = $stageId;
        $ticketData['priority']     = $data['priority'];
        $ticketData['group_id']     = $data['group_id'];
        $ticketData['active']       = $status;
        $ticketData['localteamlead_id'] = $data['localteamlead_id'];
        if($data['photographer_id']) {
            $ticketData['photographer_id']      = ($data['photographer_id'])?$data['photographer_id']:NULL;
            $ticketData['photoshoot_location']  = ($data['photoshoot_location'])?$data['photoshoot_location']:NULL;
            $ticketData['photoshoot_date']      = ($data['photoshoot_date'])?$data['photoshoot_date']:NULL;
        }
        $ticketData['mif_id']       = ($data['mif_id'])?$data['mif_id']:NULL;
        $ticketData['sa_sku']       = $data['sa_sku'];
        $ticketData['sa_variation'] = $data['sa_variation'];
        $ticketData['total_sku']    = ($data['total_sku'])?$data['total_sku']:NULL;
        $ticketData['total_images'] = ($data['total_images'])?$data['total_images']:NULL;
        $ticketData['notes']        = ($data['comment'])?$data['comment']:NULL;
        $ticketData['created_by']   =  Auth::user()->id;
        if(isset($data['pending_reason_id'])) {
            $ticketData['pending_reason_id'] = ($data['pending_reason_id'])?$data['pending_reason_id']:NULL;
        }
        return $ticketData;
    }
}