<?php
/**
 * Created by Compassites.
 * User: Arasu
 * Date: 20/03/15
 * Time: 8:43 PM
 */


use Illuminate\Database\Eloquent\Model;

class PhotographerRepository extends EloquentRepositoryAbstract  {

    public function __construct() {

        list($user,$redirect) = User::checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        $this->Database = DB::table('ticket_transaction')
            ->join('ticket', 'ticket.id', '=', 'ticket_transaction.ticket_id')
            ->join('status', 'status.id', '=', 'ticket_transaction.status_id')
            ->join('stage', 'stage.id', '=', 'ticket_transaction.stage_id')
            ->join('group', 'group.id', '=', 'ticket_transaction.group_id')
            ->join('seller_request', 'seller_request.id', '=', 'ticket.request_id')
            ->join('users', 'seller_request.merchant_city_id', '=', 'users.city_id')
            ->where('ticket_transaction.assigned_to', $user->id)
            ->where('ticket_transaction.active', 1)
            ->select('ticket_transaction.id as id', 'ticket.created_at as created_at', 'ticket_transaction.priority',
                'ticket_transaction.group_id', 'ticket_transaction.stage_id', 'ticket_transaction.status_id', 'ticket_transaction.pending_reason',
                'seller_request.seller_name as seller_name', 'seller_request.email',
                'seller_request.contact_number','seller_request.poc_name',
                'seller_request.poc_email', 'seller_request.poc_number','seller_request.total_sku',
                'seller_request.image_available', 'seller_request.comment',
                'seller_request.id as seller_request_id', 'ticket.id as ticket_id')
            ->groupBy('ticket_transaction.id');


        $this->visibleColumns = array('ticket_transaction.id as id', 'ticket.created_at as created_at', 'ticket_transaction.priority',
            'ticket_transaction.group_id', 'ticket_transaction.stage_id', 'ticket_transaction.status_id', 'ticket_transaction.pending_reason',
            'seller_request.seller_name as seller_name', 'seller_request.email',
            'seller_request.contact_number','seller_request.poc_name',
            'seller_request.poc_email', 'seller_request.poc_number','seller_request.total_sku',
            'seller_request.image_available', 'seller_request.comment',
            'seller_request.id as seller_request_id', 'ticket.id as ticket_id');

        $this->orderBy = array(array('id', 'asc'));
    }
}