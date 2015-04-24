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
            if (isset($ticketData['photographer_id'])) {
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
        $photoStage = Stage::where('stage_name', '(Local) Photoshoot Completed')->first();
        $ticketData = self::TicketData($photoStage->id, $data);

        if ($data['pending_reason_id'] != 0) {
            $photoStage = Stage::where('stage_name', '(Local) Associates Assigned')->first();
        }

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
            if ($data['image_available'] == 1) {
                $mifStage = Stage::where('stage_name', '(Local) Photoshoot Completed')->first();
            } else {
                $mifStage = Stage::where('stage_name', '(Local) Seller Images Provided')->first();
            }
        } else {
            $mifStage = Stage::where('stage_name', '(Local) MIF Completed')->first();
        }
        $ticketData = self::TicketData($mifStage->id, $data);

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
        $localCompleted = Stage::where('stage_name', '(Local) MIF Completed')->first();
        $ticketData     = self::TicketData($localCompleted->id, $data);

        $ticketTransaction = TicketTransaction::where('ticket_id', '=', $ticketId)
                                                ->where('active', '=', 1)
                                                ->update(array('active' => 0));

        $user = new User();
        $editingManager  = $user->findUserByRoleName('Editing Manager');
        // Assgining Editing Manager
        $ticketData['editingmanager_id'] = $editingManager[0]->id;
        $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);
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
        $localCompleted = Stage::where('stage_name', '(Central) Editing Completed')->first();
        $ticketData     = self::TicketData($localCompleted->id, $data);

        $ticketTransaction = TicketTransaction::where('ticket_id', '=', $ticketId)
                                                ->where('active', '=', 1)
                                                ->update(array('active' => 0));
        // Assgining Editing Manager
        if ($ticketData['editingmanager_id']) {
            $ticketData['assigned_to'] = $ticketData['editingmanager_id'];
            $editorTransaction          = TicketTransaction::updateTicket($ticketData);
        }
        // Assgining Editing Team Lead
        if ($ticketData['editingteamlead_id']) {
            $ticketData['assigned_to'] = $ticketData['editingteamlead_id'];
            $editorTransaction         = TicketTransaction::updateTicket($ticketData);
        }
        //Assgining To cataloging Manager
        if ($ticketData['catalogingmanager_id']) {
            $ticketData['assigned_to']  = $ticketData['catalogingmanager_id'];
            $leadTransaction           = TicketTransaction::updateTicket($ticketData);
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
        $localCompleted     = Stage::where('stage_name', '(Local) MIF Completed')->first();
        $ticketData         = self::TicketData($localCompleted->id, $data);

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
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));
        $localCompleted     = Stage::where('stage_name', '(Local) MIF Completed')->first();
        $ticketData         = self::TicketData($localCompleted->id, $data);
        // 6 = Raw Images QC Failed
        if ($data['pending_reason_id'] == 6) {
            $ticketData['group_id']  = 1;
            $ticketData['priority']  = 3;
            $stageNotAssigned  = Stage::where('stage_name', '(Local) Associates Not Assigned')->first();
            $ticketData['stage_id']     = $stageNotAssigned->id;
        }
        // 5 = Editing Images QC Failed
        if ($data['pending_reason_id'] == 5) {
            $ticketData['priority']  = 3;
            $stageMif  = Stage::where('stage_name', '(Local) MIF Completed')->first();
            $ticketData['stage_id']     = $stageMif->id;
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
    public static function updateEditingComplete($ticketTransactionId, $ticketId, $data)
    {
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                        ->where('active', '=', 1)
                                        ->update(array('active' => 0));
        // 6 = Raw Images QC Failed
        if ($data['pending_reason_id'] == 6) {
            $editingStage     = Stage::where('stage_name', '(Local) MIF Completed')->first();
            $data['priority'] = 3;
        } else {
            $editingStage = Stage::where('stage_name', '(Central) Editing Completed')->first();
        }

        $ticketData       = self::TicketData($editingStage->id, $data);
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
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));
        $editingCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
        $ticketData         = self::TicketData($editingCompleted->id, $data);

        $user = new User();
        $catalogueManager          = $user->findUserByRoleName('Catalogue Manager');
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
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                        ->where('active', '=', 1)
                                        ->update(array('active' => 0));
        $editingCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
        $ticketData         = self::TicketData($editingCompleted->id, $data);

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
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                    ->where('active', '=', 1)
                                    ->update(array('active' => 0));


        // if ($data['stage_id'] == 7) {
        //     $catalogStageId     = Stage::where('stage_name', '(Central) QC Completed')->first();
        // }

        // if ($data['stage_id'] == 8) {
        //     $catalogStageId     = Stage::where('stage_name', '(Central) ASIN Created')->first();
        // } NOTE: from CTL form
        $catalogStageId = $data['stage_id'];

        // 7 =Cataloging MIF QC Failed
        if ($data['pending_reason_id'] == 7) {
            $catalogStage = Stage::where('stage_name', '(Central) Editing Completed')->first();
            $catalogStageId = $catalogStage->id;
            $data['group_id']  = 3; // Cataloging
            $data['priority']  = 3; // Higher
            $data['status_id']  = 1;  // Open
        }
        // 8 =Flat File MIF QC Failed
        if ($data['pending_reason_id'] == 8) {
            $catalogStage = Stage::where('stage_name', '(Local) Associates Not Assigned')->first();
            $catalogStageId = $catalogStage->id;
            $data['group_id']  = 1; // Local
            $data['priority']  = 3; // Higher
            $data['status_id']  = 1; // Open
        }

        $ticketData         = self::TicketData($catalogStageId, $data);

        $ticketData['catalogingteamlead_id'] = Auth::user()->id;
        if ($ticketData['cataloguer_id']) {
            // Assgining cataloguer
            $ticketData['assigned_to'] = $ticketData['cataloguer_id'];
            $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);
        }
        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $ticketData['localteamlead_id'];
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
        $seller             = SellerRequest::find($ticketId)->toArray();
        // Deactivate old tickect transaction
        $ticketTransaction  = TicketTransaction::where('ticket_id', '=', $ticketId)
                                        ->where('active', '=', 1)
                                        ->update(array('active' => 0));
        $catalogCompleted   = Stage::where('stage_name', '(Central) Editing Completed')->first();
        // Default visible status =1
        $status = 1;

        // 8 == Flat File MIF QC Failed (Rejection)
        if ($data['pending_reason_id'] == 8) {
            $catalogCompleted = Stage::where('stage_name', '(Central) Editing Completed')->first();
            $data['priority']  = 3; // Urgent
            $data['group_id']  = 1; // Local
        } elseif ($data['stage_id'] == 6) {
            $catalogCompleted   = Stage::where('stage_name', '(Central) Cataloging Completed')->first();
            $data['status_id']  = 3; // Resolved
        } elseif ($data['stage_id'] == 8) {
            $data['status_id']  = 4; // Closed
            $status = 0; // Ticket Visible Status false
            $catalogCompleted   = Stage::where('stage_name', '(Central) ASIN Created')->first();
        }
        // Assign submitted data with old ticket data
        $ticketData  = self::TicketData($catalogCompleted->id, $data , $status);

        $ticketData['cataloguer_id'] =  Auth::user()->id;
        // Assgining to Local Team Lead
        $ticketData['assigned_to']          = $ticketData['localteamlead_id'];
        $leadTransaction                    = TicketTransaction::updateTicket($ticketData);

        if ($data['pending_reason_id'] != 8) {
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
            // Assgining cataloguer
            $ticketData['assigned_to'] = $ticketData['cataloguer_id'];
            $cataloguerTransaction     = TicketTransaction::updateTicket($ticketData);
        }

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
        $ticketTransaction = \TicketTransaction::find($data['transaction_id'])->toArray();
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
        // Local Team Lead
        $ticketData['localteamlead_id'] = (isset($data['localteamlead_id'])) ? $data['localteamlead_id'] : $ticketTransaction['localteamlead_id'];
        // Photgrapher
        $ticketData['photographer_id']      = (isset($data['photographer_id'])) ? $data['photographer_id'] : $ticketTransaction['photographer_id'];
        $ticketData['photoshoot_location']  = (isset($data['photoshoot_location'])) ? $data['photoshoot_location'] : $ticketTransaction['photoshoot_location'];
        $ticketData['photoshoot_date']      = (isset($data['photoshoot_date'])) ? $data['photoshoot_date'] : $ticketTransaction['photoshoot_date'];
        // var_dump($ticketTransaction);
        // Service Assoicates
        $ticketData['mif_id'] = (isset($data['mif_id'])) ? $data['mif_id'] : $ticketTransaction['mif_id'];
        // Editing Manager
        $ticketData['editingmanager_id'] = (isset($data['editingmanager_id'])) ? $data['editingmanager_id'] : $ticketTransaction['editingmanager_id'];
        // Editing Team Lead
        $ticketData['editingteamlead_id'] = (isset($data['editingteamlead_id'])) ? $data['editingteamlead_id'] : $ticketTransaction['editingteamlead_id'];
        // Editor
        $ticketData['editor_id'] = (isset($data['editor_id'])) ? $data['editor_id'] : $ticketTransaction['editor_id'];
        // Cataloging Manager
        $ticketData['catalogingmanager_id'] = (isset($data['catalogingmanager_id'])) ? $data['catalogingmanager_id'] : $ticketTransaction['catalogingmanager_id'];
        // Cataloging Team Lead
        $ticketData['catalogingteamlead_id'] = (isset($data['catalogingteamlead_id'])) ? $data['catalogingteamlead_id'] : $ticketTransaction['catalogingteamlead_id'];
        // Cataloger
        $ticketData['cataloguer_id'] = (isset($data['cataloguer_id'])) ? $data['cataloguer_id'] : $ticketTransaction['cataloguer_id'];

        $ticketData['sa_sku']       = (isset($data['sa_sku']))?$data['sa_sku']:$ticketTransaction['sa_sku'];
        $ticketData['sa_variation'] = (isset($data['sa_variation']))?$data['sa_variation']:$ticketTransaction['sa_variation'];
        $ticketData['total_sku']    = (isset($data['total_sku'])) ? $data['total_sku'] : $ticketTransaction['total_sku'];
        $ticketData['total_images'] = (isset($data['total_images'])) ? $data['total_images'] : $ticketTransaction['total_images'];
        $ticketData['notes']        = (isset($data['comment'])) ? $data['comment'] : $ticketTransaction['notes'];
        $ticketData['created_by']   =  Auth::user()->id;
        if (isset($data['pending_reason_id'])) {
            $ticketData['pending_reason_id'] = ($data['pending_reason_id']) ? $data['pending_reason_id'] : null;
        }
        // var_dump($ticketData);exit;
        return $ticketData;
    }
}
