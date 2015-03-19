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
    // echo 'Model Ticket';
    // var_dump($data);exit;
    if($data['photographer_id']) {
      $transactionData['ticket_id'] = $data['id'];
      $transactionData['status_id'] = $data['status_id'];
      $transactionData['priority'] = $data['priority'];
      $transactionData['group_id'] = $data['group_id'];
      $transactionData['stage_id'] = $data['stage_id'];
      $transactionData['assigned_to'] = $data['photographer_id'];
      $ticketTransaction = TicketTransaction::updateTicket($transactionData);
    }

    $transData['ticket_id'] = $data['id'];
    $transData['status_id'] = $data['status_id'];
    $transData['priority'] = $data['priority'];
    $transData['group_id'] = $data['group_id'];
    $transData['stage_id'] = $data['stage_id'];
    $transData['assigned_to'] = $data['mif_id'];
    $ticketTransaction = TicketTransaction::updateTicket($transData);
    return $ticketTransaction->id;
  }
}
