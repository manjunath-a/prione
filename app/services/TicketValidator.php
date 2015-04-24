<?php

namespace app\services;

use Illuminate\Validation\Validator as IlluminateValidator;

class TicketValidator  extends IlluminateValidator
{
    public $commonRules = ['total_images' => 'Integer',
                          'total_sku' => 'Integer',
                          'sa_sku' => 'Integer',
                          'sa_variation' => 'Integer',
                        ];

    public $_custom_messages = array(
        'service_associate_required' => 'Service Associates is required .',
        'service_associate_already_assigned' => 'Service Associates already assgined.',
        'photographer_required' => 'Photographer is required.',
        'photoshoot_required' => 'Photoshoot need to be completed before Service Associates Complete',
        'photographer_already_assigned' => 'Photographer already assgined ',
        'emg_required' => 'Editing Manager required',
        'etl_required' => 'Editing Team Lead is required',
        'editor_required' => 'Editior is required',
        'ctl_required' => 'Cataloging Team Lead is required',
        'cataloguer_required' => 'Cataloger is required',
        'not_authorsied_catalog_move' => 'You did not have permission to move to cataloging.',
        'stage_sa_assign' => 'Change stage to assigned',
        'stage_required' => 'Stage is required',
        'pending_reason_cant_move' => 'Pending reason Ticket move not allowed',
        'editing_cant_move' => 'Only MIF Complete stage can be moved to Editing Group',
        'cataloging_cant_move' => 'Not allowed to move Cataloging Group',
        'asin_creation_not_allowed' => 'ASIN not allowed unti QC Completed',
        'not_authorsied_edit' => 'Edit option is not available for other Group Ticket',
    );

    public function __construct()
    {
        // parent::__construct( $translator, $data, $rules, $messages, $customAttributes );
    }

    /**
     * Local Team Lead Work flow Validation.
     */
    public function localLeadFlow($data)
    {
        $ticketTransaction = \TicketTransaction::find($data['transaction_id'])->toArray();

        if (!$data['pending_reason_id']) {
            if ($data['mif_id'] == 0) {
                throw new \Exception($this->_custom_messages['service_associate_required']);
            }
            //Check for Image Not available
            if ($data['image_available'] == 1 && !$data['photographer_id']) {
                throw new \Exception($this->_custom_messages['photographer_required']);
            }

            // Check already in photographer or MIF queue
            if ($ticketTransaction['photographer_id'] != $data['photographer_id'] &&
              ($data['stage_id'] == '3' or  $data['stage_id'] == '4')) {
                throw new \Exception($this->_custom_messages['photographer_already_assigned']);
            }

            if ($ticketTransaction['mif_id'] != $data['mif_id'] && $data['stage_id'] == '4') {
                throw new \Exception($this->_custom_messages['service_associate_already_assigned']);
            }
            $this->checkValidator($data, $this->commonRules);

            // Check photographer
            if ($data['photographer_id'] && $data['image_available'] == 1) {
                $rules = [
                          'photoshoot_location' => 'required',
                          'photoshoot_date' => 'required',
                        ];
                $this->checkValidator($data, $rules);
            }
            // Check for associate Change
            if ($data['photographer_id'] && $data['mif_id']) {
                if ($data['stage_id'] == '1') {
                    throw new \Exception($this->_custom_messages['stage_sa_assign']);
                }
            }
        }
        // While pending reason ticket should not move the any queue
        if ($data['stage_id'] != 1 && $data['pending_reason_id']) {
            throw new \Exception($this->_custom_messages['pending_reason_cant_move']);
        }
        // Not authorize to move cataloguing group
        if ($data['group_id'] != 2 and $data['group_id'] != 1) {
            throw new \Exception($this->_custom_messages['not_authorsied_catalog_move']);
        }
        if (!$data['stage_id']) {
            throw new \Exception($this->_custom_messages['stage_required']);
        }
    }

    /**
     *  Photographer Work flow Validation.
     */
    public function photographerFlow($data)
    {
        $rules = [
                'total_images' => 'required|Integer',
                'total_sku' => 'required|Integer',
                  ];
        if (!$data['pending_reason_id']) {
            $this->checkValidator($data, $rules);
        }
    }

    /**
     *  ServicesAssociate Work flow Validation.
     */
    public function servicesAssociateFlow($data)
    {
        // Image available and stage should be photoshoot complete
        if ($data['image_available'] == 1 && $data['stage_id'] != 3) {
            throw new \Exception($this->_custom_messages['photoshoot_required']);
        }

        if ($data['image_available'] == 2 && ($data['stage_id'] != 3 and $data['stage_id'] != 9)) {
            throw new \Exception($this->_custom_messages['photoshoot_required']);
        }
        $this->checkValidator($data, $this->commonRules);
    }

    /**
     *  Editing Manager Work flow Validation.
     */
    public function localLeadToEditingManagerFlow($data)
    {
        $ticketTransaction = \TicketTransaction::find($data['transaction_id'])->toArray();

        // While pending reason ticket should not move the any queue
        if ($data['stage_id'] != 1 && $data['pending_reason_id']) {
            throw new \Exception($this->_custom_messages['pending_reason_cant_move']);
        }

        if (isset($ticketTransaction['photographer_id']) &&
          ($ticketTransaction['photographer_id'] != $data['photographer_id'] &&
          !$ticketTransaction['pending_reason_id'])) {
            throw new \Exception($this->_custom_messages['photographer_already_assigned']);
        }

        if (isset($ticketTransaction['mif_id']) && ($ticketTransaction['mif_id'] != $data['mif_id'] &&
            !$ticketTransaction['pending_reason_id'])) {
            throw new \Exception($this->_custom_messages['service_associate_already_assigned']);
        }

        if ($data['image_available'] == 1) {
            $commonRules = array_merge($this->commonRules, array(
                  'photoshoot_location' => 'required',
                  'photoshoot_date' => 'required',
                ));
            if (!$data['photographer_id']) {
                throw new \Exception($this->_custom_messages['photographer_required']);
            }
        }

        if (!$data['mif_id']) {
            throw new \Exception($this->_custom_messages['service_associate_required']);
        }

        $this->checkValidator($data, $this->commonRules);

        if ($data['stage_id'] != '4') {
            throw new \Exception($this->_custom_messages['editing_cant_move']);
        }

        if (!$data['stage_id']) {
            throw new \Exception($this->_custom_messages['stage_required']);
        }
    }

    /**
     *  Editing Manager Work flow Validation.
     */
    public function localLeadToCatalogingManagerFlow($data)
    {
        $ticketTransaction = \TicketTransaction::find($data['transaction_id'])->toArray();

        if (!$data['editingmanager_id']) {
            throw new \Exception($this->_custom_messages['emg_required']);
        }

        if (!$data['editingteamlead_id']) {
            throw new \Exception($this->_custom_messages['etl_required']);
        }

        // While pending reason ticket should not move the any queue
        if (!$data['catalogingmanager_id']) {
            throw new \Exception($this->_custom_messages['cataloging_cant_move']);
        }
        if (!$data['stage_id']) {
            throw new \Exception($this->_custom_messages['stage_required']);
        }
    }

    /**
     * editingManagerFlow.
     */
    public function editingManagerFlow($data)
    {

        //Check for Image Not available
        if (!$data['editingteamlead_id']) {
            throw new \Exception($this->_custom_messages['etl_required']);
        }
        // Not authorize to move cataloguing group
        if ($data['group_id'] != 2) {
            throw new \Exception($this->_custom_messages['not_authorsied_edit']);
        }
    }

    /**
     * editingTeamLeadFlow.
     */
    public function editingTeamLeadFlow($data)
    {
        //Check for Image Not available
        if (!$data['editor_id'] && !$data['pending_reason_id']) {
            throw new \Exception($this->_custom_messages['editor_required']);
        }

        $editingStages = array(6,7,8,9);
        // Not authorize to move other group unles stagerelated to Editing
        if ($data['group_id'] != 2 && !in_array($data['cataloguer'], $editingStages)) {
            throw new \Exception($this->_custom_messages['not_authorsied_edit']);
        }

        if (!$data['stage_id']) {
            throw new \Exception($this->_custom_messages['stage_required']);
        }
    }

    /**
     * Editor Flow.
     */
    public function editorFlow($data)
    {
        $rules = [
                  'total_images' => 'Integer',
                  'total_sku' => 'Integer',
                ];
        if (!$data['pending_reason_id']) {
            $this->checkValidator($data, $rules);
        }
        // Not authorize to move cataloguing group
        if ($data['group_id'] != 2) {
            throw new \Exception($this->_custom_messages['not_authorsied_edit']);
        }
    }

    /**
     * Cataloging Manager Flow.
     */
    public function catalogingManagerFlow($data)
    {

        //Check for Image Not available
        if (!$data['catalogingteamlead_id']) {
            throw new \Exception($this->_custom_messages['ctl_required']);
        }
        // Not authorize to move cataloguing group
        if ($data['group_id'] != 3 && !$data['pending_reason_id'] ) {
            throw new \Exception($this->_custom_messages['not_authorsied_edit']);
        }
    }

    /**
     * catalogingTeamLeadFlow.
     */
    public function catalogingTeamLeadFlow($data)
    {
        $rules = [
                  'sa_variation' => 'Integer',
                  'sa_sku' => 'Integer',
                ];
        $this->checkValidator($data, $rules);
        //Check for Image Not available
        if (!$data['cataloguer_id'] && !$data['pending_reason_id']) {
            throw new \Exception($this->_custom_messages['cataloguer_required']);
        }

        $catalogingStages = array(6,7,8,9);
        // Not authorize to move cataloguing group unles stagerelated to Cataloging
        if ($data['group_id'] != 3 && !in_array($data['cataloguer_id'], $catalogingStages)) {
            throw new \Exception($this->_custom_messages['not_authorsied_edit']);
        }

        if (!$data['stage_id']) {
            throw new \Exception($this->_custom_messages['stage_required']);
        }
    }

    /**
     * catalogerFlow.
     */
    public function catalogerFlow($data)
    {
        $rules = [
                  'sa_variation' => 'Integer',
                  'sa_sku' => 'Integer',
                ];
        $ticketTransaction = \TicketTransaction::find($data['transaction_id'])->toArray();
        // MOve allowed from QC commpleted -> ASIN created
        if ($ticketTransaction['stage_id'] != 7 && $data['stage_id'] == 8) {
            throw new \Exception($this->_custom_messages['asin_creation_not_allowed']);
        }

        $this->checkValidator($data, $rules);
        // Not authorize to move cataloguing group
        if ($data['group_id'] != 3) {
            throw new \Exception($this->_custom_messages['not_authorsied_edit']);
        }
        if (!$data['stage_id']) {
            throw new \Exception($this->_custom_messages['stage_required']);
        }
    }

    /**
     * checkValidator.
     */
    public function checkValidator($data, $rules)
    {
        $validator = \Validator::make($data, $rules);
        $messages = $validator->messages();
        $errors = '';
        if ($validator->fails()) {
            foreach ($messages->all(':message') as $message) {
                $errors .= $message;
            }
            throw new \Exception($errors);
        }
    }
}
