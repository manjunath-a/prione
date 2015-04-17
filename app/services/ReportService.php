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
        $active=1;
        if($stageId==8)
            $active=0;
        $results = \DB::select("SELECT count(*) AS demand FROM (
                                SELECT  id FROM dcst_ticket_transaction
                                WHERE dcst_ticket_transaction.stage_id = :stage_id
                                AND dcst_ticket_transaction.active = :active=1
                                GROUP BY dcst_ticket_transaction.ticket_id) AS cnt", ['stage_id' => $stageId,
                                'active' =>$active]);
        return $results[0]->demand;
    }

    public function getCountByStatus($statusId)
    {
        $active=1;
        if($statusId==4)
            $active=0;
        $results = \DB::select("SELECT count(*) AS demand FROM (
                                SELECT  id FROM dcst_ticket_transaction
                                WHERE dcst_ticket_transaction.status_id = :status_id
                                AND dcst_ticket_transaction.active = :active
                                GROUP BY dcst_ticket_transaction.ticket_id) AS cnt", ['status_id' => $statusId,
                                'active' => $active]);
        return $results[0]->demand;
    }
}