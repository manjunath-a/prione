<?php

use Illuminate\Database\Eloquent\Model;

class TicketTransaction extends Eloquent  {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'ticket_transaction';

  /**
     * Add your validation rules here
     *
     * @var string
     */
    public static $rules = [
        'ticket_id' => 'required',
        'status_id' => 'required',
        'stage_id' => 'required',
        'priority' => 'required',
        'group_id' => 'required',
    ];

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
     //var_dump($transactionData);exit;
    return TicketTransaction::create($transactionData);
  }

  public function transactionByTicketId($ticketId)
  {
      return TicketTransaction::where('ticket_id','=', $ticketId)->where('active','=', 1)->first()->toArray();
  }

}
