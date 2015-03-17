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
    {{--{{--}}
        {{--GridRender::setGridId("sellerrequest")--}}
                    {{--->enableFilterToolbar()--}}
                    {{--->setGridOption('url',URL::to('/locallead'))--}}
                    {{--->setGridOption('rowNum', 5)--}}
                    {{--->setGridOption('shrinkToFit',false)--}}
                    {{--->setGridOption('sortname','id')--}}
                    {{--->setGridOption('caption','locallead')--}}
                    {{--->setGridOption('useColSpanStyle', true)--}}
                    {{--->setNavigatorOptions('navigator', array('viewtext'=>'view'))--}}
                    {{--->setNavigatorOptions('view',array('closeOnEscape'=>false))--}}
                    {{--->setFilterToolbarOptions(array('autosearch'=>true))--}}
                    {{--//->setGridEvent('gridComplete', 'gridCompleteEvent') //gridCompleteEvent must be previously declared as a javascript function.--}}
                {{--//    ->setNavigatorEvent('view', 'beforeShowForm', 'function(){alert("Before show form");}')--}}
                 {{--//   ->setFilterToolbarEvent('beforeSearch', 'function(){alert("Before search event");}')--}}
                  {{--//  ->addGroupHeader(array('startColumnName' => 'amount', 'numberOfColumns' => 3, 'titleText' => 'Price'))--}}
                    {{--->addColumn(array('index'=>'id', 'width'=>55))--}}
                    {{--->addColumn(array('label'=>'Name','index'=>'product','width'=>100))--}}
                    {{--->addColumn(array('label'=>'email','index'=>'amount','index'=>'amount', 'width'=>80, 'align'=>'right'))--}}
                {{--//    ->addColumn(array('label'=>'Total','index'=>'total','index'=>'total', 'width'=>80))--}}
                  {{--//  ->addColumn(array('label'=>'Note','index'=>'note','index'=>'note', 'width'=>55,'searchoptions'=>array('attr'=>array('title'=>'Note title'))))--}}
                    {{--->renderGrid();--}}
    {{--}}--}}

    {{
    GridRender::setGridId("sellerrequest")
            ->enableFilterToolbar()
            ->setGridOption('url', URL::to('/locallead'))
            ->setGridOption('rowNum', 25)
            ->setGridOption('shrinkToFit', false)
            ->setGridOption('viewrecords', false)
            ->setNavigatorOptions('navigator', array('view'=>false))
            ->addColumn(array('name'=>'id', 'index'=>'id', 'align'=>'center', 'hidden' => true))
            ->addColumn(array('label'=>'Request Id', 'align'=>'center', 'index'=>'id'))
            ->addColumn(array('label'=>'Request Date', 'align'=>'center', 'index'=>'created_at'))
            ->addColumn(array('label'=>'Seller Name', 'align'=>'center','index'=>'seller_name'))
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
   <!-- -->
@stop