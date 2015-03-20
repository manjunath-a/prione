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

  public static function assignTicket($id, $data) {

    //  'Model Ticket';

    $associatAssigned = Stage::where('stage_name', '(Local) Associates Assigned')->first();
    $photographerCompleted = Stage::where('stage_name',
      '(Local) Photoshoot Completed / Seller Images Provided')->first();

    if($data['photographer_id']) {
      $photographerData['ticket_id'] = $data['id'];
      $photographerData['status_id'] = $data['status_id'];
      $photographerData['priority'] = $data['priority'];
      $photographerData['group_id'] = $data['group_id'];
      $photographerData['stage_id'] = $photographerCompleted->id;
      $photographerData['active'] = 1;
      $photographerData['photgrapher_id'] =  $data['photographer_id'];
      $photographerData['mif_id'] = $data['mif_id'];
      $photographerData['photosuite_date'] = $data['photosuite_date'];
      $photographerData['photosuite_location'] = $data['photosuite_location'];
      $photographerData['assigned_to'] = $data['photographer_id'];
      // var_dump($photographerData);exit;
      $photographerTransaction = TicketTransaction::updateTicket($photographerData);
    }

    $serviceAssociateData['ticket_id'] = $data['id'];
    $serviceAssociateData['status_id'] = $data['status_id'];
    $serviceAssociateData['priority'] = $data['priority'];
    $serviceAssociateData['group_id'] = $data['group_id'];
    $serviceAssociateData['stage_id'] =  $associatAssigned->id;
    $serviceAssociateData['active'] = 1;
    $serviceAssociateData['photgrapher_id'] =  $data['photographer_id'];
    $serviceAssociateData['mif_id'] = $data['mif_id'];
    $serviceAssociateData['photosuite_date'] = $data['photosuite_date'];
    $serviceAssociateData['photosuite_location'] = $data['photosuite_location'];
    $serviceAssociateData['assigned_to'] = $data['mif_id'];
    $serviceAssociateTransaction = TicketTransaction::updateTicket($serviceAssociateData);


    $leadData['ticket_id'] = $data['id'];
    $leadData['status_id'] = $data['status_id'];
    $leadData['priority'] = $data['priority'];
    $leadData['group_id'] = $data['group_id'];
    $leadData['stage_id'] = $associatAssigned->id;
    $leadData['active'] = 1;
    $leadData['photgrapher_id'] =  $data['photographer_id'];
    $leadData['mif_id'] = $data['mif_id'];
    $leadData['photosuite_date'] = $data['photosuite_date'];
    $leadData['photosuite_location'] = $data['photosuite_location'];
    $leadData['assigned_to'] = Auth::user()->id;
    $leadTransaction = TicketTransaction::updateTicket($leadData);


    // Update Team Lead
    $ticketTransaction = TicketTransaction::find($id);
    $ticketTransaction->active = 0;
    $ticketTransaction->save();

    return $leadTransaction->id;
  }
}
