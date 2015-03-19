<?php


class TicketTransaction extends Eloquent  {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'ticket_transaction';

  /**
   * Primary key for the table.
   *
   * @var string
   */
  protected $primaryKey = 'id';
  /**
   * @var Array guarded
   */
  protected $guarded = array('id');

  public function ticket() {
      return $this->belongsTo('Ticket');
  }

  public function request() {
      return $this->belongsTo('SellerRequest');
  }

  public function group() {
      return $this->belongsTo('Group');
  }

  public function stage() {
      return $this->belongsTo('Stage');
  }

  public function status() {
      return $this->belongsTo('Status');
  }

  public static function updateTicket($transactionData) {
    // echo 'Model Ticket';
    // var_dump($data);exit;
    return TicketTransaction::create($transactionData);
  }

}
