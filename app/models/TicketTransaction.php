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

  /**
   *
   * @param integer $ticketId
   * @return Array users
   **/
  public static function getAssignedUsersByTicketId($ticketId)
  {
      $ticketUsers = DB::select('SELECT tt.ticket_id,
                                ltl.username as LocalTeamLead,  ph.username as Photographer, sa.username as ServiceAssociate,
                                em.username as EditingManager,  etl.username as EditingTeamLead, ed.username as Editor,
                                cm.username as CatalogingManager,  ctl.username as CatalogingTeamLead, cat.username as Cataloger,
                                ro.name as RejectedBy
                                FROM dcst_ticket_transaction as tt
                                JOIN dcst_ticket as t ON tt.ticket_id=t.id
                                LEFT JOIN dcst_users as ltl  ON tt.localteamlead_id = ltl.id
                                LEFT JOIN dcst_users as ph   ON tt.photographer_id = ph.id
                                LEFT JOIN dcst_users as sa   ON tt.mif_id = sa.id
                                LEFT JOIN dcst_users as em   ON tt.editingmanager_id = em.id
                                LEFT JOIN dcst_users as etl  ON tt.editingteamlead_id = etl.id
                                LEFT JOIN dcst_users as ed   ON tt.editor_id = ed.id
                                LEFT JOIN dcst_users as cm   ON tt.catalogingmanager_id = cm.id
                                LEFT JOIN dcst_users as ctl  ON tt.catalogingteamlead_id = ctl.id
                                LEFT JOIN dcst_users as cat  ON tt.cataloguer_id = cat.id
                                LEFT JOIN dcst_roles as ro   ON tt.rejected_role = ro.id
                                WHERE tt.ticket_id = '.$ticketId.' ORDER BY tt.id DESC  LIMIT 0,1'
                                );
      return $ticketUsers;
  }
}
