@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
    {{{ Lang::get('user/user.settings') }}} ::
    @parent
@stop

{{-- New Laravel 4 Feature in use --}}
@section('styles')
    @parent
    body {
    background: #f2f2f2;
    }
@stop

{{-- Content --}}
@section('content')
    <div class="page-header">
        <h3>Local Lead Dashboard</h3>
    </div>
<!-- 'formatter' => 'select', -->
    {{
    GridRender::setGridId("sellerrequest")
            ->enableFilterToolbar()
            ->setGridOption('url', URL::to('dashboard/locallead'))
            ->setGridOption('rowNum', 25)
            ->setGridOption('shrinkToFit', false)
            ->setGridOption('viewrecords', false)
            ->setNavigatorOptions('navigator', array('view'=>false))
            ->addColumn(array('name'=>'id', 'index'=>'id', 'align'=>'center', 'hidden' => true))
            ->addColumn(array('label'=>'Request Id', 'align'=>'center', 'index'=>'id'))
            ->addColumn(array('label'=>'Request Date', 'align'=>'center', 'index'=>'created_at'))

            ->addColumn(array('label' => 'Priority','index' => 'priority', 'align' => 'center',
            'width' => 90, 'editable' => true, 'editoptions' => array('value' => $status),
            'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))

            ->addColumn(array('label' => 'Group','index' => 'group_id', 'align' => 'center',
            'width' => 90, 'editable' => true, 'editoptions' => array('value' => $group),
            'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))

            ->addColumn(array('label' => 'Stage','index' => 'stage_id', 'align' => 'center',
            'width' => 90, 'editable' => true, 'editoptions' => array('value' => $stage),
            'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))

            ->addColumn(array('label' => 'Status','index' => 'status_id', 'align' => 'center',
            'width' => 90, 'editable' => true, 'editoptions' => array('value' => $status),
            'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))

            ->addColumn(array('label'=>'Assigned ', 'align'=>'center','index'=>'username', 'editable'=>true))
            ->addColumn(array('label'=>'PhotoGrapher ', 'align'=>'center','index'=>'username', 'editable'=>true))
            ->addColumn(array('label'=>'MIF Collecter ', 'align'=>'center','index'=>'username', 'editable'=>true))
            ->addColumn(array('label'=>'Seller Name', 'align'=>'center','index'=>'seller_name', 'editable'=>true))
            ->addColumn(array('label'=>'Seller Email ID', 'align'=>'center','index'=>'email' ))
            ->addColumn(array('label'=>'Contact Number', 'align'=>'right','index'=>'contact_number'))
            ->addColumn(array('label'=>'POC Name', 'align'=>'center','index'=>'poc_name' ))
            ->addColumn(array('label'=>'POC Email ID', 'align'=>'center','index'=>'poc_email' ))
            ->addColumn(array('label'=>'POC Contact Number','index'=>'poc_number'))
            ->addColumn(array('label'=>'No. of SKUs', 'align'=>'center','index'=>'total_sku'))
            ->addColumn(array('label'=>'Image Available', 'align'=>'center','index'=>'image_available' ))
            ->addColumn(array('label'=>'Comment', 'align'=>'right','index'=>'comment'))
            ->renderGrid();
}}
@stop