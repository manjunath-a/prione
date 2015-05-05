<?php


class Ticket extends Eloquent
{
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
     * AssignTicket.
     *
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     * @return int
     *             Assgining from Local Team Lead to Photographer and service associates.
     */
    public static function assignTicket($ticketTransactionId, $ticketId, $data)
    {
        $ticketTransaction = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));

        $ticketData = self::TicketData($data['stage_id'], $data);

        // Checks for Open Status
        if ($ticketData['status_id'] == 1) {
            if (isset($ticketData['photographer_id']) && $data['photographer_id']) {
                $ticketData['assigned_to']        = $ticketData['photographer_id'];
                // Checks associate assigned stage else dont make entry in db
                if ($ticketData['stage_id'] == 2) {
                    $photographerTransaction = TicketTransaction::updateTicket($ticketData);
                }
            }
            // Assgining to Service Assiocate
            if ($ticketData['stage_id'] != 4 && $ticketData['mif_id']) {
                $ticketData['assigned_to']      = $ticketData['mif_id'];
                $serviceAssociateTransaction    = TicketTransaction::updateTicket($ticketData);
            }
        }
        // Assgining to Local Team Lead from Session user
        $ticketData['assigned_to'] = Auth::user()->id;
        $leadTransaction = TicketTransaction::updateTicket($ticketData);

        return $leadTransaction->id;
    }
    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updatePhotographer($ticketTransactionId, $ticketId, $data)
    {
        $cityId =  Auth::user()->city_id;
        // Update Team Lead
        $ticketTransaction = TicketTransaction::where('ticket_id', '=', $ticketId)
                                        ->where('active', '=', 1)
                                        ->update(array('active' => 0));
        $photoStageId = 3; // (Local) Photoshoot Completed

        if ($data['pending_reason_id'] != 0) {
            $photoStageId = 2; // (Local) Associates Assigned
            $photoRoleId =  9 ; //Auth::user()->roles; // Photogrpaher Role
            $ticketData['rejected_role'] = $photoRoleId;
        }
        $ticketData = self::TicketData($photoStageId, $data);

        $ticketData['photographer_id']     =  Auth::user()->id;
        // Assgining to Local Team Lead
        $ticketData['assigned_to'] = $ticketData['localteamlead_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);

        // Assgining to service Assiocate
        $ticketData['assigned_to']   = $ticketData['mif_id'];
        $serviceAssociateTransaction = TicketTransaction::updateTicket($ticketData);

        // Check for Potographer assigned
        if (Auth::user()->id) {
            $ticketData['assigned_to'] = Auth::user()->id;
            $ticketData['active']      = 0;
            $photographerTransaction   = TicketTransaction::updateTicket($ticketData);
        }

        return $leadTransaction->id;
    }

    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateMIF($ticketTransactionId, $ticketId, $data)
    {
        $ticketArray = TicketTransaction::find($data['transaction_id'])->toArray();
        $cityId =  Auth::user()->city_id;
        // Update Team Lead
        $ticketTransaction = TicketTransaction::where('ticket_id', '=', $ticketId)
                                        ->where('active', '=', 1)
                                        ->update(array('active' => 0));

        if ($data['pending_reason_id'] != 0) {
            $mifStageId = 9;//  (Local) Seller Images Provided
            if ( isset($data['photographer_id']) ) {
                //if ($data['image_available'] == 1) {
                if ( $data['photographer_id'] != 0 ) {
                    $mifStageId = 3;  // (Local) Photoshoot Completed
                }
            }
            // Assign Rejected Role
            $saRoleId = 10; // Auth::user()->roles;
            $ticketData['rejected_role'] = $saRoleId;
        } else {
            $mifStageId =  4; //(Local) MIF Completed
        }
        $ticketData = self::TicketData($mifStageId , $data);
        // Assgining to Local Team Lead
        $ticketData['assigned_to'] = $ticketData['localteamlead_id'];
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

    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function assignEditingManager($ticketTransactionId, $ticketId, $data)
    {
        $mifCompletedId = 4; //  (Local) MIF Completed

        $ticketData     = self::TicketData($mifCompletedId, $data);

        $ticketTransaction = TicketTransaction::where('ticket_id', '=', $ticketId)
                                                ->where('active', '=', 1)
                                                ->update(array('active' => 0));

        $user = new User();
        $editingManager  = $user->findUserByRoleName('Editing Manager');
        // Assgining Editing Manager
        $ticketData['editingmanager_id'] = $editingManager[0]->id;
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        if($ticketData['editingteamlead_id']){
            // Assgining Editing Team Lead
            $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
            $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        }
        // Assgining Local Team lead
        $ticketData['assigned_to']  = Auth::user()->id;
        $leadTransaction            = TicketTransaction::updateTicket($ticketData);

        return $leadTransaction->id;
    }

    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function assignCatalogingManager($ticketTransactionId, $ticketId, $data)
    {
        $localCompletedId =  5 ; //(Central) Editing Completed
        $ticketData     = self::TicketData($localCompletedId, $data);

        $ticketTransaction = TicketTransaction::where('ticket_id', '=', $ticketId)
                                                ->where('active', '=', 1)
                                                ->update(array('active' => 0));
        // Assgining Editing Manager
        if ($ticketData['editingmanager_id']) {
            $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
            $emTransaction          = TicketTransaction::updateTicket($ticketData);
        }
        // Assgining Editing Team Lead
        if ($ticketData['editingteamlead_id']) {
            $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
            $etlTransaction         = TicketTransaction::updateTicket($ticketData);
        }
        //Assgining To cataloging Manager
        if ($ticketData['catalogingmanager_id']) {
            $ticketData['assigned_to']  = $ticketData['catalogingmanager_id'];
            $cmTransaction           = TicketTransaction::updateTicket($ticketData);
        }
        //Assgining To cataloging Team Lead
        if ($ticketData['catalogingteamlead_id']) {
            $ticketData['assigned_to']  = $ticketData['catalogingteamlead_id'];
            $ctlTransaction           = TicketTransaction::updateTicket($ticketData);
        }
        // Assgining Local Team lead
        $ticketData['assigned_to']  = $ticketData['localteamlead_id'];
        $leadTransaction            = TicketTransaction::updateTicket($ticketData);

        return $leadTransaction->id;
    }

    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateEditingManager($ticketTransactionId, $ticketId, $data)
    {
        $localCompletedId   =  4; //(Local) MIF Completed
        $ticketData         = self::TicketData($localCompletedId, $data);

        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                            ->where('active', '=', 1)
                                            ->update(array('active' => 0));
        // Assgining Editing Manager
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Local Team Lead
        $ticketData['assigned_to'] = $ticketData['localteamlead_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Team Lead
        $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
        $editorTransaction         = TicketTransaction::updateTicket($ticketData);

        return $editorTransaction->id;
    }

    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateAssignEditor($ticketTransactionId, $ticketId, $data)
    {
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));
        $localCompletedId   =  4;  //(Local) MIF Completed
        $ticketData         = self::TicketData($localCompletedId, $data);
        // 6 = Raw Images QC Failed
        if ($data['pending_reason_id'] == 6) {
            $ticketData['group_id']  = 1;
            $ticketData['priority']  = 3;
            $ticketData['stage_id']     = 1;  //(Local) Associates Not Assigned
            // Assign Rejected Role
            $etlRole = Auth::user()->roles;
            $ticketData['rejected_role'] = $etlRole[0]->id;
        }
        // 5 = Editing Images QC Failed
        if ($data['pending_reason_id'] == 5) {
            $ticketData['priority']  = 3;
            $ticketData['stage_id']  = 4; //(Local) MIF Completed
            // Assign Rejected Role
            $etlRole = Auth::user()->roles;
            $ticketData['rejected_role'] = $etlRole[0]->id;
        }

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $ticketData['localteamlead_id'];
        $ticketData['editingteamlead_id']   = Auth::user()->id;
        $editorTransaction                  = TicketTransaction::updateTicket($ticketData);

        if ($data['pending_reason_id'] != 6) {
            // Assgining Editing Manager
            $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
            $editorTransaction         = TicketTransaction::updateTicket($ticketData);

            // Assgining Editing Team Lead
            $ticketData['assigned_to'] = Auth::user()->id;
            $editorTransaction         = TicketTransaction::updateTicket($ticketData);

            if ($ticketData['editor_id']) {
                // Assgining Editor
                $ticketData['assigned_to'] = $ticketData['editor_id'];
                $editorTransaction       = TicketTransaction::updateTicket($ticketData);
            }
        }

        return $editorTransaction->id;
    }

    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function assignLocalTeamLead($ticketTransactionId, $ticketId, $data)
    {
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));
        $localCompletedId     =  4 ; //(Local) MIF Completed
        $ticketData         = self::TicketData($localCompletedId, $data);
        // 6 = Raw Images QC Failed
        if ($data['pending_reason_id'] == 6) {
            $ticketData['group_id']  = 1;
            $ticketData['priority']  = 3;
            $stageNotAssignedId  = 1; //(Local) Associates Not Assigned
            $ticketData['stage_id']     = $stageNotAssignedId;
            // Assign Rejected Role
            $etlRole = Auth::user()->roles;
            $ticketData['rejected_role'] = $etlRole[0]->id;
        }

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $ticketData['localteamlead_id'];
        $ticketData['editingteamlead_id']   = Auth::user()->id;
        $editorTransaction                  = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Team Lead
        $ticketData['active'] = 0;
        $ticketData['assigned_to'] = Auth::user()->id;
        $editorTransaction         = TicketTransaction::updateTicket($ticketData);


        return $editorTransaction->id;
    }


    /**
     * Editor Update
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateEditingComplete($ticketTransactionId, $ticketId, $data)
    {
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                        ->where('active', '=', 1)
                                        ->update(array('active' => 0));
        $editingStageId =  5 ; //(Central) Editing Completed
        $ticketData       = self::TicketData($editingStageId, $data);

        // 6 = Raw Images QC Failed
        if ($data['pending_reason_id'] == 6) {
            $ticketData['stage_id'] = 4; //(Local) MIF Completed
            $ticketData['priority'] = 3;
            // Assign Rejected Role
            $etlRole = Auth::user()->roles;
            $ticketData['rejected_role'] = $etlRole[0]->id;
        }

        // Assgining to Local Team Lead
        $ticketData['assigned_to'] = $ticketData['localteamlead_id'];
        $editorTransaction         = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Manager
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $editorTransaction         = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Team Lead
        $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
        $editorTransaction         = TicketTransaction::updateTicket($ticketData);
        // Assgining Editor
        $ticketData['assigned_to'] = $ticketData['editor_id'];
        $ticketData['active']      = 0;
        $editorTransaction         = TicketTransaction::updateTicket($ticketData);
        return $editorTransaction->id;
    }

    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateCatalogManager($ticketTransactionId, $ticketId, $data)
    {
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));
        $editingCompletedId   = 5; // (Central) Editing Completed ;
        $ticketData         = self::TicketData($editingCompletedId, $data);

        $user = new User();
        $catalogueManager          = $user->findUserByRoleName('Catalog Manager');
        $ticketData['catalogingmanager_id']    = $catalogueManager[0]->id;

        // Assgining to Local Team Lead
        $ticketData['assigned_to'] = $ticketData['localteamlead_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining catalogue Manager
        $ticketData['assigned_to'] = $ticketData['catalogingmanager_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Manager
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $editorTransaction         = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Team Lead
        $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
        $editingTransaction        = TicketTransaction::updateTicket($ticketData);

        return $catalogueTransaction->id;
    }

    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateAssignCatalogTeamLead($ticketTransactionId, $ticketId, $data)
    {
        // Deactivate old active tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                        ->where('active', '=', 1)
                                        ->update(array('active' => 0));
        $editingCompletedId =  5 ; // (Central) Editing Completed
        $ticketData         = self::TicketData($editingCompletedId, $data);

        // Assgining Local Team Lead
        $ticketData['assigned_to'] = $ticketData['localteamlead_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Manager
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Team Lead
        $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
        $editingTransaction        = TicketTransaction::updateTicket($ticketData);
        // Assgining catalogue Manager
        $ticketData['assigned_to'] = $ticketData['catalogingmanager_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);
        // Assgining catalogue TeamLead
        $ticketData['assigned_to'] = $ticketData['catalogingteamlead_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        return $catalogueTransaction->id;
    }

    /**
     *  CTL - Catalog Team Lead.
     *
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateCatalogTeamLead($ticketTransactionId, $ticketId, $data)
    {
        // Deactivate old active tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));
        // (Central) QC Completed [  OR ] (Central) ASIN Created'
        $catalogStageId = $data['stage_id'];

        // 7 = MIF QC failed
        if ($data['pending_reason_id'] == 7) {
            $catalogStageId = 5 ; //(Central) Editing Completed
            $data['group_id']  = 3; // Cataloging
            $data['priority']  = 3; // Higher
            $data['status_id']  = 1;  // Open
            // Assign Rejected Role
            $ctlRole = Auth::user()->roles;
            $data['rejected_role'] = $ctlRole[0]->id;
        }
        // 8 =Flat File QC failed
        if ($data['pending_reason_id'] == 8) {
            $catalogStageId = 1;  //(Local) Associates Not Assigned
            $data['group_id']  = 1; // Local
            $data['priority']  = 3; // Higher
            $data['status_id']  = 1; // Open
            // Assign Rejected Role
            $ctlRole = Auth::user()->roles;
            $data['rejected_role'] = $ctlRole[0]->id;
        }

        $ticketData         = self::TicketData($catalogStageId, $data);

        $ticketData['catalogingteamlead_id'] = Auth::user()->id;

        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $ticketData['localteamlead_id'];
        $leadTransaction = TicketTransaction::updateTicket($ticketData);

        if ($data['pending_reason_id'] == 8) {
            $ticketData['active'] = 0;
        }

        if ($ticketData['cataloguer_id']) {
            // Assgining cataloguer
            $ticketData['assigned_to'] = $ticketData['cataloguer_id'];
            $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);
        }
        // Assgining Editing Manager
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Team Lead
        $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
        $editingTransaction        = TicketTransaction::updateTicket($ticketData);

        // Assgining catalogue Manager
        $ticketData['assigned_to'] = $ticketData['catalogingmanager_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        // Assgining catalogue Team Lead
        $ticketData['assigned_to'] = $ticketData['catalogingteamlead_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        return $leadTransaction->id;
    }

    /**
     *  CTL to LTL - Catalog Team Lead to Local Team Lead.
     *
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateCatalogingTeamLeadToLocalTeamLead($ticketTransactionId, $ticketId, $data)
    {

        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));
        $catalogStageId = 1; // (Local) Associates Not Assigned
        // Build Default Ticket Values
        $ticketData         = self::TicketData($catalogStageId, $data);

        $ticketData['group_id']  = 1; // Local
        $ticketData['priority']  = 3; // Higher
        $ticketData['status_id']  = 1; // Open

        // Assign Rejected Role
        $ctlRole = Auth::user()->roles;
        $ticketData['rejected_role'] = $ctlRole[0]->id;

        // Assgining to Local Team Lead
        $ticketData['assigned_to'] = $ticketData['localteamlead_id'];
        $leadTransaction = TicketTransaction::updateTicket($ticketData);

         // Assgining Editing Manager
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Team Lead
        $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
        $editingTransaction        = TicketTransaction::updateTicket($ticketData);

        // Assgining catalogue Manager
        $ticketData['assigned_to'] = $ticketData['catalogingmanager_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

         // Assgining catalogue Team Lead
        $ticketData['assigned_to'] = $ticketData['catalogingteamlead_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        return $leadTransaction->id;
    }
    /**
     * @param int   $ticketTransactionId
     * @param int   $ticketId
     * @param array $data
     *
     * @return int
     */
    public static function updateCataloguer($ticketTransactionId, $ticketId, $data)
    {
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                        ->where('active', '=', 1)
                                        ->update(array('active' => 0));
        $catalogCompletedId   =  5; //(Central) Editing Completed ;
        // Default visible status =1
        $status = 1;

        // 8 == Flat File QC failed (Rejection)
        if ($data['pending_reason_id'] == 8) {
            $catalogCompletedId = 5; //(Central) Editing Completed ;
            $data['priority']  = 3; // Urgent
            // Assign Rejected Role
            $ctlRoleId = 4; //Cataloger;
            $data['rejected_role'] = $ctlRoleId;
        } elseif ($data['stage_id'] == 6) {
            $catalogCompletedId   =  6;//(Central) Cataloging Completed ;
            $data['status_id']  = 3; // Resolved
        } elseif ($data['stage_id'] == 8) {
            $data['status_id']  = 4; // Closed
            $status = 0; // Ticket Visible Status false
            $catalogCompletedId   = 8;  //(Central) ASIN Created ;
        }
        // Assign submitted data with old ticket data
        $ticketData  = self::TicketData($catalogCompletedId, $data , $status);

        $ticketData['cataloguer_id'] =  Auth::user()->id;
        // Assgining to Local Team Lead
        $ticketData['assigned_to'] = $ticketData['localteamlead_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);

        // Assgining Editing Manager
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Team Lead
        $ticketData['assigned_to'] =  $ticketData['editingteamlead_id'];
        $editingTransaction        = TicketTransaction::updateTicket($ticketData);
        // Assgining catalogue Manager
        $ticketData['assigned_to'] = $ticketData['catalogingmanager_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);
        // Assgining catalogue Team Lead
        $ticketData['assigned_to'] = $ticketData['catalogingteamlead_id'];
        $catalogueTransaction      = TicketTransaction::updateTicket($ticketData);

        if ($data['pending_reason_id'] == 8) {
            $ticketData['active']  = 0; // In active
        }
        // Assgining cataloguer
        $ticketData['assigned_to'] = $ticketData['cataloguer_id'];
        $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);

        return $leadTransaction->id;
    }
    /**
     * @param string $role
     * @param int    $cityId
     *
     * @return int
     */
    public static function findUserByRoleAndCity($role = 'Local Team Lead', $cityId)
    {
        $user       = new User();
        $loalLead   = $user->findAllByRoleAndCity($role, $cityId);

        return $loalLead[0]->id;
    }

    /**
     * Build Ticket Data.
     *
     * @var stageId Integer
     * @var data    Array
     * @var status  Integer defualt will be active
     */
    public static function TicketData($stageId, $data, $status = 1)
    {
        $ticketTransactionRule = TicketTransaction::$rules;
        // Current Ticket Data
        $ticketTransaction = TicketTransaction::find($data['transaction_id'])->toArray();
        $validator = Validator::make($data, $ticketTransactionRule);
        // validation fails redirect to form with error
        if ($validator->fails()) {
            $errors = '';
            $messages = $validator->messages();
            foreach ($messages->all('<li>:message</li>') as $message) {
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
        $ticketData['read_status']  = 0;

        // Local Team Lead
        $ticketData['localteamlead_id'] = (isset($data['localteamlead_id'])) ? $data['localteamlead_id'] : $ticketTransaction['localteamlead_id'];
        // Photgrapher
        $ticketData['photographer_id']      = (isset($data['photographer_id'])) ? $data['photographer_id'] : $ticketTransaction['photographer_id'];
        $ticketData['photographer_id'] = ($ticketData['photographer_id']==0)?NULL:$ticketData['photographer_id'];
        $ticketData['photoshoot_location']  = (isset($data['photoshoot_location'])) ? $data['photoshoot_location'] : $ticketTransaction['photoshoot_location'];
        $ticketData['photoshoot_date']      = (isset($data['photoshoot_date'])) ? $data['photoshoot_date'] : $ticketTransaction['photoshoot_date'];
        // Service Assoicates
        $ticketData['mif_id'] = (isset($data['mif_id'])) ? $data['mif_id'] : $ticketTransaction['mif_id'];
        $ticketData['mif_id'] = ($ticketData['mif_id']==0)?NULL:$ticketData['mif_id'];
        // Editing Manager
        $ticketData['editingmanager_id'] = (isset($data['editingmanager_id'])) ? $data['editingmanager_id'] : $ticketTransaction['editingmanager_id'];
        $ticketData['editingmanager_id'] = ($ticketData['editingmanager_id']==0)?NULL:$ticketData['editingmanager_id'];
        // Editing Team Lead
        $ticketData['editingteamlead_id'] = (isset($data['editingteamlead_id'])) ? $data['editingteamlead_id'] : $ticketTransaction['editingteamlead_id'];
        $ticketData['editingteamlead_id'] = ($ticketData['editingteamlead_id']==0)?NULL:$ticketData['editingteamlead_id'];
        // Editor
        $ticketData['editor_id'] = (isset($data['editor_id'])) ? $data['editor_id'] : $ticketTransaction['editor_id'];
        $ticketData['editor_id'] = ($ticketData['editor_id']==0)?NULL:$ticketData['editor_id'];
        // Cataloging Manager
        $ticketData['catalogingmanager_id'] = (isset($data['catalogingmanager_id'])) ? $data['catalogingmanager_id'] : $ticketTransaction['catalogingmanager_id'];
        $ticketData['catalogingmanager_id'] = ($ticketData['catalogingmanager_id']==0)?NULL:$ticketData['catalogingmanager_id'];
        // Cataloging Team Lead
        $ticketData['catalogingteamlead_id'] = (isset($data['catalogingteamlead_id'])) ? $data['catalogingteamlead_id'] : $ticketTransaction['catalogingteamlead_id'];
        $ticketData['catalogingteamlead_id'] = ($ticketData['catalogingteamlead_id']==0)?NULL:$ticketData['catalogingteamlead_id'];
        // Cataloger
        $ticketData['cataloguer_id'] = (isset($data['cataloguer_id'])) ? $data['cataloguer_id'] : $ticketTransaction['cataloguer_id'];
        $ticketData['cataloguer_id'] = ($ticketData['cataloguer_id']==0)?NULL:$ticketData['cataloguer_id'];

        $ticketData['sa_sku']       = (isset($data['sa_sku']))?$data['sa_sku']:$ticketTransaction['sa_sku'];
        $ticketData['sa_variation'] = (isset($data['sa_variation']))?$data['sa_variation']:$ticketTransaction['sa_variation'];
        $ticketData['total_sku']    = (isset($data['total_sku'])) ? $data['total_sku'] : $ticketTransaction['total_sku'];
        $ticketData['total_images'] = (isset($data['total_images'])) ? $data['total_images'] : $ticketTransaction['total_images'];
        $ticketData['notes']        = (isset($data['comment'])) ? $data['comment'] : $ticketTransaction['notes'];
        $ticketData['created_by']   =  Auth::user()->id;
        $ticketData['rejected_role'] = (isset($data['rejected_role'])) ? $data['rejected_role'] :$ticketTransaction['rejected_role'];
        if (isset($data['pending_reason_id'])) {
            $ticketData['pending_reason_id'] = ($data['pending_reason_id']) ? $data['pending_reason_id'] : NULL;
            if($data['pending_reason_id']) {
                $userRole = Auth::user()->roles;
                $ticketData['rejected_role'] = $userRole[0]->id;
            } else {
                $ticketData['rejected_role'] = NULL;
            }
        }
        // var_dump($ticketTransaction);
        // var_dump($ticketData);exit;
        return $ticketData;
    }
}
