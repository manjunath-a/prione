<?php
/**
 * Created by Compassites.
 * User: Arasu
 * Date: 12/03/15
 * Time: 8:43 PM
 */

use Illuminate\Database\Eloquent\Model;
use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;

class CentralDashboardRepository extends EloquentRepositoryAbstract  {

    public function __construct() {

        list($user,$redirect) = User::checkAuthAndRedirect('user');
        if($redirect) return $redirect;
        $status = 1;
        $this->getDashboardRequestData($user->id, $status);
    }

    public function getDashboardRequestData($userId, $status) {

        // select `dcst_ticket_transaction`.`id` as `id`, `dcst_ticket`.`created_at` as `created_at`,
        //  `dcst_ticket_transaction`.`priority`, `dcst_ticket_transaction`.`group_id`,
        //  `dcst_ticket_transaction`.`stage_id`, `dcst_ticket_transaction`.`status_id`,
        //  `dcst_ticket_transaction`.`pending_reason`, `dcst_seller_request`.`requester_name` as `requester_name`,
        //   `dcst_seller_request`.`email`, `dcst_seller_request`.`contact_number`,
        //    `dcst_seller_request`.`poc_name`, `dcst_seller_request`.`poc_email`,
        //    `dcst_seller_request`.`poc_number`, `dcst_seller_request`.`total_sku`,
        //    `dcst_seller_request`.`image_available`, `dcst_seller_request`.`comment`,
        //    `dcst_seller_request`.`id` as `seller_request_id`, `dcst_ticket`.`id` as `ticket_id`,
        //    `dcst_ticket_transaction`.`photosuite_date`, `dcst_ticket_transaction`.`photosuite_location`,
        //    `dcst_ticket_transaction`.`photographer_id`, `dcst_ticket_transaction`.`mif_id`,
        //    `dcst_ticket_transaction`.`sa_variation`, `dcst_ticket_transaction`.`sa_sku`,
        //    `dcst_ticket_transaction`.`total_sku`, `dcst_ticket_transaction`.`total_images`,
        //    `dcst_ticket_transaction`.`id` as `transaction_id`
        //    from `dcst_ticket_transaction`
        //    inner join `dcst_ticket` on `dcst_ticket`.`id` = `dcst_ticket_transaction`.`ticket_id`
        //    inner join `dcst_status` on `dcst_status`.`id` = `dcst_ticket_transaction`.`status_id`
        //    inner join `dcst_stage` on `dcst_stage`.`id` = `dcst_ticket_transaction`.`stage_id`
        //    inner join `dcst_group` on `dcst_group`.`id` = `dcst_ticket_transaction`.`group_id`
        //    inner join `dcst_seller_request` on `dcst_seller_request`.`id` = `dcst_ticket`.`request_id`
        //    inner join `dcst_users` on `dcst_seller_request`.`merchant_city_id` = `dcst_users`.`city_id`
        //    where `dcst_ticket_transaction`.`assigned_to` = ? and `dcst_ticket_transaction`.`active` = ?

        //  ->join('users', 'seller_request.merchant_city_id', '=', 'users.city_id')

        // return  DB::table('ticket_transaction')
           $this->Database =
                DB::table('ticket_transaction')
                            ->join('ticket', 'ticket.id', '=', 'ticket_transaction.ticket_id')
                            ->join('status', 'status.id', '=', 'ticket_transaction.status_id')
                            ->join('stage', 'stage.id', '=', 'ticket_transaction.stage_id')
                            ->join('group', 'group.id', '=', 'ticket_transaction.group_id')
                            ->join('seller_request', 'seller_request.id', '=', 'ticket.request_id')
                            ->where('ticket_transaction.assigned_to', $userId)
                            ->where('ticket_transaction.active', $status)
                            ->select('ticket_transaction.id as id', 'ticket.created_at as created_at',
            'ticket_transaction.priority', 'ticket_transaction.group_id', 'ticket_transaction.stage_id',
            'ticket_transaction.status_id', 'ticket_transaction.pending_reason',
            'seller_request.requester_name as requester_name', 'seller_request.email',
            'seller_request.contact_number','seller_request.poc_name', 'ticket_transaction.created_at as assigned_date',
            'seller_request.poc_email', 'seller_request.poc_number','seller_request.total_sku',
            'seller_request.image_available', 'seller_request.comment',
            'seller_request.id as seller_request_id', 'ticket.id as ticket_id',
            'ticket_transaction.photosuite_date','ticket_transaction.photosuite_location',
            'ticket_transaction.photographer_id','ticket_transaction.mif_id',
            'ticket_transaction.sa_variation', 'ticket_transaction.sa_sku',
            'ticket_transaction.total_sku', 'ticket_transaction.total_images',
             'ticket_transaction.id as transaction_id')
                            ->groupBy('ticket_transaction.id');
        //->toSql(); exit;
        $this->visibleColumns = array('ticket_transaction.id as id', 'ticket.created_at as created_at',
            'ticket_transaction.priority', 'ticket_transaction.group_id', 'ticket_transaction.stage_id',
            'ticket_transaction.status_id', 'ticket_transaction.pending_reason',
            'seller_request.requester_name as requester_name', 'seller_request.email',
            'seller_request.contact_number','seller_request.poc_name', 'ticket_transaction.created_at as assigned_date',
            'seller_request.poc_email', 'seller_request.poc_number','seller_request.total_sku',
            'seller_request.image_available', 'seller_request.comment',
            'ticket_transaction.photosuite_date','ticket_transaction.photosuite_location',
            'ticket_transaction.photographer_id','ticket_transaction.mif_id',
            'ticket_transaction.sa_variation', 'ticket_transaction.sa_sku',
            'ticket_transaction.total_sku', 'ticket_transaction.total_images',
            'seller_request.id as seller_request_id', 'ticket.id as ticket_id',
            'ticket_transaction.id as transaction_id');

        $this->orderBy = array(array('id', 'asc'));

        $queries = DB::getQueryLog(); $last_query = end($queries);  var_dump($last_query); exit;
    }
}