<?php
/**
 * Created by Compassites.
 * User: Arasu
 * Date: 12/03/15
 * Time: 8:43 PM
 */

use Illuminate\Database\Eloquent\Model;
use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;

class LocalLeadRepository extends EloquentRepositoryAbstract  {

    public function __construct() {
        $this->Database = DB::table('ticket')
                             ->join('seller_request', 'seller_request.id', '=', 'ticket.request_id');

        $this->visibleColumns = array('seller_request.id as id', 'ticket.created_at as created_at',
            'ticket.priority', 'seller_request.seller_name as seller_name',
            'seller_request.email',  'seller_request.contact_number','seller_request.poc_name',
            'seller_request.poc_email', 'seller_request.poc_number','seller_request.total_sku',
            'seller_request.image_available','seller_request.comment');

        $this->orderBy = array(array('id', 'asc'));
    }
}