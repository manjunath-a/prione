<?php
namespace App\Services;
class ReportService
{

    public function getTotalRequest()
    {
        return $total = \SellerRequest::count();
    }

    public function getTotalSKU()
    {

        $total =  \DB::table('ticket_transaction')
                            ->join('ticket', 'ticket.id', '=', 'ticket_transaction.ticket_id')
                            ->join('status', 'status.id', '=', 'ticket_transaction.status_id')
                            ->join('stage', 'stage.id', '=', 'ticket_transaction.stage_id')
                            ->join('group', 'group.id', '=', 'ticket_transaction.group_id')
                            ->join('seller_request', 'seller_request.id', '=', 'ticket.request_id')
                            ->join('category', 'category.id', '=', 'seller_request.category_id')
                            ->join('users', 'seller_request.merchant_city_id', '=', 'users.city_id')
                            ->groupBy('ticket_transaction.ticket_id')
                            ->sum('ticket_transaction.total_sku');
        return $total;
    }

    public function getCountByStage($stageId)
    {

        // select count(*) from  (select id from dcst_ticket_transaction
         // where `dcst_ticket_transaction`.`stage_id` = 6 group by `dcst_ticket_transaction`.`ticket_id`)
        // as tt

        $total =  \DB::query('ticket_transaction')
                      ->groupBy('ticket_transaction.ticket_id')
                      ->where('ticket_transaction.stage_id','=', $stageId)
                      ->count();
        return $total;
    }

    public function getCountByStatus($statusId)
    {
        $total =  \DB::table('ticket_transaction')
                      ->groupBy('ticket_transaction.ticket_id')
                      ->where('ticket_transaction.status_id', '=' ,$statusId)
                      ->count();
        return $total;
    }
}