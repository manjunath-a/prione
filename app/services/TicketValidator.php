<?php
namespace App\Services;
use Illuminate\Validation\Validator as IlluminateValidator;

class TicketValidator  extends IlluminateValidator {

    public $_custom_messages = array (
        "service_associate_required" => "Service Associates is required .",
        "photographer_required" => "Photographer is required.",
        "not_authorsied_catalog_move" => "You did not have permission to move to cataloging.",
        "stage_sa_assign" => "Change stage to assigned",
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

      if($data['mif_id'] == 0) {
          throw new \Exception($this->_custom_messages['service_associate_required']);
      }
      //Check for Image Not available
      if($data['image_available'] == 1 && !$data['photographer_id']) {
          throw new \Exception($this->_custom_messages['photographer_required']);
      }
      $commonRules = ['total_images' => 'Integer',
                'total_sku' => 'Integer',
              ];
      $this->checkValidator($data, $commonRules);

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

      // Not Authorize to move cataloguing
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
      $rules = ['total_images' => 'Integer',
                'total_sku' => 'Integer',
                'sa_sku' => 'Integer',
                'sa_variation' => 'Integer',
              ];
      if(!$data['pending_reason_id']) {
          $this->checkValidator($data, $rules);
      }
    }



    /**
     *  Editing Manager Work flow Validation
     */
    public function editingManagerFlow($data) {

      $commonRules = ['total_images' => 'Integer',
                'total_sku' => 'Integer',
                'sa_sku' => 'Integer',
                'sa_variation' => 'Integer',
              ];
      if($data['image_available'] == 1) {
          $commonRules = array_merge($commonRules , array(
                'photoshoot_location' => 'required',
                'photoshoot_date' => 'required|after:date'
              ));
          if(!$data['photographer_id']){
              throw new \Exception($this->_custom_messages['photographer_required']);
          }
      }

      if(!$data['mif_id']){
          throw new \Exception($this->_custom_messages['service_associate_required']);
      }

      $this->checkValidator($data, $commonRules);
      if($data['stage_id'] != '4') {
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
            var_dump($errors);exit;
            throw new \Exception($errors);
        }
    }
}