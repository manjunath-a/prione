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

    //  'Model Ticket';
    if($data['mif_id'] == 0) {
      throw new Exception("Service Associates is reuired ");
    }
    $associatAssigned = Stage::where('stage_name', '(Local) Associates Assigned')->first();

    $ticketData['ticket_id'] = $data['ticket_id'];
    $ticketData['status_id'] = $data['status_id'];
    $ticketData['priority'] = $data['priority'];
    $ticketData['group_id'] = $data['group_id'];
    $ticketData['stage_id'] = $associatAssigned->id;
    $ticketData['active'] = 1;

    $ticketData['mif_id'] = $data['mif_id'];
    $ticketData['photosuite_date'] = $data['photosuite_date'];
    $ticketData['photosuite_location'] = $data['photosuite_location'];
    $ticketData['notes'] = $data['comment'];

    if($data['total_sku'])
      $ticketData['total_sku'] = $data['total_sku'];
    if($data['total_images'])
      $ticketData['total_images'] = $data['total_images'];
    if($data['sa_sku'])
      $ticketData['sa_sku'] = $data['sa_sku'];
    if($data['sa_variation'])
      $ticketData['sa_variation'] = $data['sa_variation'];

    if($data['photographer_id']) {
      $ticketData['photographer_id'] =  $data['photographer_id'];
      $ticketData['assigned_to'] = $data['photographer_id'];
      $photographerTransaction = TicketTransaction::updateTicket($ticketData);
    }

    $ticketData['assigned_to'] = $data['mif_id'];
    $serviceAssociateTransaction = TicketTransaction::updateTicket($ticketData);

    $ticketData['assigned_to'] = Auth::user()->id;
    $leadTransaction = TicketTransaction::updateTicket($ticketData);

    // Update Team Lead
    $ticketTransaction = TicketTransaction::find($ticketTransactionId);
    $ticketTransaction->active = 0;
    $ticketTransaction->save();

    return $leadTransaction->id;
  }
}
