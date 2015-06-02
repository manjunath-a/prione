<?php
/**
 * Created by Compassites.
 * User: Arasu
 * Date: 12/03/15
 * Time: 8:43 PM
 */

use Illuminate\Database\Eloquent\Model;
use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;

class DashboardRepository extends EloquentRepositoryAbstract  {

    protected $userId;

    protected $status;

    public function __construct() {

        list($user,$redirect) = User::checkAuthAndRedirect('user');
        if($redirect) return $redirect;
        $status = 1;
        $this->userId = $user->id;
        $this->status = $status;
        $this->getDashboardRequestData($user->id, $status);
    }

    public function getTotalNumberOfRows(array $filters = array())
    {
        $count =  DB::table('ticket_transaction')
            ->join('ticket', 'ticket.id', '=', 'ticket_transaction.ticket_id')
            ->join('status', 'status.id', '=', 'ticket_transaction.status_id')
            ->join('stage', 'stage.id', '=', 'ticket_transaction.stage_id')
            ->join('group', 'group.id', '=', 'ticket_transaction.group_id')
            ->join('seller_request', 'seller_request.id', '=', 'ticket.request_id')
            ->join('category', 'category.id', '=', 'seller_request.category_id')
            ->join('users', 'seller_request.merchant_city_id', '=', 'users.city_id')
            ->where('ticket_transaction.assigned_to', $this->userId)
            ->where('ticket_transaction.active', $this->status)
            ->select('ticket_transaction.ticket_id')
            ->groupBy('ticket_transaction.ticket_id')->get();

        return count($count);
    }

    public function getDashboardRequestData($userId, $status) {

        // return  DB::table('ticket_transaction')
        $this->Database = DB::table('ticket_transaction')
                            ->join('ticket', 'ticket.id', '=', 'ticket_transaction.ticket_id')
                            ->join('status', 'status.id', '=', 'ticket_transaction.status_id')
                            ->join('stage', 'stage.id', '=', 'ticket_transaction.stage_id')
                            ->join('group', 'group.id', '=', 'ticket_transaction.group_id')
                            ->join('seller_request', 'seller_request.id', '=', 'ticket.request_id')
                            ->join('category', 'category.id', '=', 'seller_request.category_id')
                            ->join('users', 'seller_request.merchant_city_id', '=', 'users.city_id')
                            ->where('ticket_transaction.assigned_to', $userId)
                            ->where('ticket_transaction.active', $status)
                            ->select('ticket_transaction.id as id',
                            'ticket_transaction.created_at as created_at',
                            'seller_request.created_at as request_created',
            'ticket_transaction.priority', 'ticket_transaction.group_id', 'ticket_transaction.stage_id',
            'ticket_transaction.status_id', 'ticket_transaction.pending_reason_id', 'ticket_transaction.notes as comment',
            'ticket_transaction.created_at as assigned_date','seller_request.requester_name as requester_name',
            'seller_request.email', 'seller_request.contact_number', 'category.category_name as category_name',
            'seller_request.merchant_name as merchant_name', 'seller_request.poc_name',
            'seller_request.poc_email', 'seller_request.poc_number as merchant_phone','seller_request.total_sku',
            'seller_request.image_available as image_available', 'seller_request.comment as seller_comment', 'ticket.s3_folder as s3_folder',
            'seller_request.id as seller_request_id', 'ticket.id as ticket_id',
            'ticket_transaction.photoshoot_date','ticket_transaction.photoshoot_location',
            'ticket_transaction.localteamlead_id','ticket_transaction.photographer_id','ticket_transaction.mif_id',
            'ticket_transaction.editingmanager_id', 'ticket_transaction.editingteamlead_id as editingteamlead_id',
            'ticket_transaction.editor_id as editor_id',
            'ticket_transaction.catalogingmanager_id', 'ticket_transaction.catalogingteamlead_id as catalogingteamlead_id','ticket_transaction.cataloguer_id as cataloguer',
            'ticket_transaction.sa_variation', 'ticket_transaction.sa_sku',
            'ticket_transaction.total_sku', 'ticket_transaction.total_images',
             'ticket_transaction.id as transaction_id')
                            ->groupBy('ticket_transaction.ticket_id');
        // $queries = DB::getQueryLog(); $last_query = end($queries);  var_dump($last_query); exit;

        $this->visibleColumns = array('ticket_transaction.id as id', 'ticket_transaction.created_at as created_at',
            'ticket_transaction.priority', 'ticket_transaction.group_id', 'ticket_transaction.stage_id', 'ticket_transaction.notes as comment',
            'ticket_transaction.status_id', 'ticket_transaction.pending_reason_id', 'category.category_name as category_name',
            'seller_request.requester_name as requester_name', 'seller_request.email',
            'seller_request.contact_number','seller_request.poc_name', 'ticket_transaction.created_at as assigned_date',
            'seller_request.poc_email', 'seller_request.poc_number','seller_request.total_sku',
            'seller_request.image_available as image_available', 'seller_request.comment as seller_comment', 'ticket.s3_folder as s3_folder',
            'ticket_transaction.photoshoot_date','ticket_transaction.photoshoot_location',
            'ticket_transaction.localteamlead_id','ticket_transaction.photographer_id','ticket_transaction.mif_id',
            'ticket_transaction.sa_variation', 'ticket_transaction.sa_sku',
            'ticket_transaction.total_sku', 'ticket_transaction.total_images',
            'seller_request.id as seller_request_id', 'ticket.id as ticket_id',
            'ticket_transaction.id as transaction_id');

        $this->orderBy = array(array('id', 'asc'));
    }
}