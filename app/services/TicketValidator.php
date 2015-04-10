<?php
namespace App\Services;
use Illuminate\Validation\Validator as IlluminateValidator;

class TicketValidator  extends IlluminateValidator {
    public $commonRules = ['total_images' => 'Integer',
                          'total_sku' => 'Integer',
                          'sa_sku' => 'Integer',
                          'sa_variation' => 'Integer'
                        ];
    public $_custom_messages = array (
        "service_associate_required" => "Service Associates is required .",
        "service_associate_already_assigned" => "Service Associates already assgined.",
        "photographer_required" => "Photographer is required.",
        "photographer_already_assigned" => "Photographer already assgined ",
        "not_authorsied_catalog_move" => "You did not have permission to move to cataloging.",
        "stage_sa_assign" => "Change stage to assigned",
        "pending_reason_cant_move" => "Pending reason Ticket move not allowed",
        "editing_cant_move" => "Only MIF Complete stage can be moved to Editing Group",
    );

    public function __construct()
    {
        // parent::__construct( $translator, $data, $rules, $messages, $customAttributes );
    }
    /**
     * Local Team Lead Work flow Validation
     */
    public function localLeadFlow($data) {

      $ticketTransaction = \TicketTransaction::find($data['transaction_id'])->toArray();

      if($data['mif_id'] == 0) {
          throw new \Exception($this->_custom_messages['service_associate_required']);
      }
      //Check for Image Not available
      if($data['image_available'] == 1 && !$data['photographer_id']) {
          throw new \Exception($this->_custom_messages['photographer_required']);
      }
      // Check already in photographer or MIF queue
      if($data['stage_id'] != '1' && !$data['pending_reason_id']) {

        if(isset($ticketTransaction['photographer_id']) &&
          ($ticketTransaction['photographer_id'] != $data['photographer_id'] &&
          !$ticketTransaction['pending_reason_id'])) {
          throw new \Exception($this->_custom_messages['photographer_already_assigned']);
        }

        if(isset($ticketTransaction['mif_id']) && ($ticketTransaction['mif_id'] != $data['mif_id'] &&
          !$ticketTransaction['pending_reason_id'])) {
          throw new \Exception($this->_custom_messages['service_associate_already_assigned']);
        }

      }
      // While pending reason ticket should not move the any queue
      if($data['stage_id'] != 1 && $data['pending_reason_id']) {
          throw new \Exception($this->_custom_messages['pending_reason_cant_move']);
      }


      $this->checkValidator($data, $this->commonRules);

      // Check photographer
      if($data['photographer_id'] && $data['image_available'] == 1) {
          $rules = [
                    'photoshoot_location' => 'required',
                    'photoshoot_date' => 'required|after:date',
                  ];
          $this->checkValidator($data, $rules);
      }
      // Check for associate Change
      if($data['photographer_id'] && $data['mif_id']) {
          if($data['stage_id'] == '1') {
              throw new \Exception($this->_custom_messages['stage_sa_assign']);
          }
      }

      // Not authorize to move cataloguing group
      if($data['group_id'] != 2 and $data['group_id']!=1) {
        throw new \Exception($this->_custom_messages['not_authorsied_catalog_move']);
      }
    }

    /**
     *  Photographer Work flow Validation
     */
    public function photographerFlow($data) {
      $rules = [    'total_images' => 'required|Integer',
                    'total_sku' => 'required|Integer',
                ];
      if(!$data['pending_reason_id']) {
         $this->checkValidator($data, $rules);
      }
    }

    /**
     *  ServicesAssociate Work flow Validation
     */
    public function servicesAssociateFlow($data) {
        if(!$data['pending_reason_id']) {
            $this->checkValidator($data, $this->commonRules);
        }
    }

    /**
     *  Editing Manager Work flow Validation
     */
    public function localLeadToEditingManagerFlow($data) {

        $ticketTransaction = \TicketTransaction::find($data['transaction_id'])->toArray();

        // While pending reason ticket should not move the any queue
        if($data['stage_id'] != 1 && $data['pending_reason_id']) {
            throw new \Exception($this->_custom_messages['pending_reason_cant_move']);
        }


        if(isset($ticketTransaction['photographer_id']) &&
          ($ticketTransaction['photographer_id'] != $data['photographer_id'] &&
          !$ticketTransaction['pending_reason_id'])) {
          throw new \Exception($this->_custom_messages['photographer_already_assigned']);
        }

        if(isset($ticketTransaction['mif_id']) && ($ticketTransaction['mif_id'] != $data['mif_id'] &&
            !$ticketTransaction['pending_reason_id'])) {
            throw new \Exception($this->_custom_messages['service_associate_already_assigned']);
        }

        if($data['image_available'] == 1) {
            $commonRules = array_merge($this->commonRules , array(
                  'photoshoot_location' => 'required',
                  'photoshoot_date' => 'required|after:date'
                ));
            if(!$data['photographer_id']) {
                throw new \Exception($this->_custom_messages['photographer_required']);
            }
        }

        if(!$data['mif_id']) {
            throw new \Exception($this->_custom_messages['service_associate_required']);
        }

      $this->checkValidator($data, $commonRules);

      if($data['stage_id'] != '5') {
        throw new \Exception($this->_custom_messages['editing_cant_move']);
      }
    }

    /**
     * checkValidator
    */
    public function checkValidator($data, $rules) {
        $validator = \Validator::make($data, $rules);
        $messages = $validator->messages();
        $errors = '';
        if ($validator->fails()) {
            foreach ($messages->all(":message") as $message) {
               $errors .= $message;
            }
            throw new \Exception($errors);
        }
    }
}