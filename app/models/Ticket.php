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

    $leadData['ticket_id'] = $data['id'];
    $leadData['status_id'] = $data['status_id'];
    $leadData['priority'] = $data['priority'];
    $leadData['group_id'] = $data['group_id'];
    $leadData['stage_id'] =  Stage::where('stage_name', '(Local) Associates Assigned');
    $leadData['active'] = 1;
    $leadData['assigned_to'] = $user->id;
    $leadTransaction = TicketTransaction::updateTicket($leadData);

    if($data['photographer_id']) {
      $photographerData['ticket_id'] = $data['id'];
      $photographerData['status_id'] = $data['status_id'];
      $photographerData['priority'] = $data['priority'];
      $photographerData['group_id'] = $data['group_id'];
      $photographerData['stage_id'] = Stage::where('stage_name', '(Local) Associates Assigned');
      $photographerData['active'] = 1;
      $photographerData['assigned_to'] = $data['photographer_id'];
      $photographerTransaction = TicketTransaction::updateTicket($photographerData);
    }

    $serviceAssociateData['ticket_id'] = $data['id'];
    $serviceAssociateData['status_id'] = $data['status_id'];
    $serviceAssociateData['priority'] = $data['priority'];
    $serviceAssociateData['group_id'] = $data['group_id'];
    $serviceAssociateData['stage_id'] =  Stage::where('stage_name', '(Local) Associates Assigned');
    $serviceAssociateData['active'] = 1;
    $serviceAssociateData['assigned_to'] = $data['mif_id'];
    $serviceAssociateTransaction = TicketTransaction::updateTicket($serviceAssociateData);

    // Update Team Lead
    $ticketTransaction = TicketTransaction::find($id);
    $ticketTransaction->active = 0;
    $ticketTransaction->save();

    return $leadTransaction->id;
  }
}
