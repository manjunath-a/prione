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


    $ticketData = Ticket::ticketData($data['stage_id'], 1, $data);
    $freshdesk = App::make('freshDesk');

    if(isset($data['photographer_id'])) {
      $ticketData['photographer_id']    =  $data['photographer_id'];
      $ticketData['photosuite_location'] =  $data['photosuite_location'];
      $ticketData['assigned_to']        = $data['photographer_id'];
      $ticketData['photosuite_date']    = $data['photosuite_date'];

      $photographerTransaction = TicketTransaction::updateTicket($ticketData);
    }
    // Assgining to Service Assiocate
    $ticketData['assigned_to']      = $data['mif_id'];
    $serviceAssociateTransaction    = TicketTransaction::updateTicket($ticketData);

    // Assgining to Local Team Lead from Session user
    $ticketData['assigned_to'] = Auth::user()->id;
    $leadTransaction = TicketTransaction::updateTicket($ticketData);

    // Update Team Lead
    $ticketTransaction          = TicketTransaction::find($ticketTransactionId);
    $ticketTransaction->active  = 0;
    $ticketTransaction->save();

    return $leadTransaction->id;
  }

  public static function updatePhotographer($ticketTransactionId, $ticketId, $data) {

      $cityId =  Auth::user()->city_id;

          // Update Team Lead
      $ticketTransaction = TicketTransaction::where('ticket_id', '=' ,$ticketId)->update(array('active' => 0));

      //  'Model Ticket';
      if($data['mif_id'] == 0) {
        throw new Exception("Service Associates is reuired ");
      }

      $photoCompleted = Stage::where('stage_name',
        '(Local) Photoshoot Completed / Seller Images Provided')->first();

      $ticketData = Ticket::TicketData($photoCompleted->id, 1, $data);
      $ticketData['photographer_id'] =  Auth::user()->id;
      $ticketData['photosuite_location'] =  $data['photosuite_location'];
      $ticketData['photosuite_date']    = $data['photosuite_date'];
      // Assgining to service Assiocate
      $ticketData['assigned_to'] = $data['mif_id'];
      $serviceAssociateTransaction = TicketTransaction::updateTicket($ticketData);
       // Assgining to Local Team Lead
      $ticketData['assigned_to'] = Ticket::findUserByRoleAndCity('Local Team Lead', $cityId);;
      $leadTransaction = TicketTransaction::updateTicket($ticketData);
       // Check for Poto Grapher assigned
      if(Auth::user()->id) {
        $ticketData['assigned_to'] = Auth::user()->id;
        $ticketData['active'] = 0;
        $photographerTransaction = TicketTransaction::updateTicket($ticketData);
      }
      return $leadTransaction->id;
  }


  public static function updateMIF($ticketTransactionId, $ticketId, $data) {
      $cityId =  Auth::user()->city_id;
      // Update Team Lead
      $ticketTransaction = TicketTransaction::where('ticket_id', '=' ,$ticketId)
        ->update(array('active' => 0));

      //  'Model Ticket';
      if($data['mif_id'] == 0) {
          throw new Exception("Service Associates is required ");
      }
      $mifCompleted = Stage::where('stage_name', '(Local) MIF Completed')->first();

      $ticketData = Ticket::ticketData($mifCompleted->id, 1, $data);
      $ticketData['photographer_id'] = $data['photographer_id'];
      $ticketData['photosuite_location'] =  $data['photosuite_location'];
      $ticketData['photosuite_date']    = $data['photosuite_date'];

      // Check for Poto Grapher assigned
      // if($data['photographer_id']) {
      //   $ticketData['assigned_to'] = $data['photographer_id'];
      //   $photographerTransaction = TicketTransaction::updateTicket($ticketData);
      // }

      // Assgining to Local Team Lead
      $ticketData['assigned_to'] = Ticket::findUserByRoleAndCity('Local Team Lead', $cityId);
      $leadTransaction = TicketTransaction::updateTicket($ticketData);
        // Assgining to Service Assiocate as current Session user
      if(Auth::user()->id) {
          $ticketData['mif_id'] =  Auth::user()->id;
          $ticketData['assigned_to'] = Auth::user()->id;
          $ticketData['active'] = 0;
          $serviceAssociateTransaction = TicketTransaction::updateTicket($ticketData);
      }
      return $leadTransaction->id;
    }

    public static function updateEditingManager($ticketTransactionId, $ticketId, $data) {
        $cityId =  Auth::user()->city_id;
        $localCompleted = Stage::where('stage_name', '(Local) MIF Completed')->first();
        // Update Team Lead
        $ticketTransaction = TicketTransaction::where('ticket_id', '=' , $ticketId)
        ->update(array('active' => 0));

        $ticketData = Ticket::ticketData($localCompleted->id, 1, $data);
        $ticketData['photographer_id'] = $data['photographer_id'];
        $ticketData['photosuite_location'] =  $data['photosuite_location'];
        $ticketData['photosuite_date']    = $data['photosuite_date'];


        // // Assgining to service Assiocate
        // $ticketData['assigned_to'] = $data['mif_id'];
        // $serviceAssociateTransaction = TicketTransaction::updateTicket($ticketData);

        // // Assgining Local Team lead
        // $ticketData['assigned_to'] = Ticket::findUserByRoleAndCity('Local Team Lead', $cityId);
        // $leadTransaction = TicketTransaction::updateTicket($ticketData);
        // Assgining Editing Manager
        $ticketData['assigned_to'] = Ticket::findUserByRoleAndCity('Editing Manager', $cityId);
        $leadTransaction           = TicketTransaction::updateTicket($ticketData);

        return $leadTransaction->id;
  }

  public static function findUserByRoleAndCity($role = 'Local Team Lead', $cityId) {
    $user = new User;
    $loalLead =  $user->findAllByRoleAndCity($role, $cityId);
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
      if( Input::has('photosuite_date') ) {
          $ticketTransactionRule['photosuite_date'] = 'After:'.Date('Y-m-d');
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
      $ticketData['notes']        = $data['comment'];
      $ticketData['mif_id']       = $data['mif_id'];
      $ticketData['sa_sku']       = $data['sa_sku'];
      $ticketData['sa_variation'] = $data['sa_variation'];
      $ticketData['total_sku'] = ($data['total_sku'])?$data['total_sku']:NULL;
      $ticketData['total_images'] = ($data['total_images'])?$data['total_images']:NULL;
      $ticketData['notes'] = ($data['comment'])?$data['comment']:NULL;
      return $ticketData;
  }
}
