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
        <h3>Dashboard : {{$user->username}}
            City : {{City::Where('id',$user->city_id)->first()->city_name}}</h3>
    </div>

    <form method="POST" action="http://prione.app/dashboard/locallead" accept-charset="UTF-8" id="sellerrequestExportForm">
        <input name="_token" type="hidden" value="NRpSJe8P8nLMhPcW1XxuDCenBuME47Y1dptcjznF">
        <input id="sellerrequestName" name="name" type="hidden" value="sellerrequest">
        <input id="sellerrequestModel" name="model" type="hidden">
        <input id="sellerrequestExportFormat" name="exportFormat" type="hidden" value="xls">
        <input id="sellerrequestFilters" name="filters" type="hidden">
        <input id="sellerrequestPivotFlag" name="pivot" type="hidden" value="">
        <input id="sellerrequestRows" name="pivotRows" type="hidden">
        <input name="fileProperties" type="hidden" value='[]'>
        <input name="sheetProperties" type="hidden" value='[]'>
    </form>
    <table id="locallead">

    </table>
    <div id="localleadPager">

    </div>
    <script type="text/javascript">
        // $.jgrid.no_legacy_api = false;
        //        $.jgrid.useJSON = true;
        var lastsel3;
        // var $ =jQuery.noConflict();
        jQuery("#locallead").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"locallead",
                    "editurl":'/request/update',
                    "rowNum":25,
                    // "autowidth":true,
                    //"shrinkToFit":false,
                    "viewrecords":true,
                    "colModel":[
                        {"label":"Action",name:'act',index:'act', width:75,sortable:false},
                        {"name":"id","index":"id","align":"center","hidden":true},
                        {"label":"Request Id",width:75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true},
                        {"label":"Request Date","align":"center","index":"created_at","name":"created_at"},
                        {"label":"Priority","index":"priority","align":"center","width":90,"editable":true,"editoptions":{"value":"1:Low;2:Medium;3:Urgent;4:high"},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"priority"},
                        {"label":"Group","index":"group_id","align":"center","width":110,"editable":true,"editoptions":{"value":"1:Local;2:Editing;3:Cataloging"},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"group_id"},
                        {"label":"Stage","index":"stage_id","align":"center","width":320,"editable":true,"editoptions":{"value":"1:(Local) Associates Not Assigned;2:(Local) Associates Assigned;3:(Local) Photoshoot Completed \/ Seller Images Provided;4:(Local) MIF Completed;5:(Central) Editing Completed;6:(Central) Cataloging Completed;7:(Central) ASIN Created"},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        // {"label":"Status","index":"status_id","align":"center","width":90,"editable":true,"editoptions": "<?php //echo '{value:'.$status .'}'?>" ,"edittype":"select","formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"Status","index":"status_id","align":"center","width":110,"editable":true,"editoptions":{"value":"1:Open;2:Pending;3:Resolved;4:Closed;5:Rejectd"},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"PhotoGrapher","index":"photographer_id","align":"center","width":130,"editable":true,"editoptions":{"value":"-1:select;9:Photographer","defaultValue":-1},"edittype":"select","formatter":"select","name":"photographer_id"},
                        {"label":"PhotoSuiteDate","index":"photosuite_date","align":"center","width":150,"editable":true,"name":"photosuite_date",formatter: "date", "editrules":{"date":true},editoptions: { dataInit : function (elem) {jQuery(elem).datepicker({dateFormat:"yy-mm-dd"});}} },
                        {"label":"PhotoSuiteLocation","index":"photosuite_location","align":"center","width":150,"editable":true,"name":"photosuite_location"},
                        //{"label":"Assigned ","align":"center","index":"username","editable":true,"name":"username"},
                        // {"label":"PhotoGrapher ","align":"center","index":"username","editable":true,"name":"username"},
                        //{"label":"MIF Collecter ","align":"center","index":"username","editable":true,"name":"username"},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","editable":true,"width":90},
                        {"label":"No. of Images","align":"center","index":"image_available","name":"image_available","editable":true,"width":100},
                        {"label":"MIF","index":"mif_id","align":"center","width":150,"editable":true,"editoptions":{"value":"-1:select;10:ServicesAssociate","defaultValue":"-1"},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"mif_id"},
                        {"label":"No. of parent SKUs","index":"sa_sku","align":"center","width":115,"editable":true,"name":"sa_sku"},
                        {"label":"No. of variations","index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation"},
                        {"label":"Comment","align":"right","index":"comment","name":"comment","editable":true}
                    ],
                    jsonReader: { repeatitems : false, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#locallead").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#locallead').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#locallead').saveRow('"+cl+"');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#locallead').restoreRow('"+cl+"');\" />";
                            jQuery("#locallead").jqGrid('setRowData',ids[i],{act:be+se+ce});
                        }
                    },
//                    "onSelectRow" : function(id)
//                                    {alert(1);
//                                        jQuery("#"+id+"_photosuite_date","#locallead").datepicker({dateFormat:"yy-mm-dd"});
////                                        if(id && id!==lastsel3)
////                                        {
//////                                            jQuery("#locallead").jqGrid('restoreRow',lastsel3);
//////                                            alert(1);
////                                           jQuery('#locallead').jqGrid('editRow',id,false,pickdates);
////                                            lastsel3=id;
////                                        }
//                                    },
                    "subGrid":true,
                    "subGridUrl":"seller",
                    "subGridModel" :[
                        {
                            name  : ['seller_name','Seller Email ID','Contact Number','POC Name','POC Email ID','POC Contact Number'],
                            width : [120,120,120,120,120,120],
                            colModel: [
                                {"align":"center","index":"seller_name","editable":true,"name":"seller_name"},
                                {"label":"Seller Email ID","align":"center","index":"email","name":"email"},
                                {"label":"Contact Number","align":"right","index":"contact_number","name":"contact_number"},
                                {"label":"POC Name","align":"center","index":"poc_name","name":"poc_name"},
                                {"label":"POC Email ID","align":"center","index":"poc_email","name":"poc_email"},
                                {"label":"POC Contact Number","index":"poc_number","name":"poc_number"}
                            ]
                        }
                    ],
                    "pager":"localleadPager"
                    //'cellEdit': true
                }
        );
        ////jQuery("#locallead").jqGrid('navGrid',"#localleadPager",{edit:false,add:false,del:false});
        ////.navGrid("#localleadPager",{"add":false,"edit":fasle,"del":false,"search":false,"view":false,"refresh":false}, {}, {}, {}, {}, {} );
        //
        //        function pickdates(id)
        //        {
        //            alert(2);
        //            jQuery("#"+id+"_photosuite_date","#locallead").datepicker({dateFormat:"yy-mm-dd"});
        //        }
    </script>
    <!-- ./ content -->
    </div>

    {{--<!-- 'formatter' => 'select', -->--}}
    {{--{{--}}
    {{--GridRender::setGridId("sellerrequest")--}}
    {{--->enableFilterToolbar()--}}
    {{--->setGridOption('url', URL::to('/dashboard/locallead'))--}}
    {{--->setGridOption('rowNum', 25)--}}
    {{--->setGridOption('editurl',URL::to('/request/update'))--}}
    {{--->setGridOption('shrinkToFit', false)--}}
    {{--->setGridOption('viewrecords', false)--}}
    {{--->setNavigatorOptions('navigator', array('view'=>false))--}}
    {{--->addColumn(array('name'=>'id', 'index'=>'id', 'align'=>'center', 'hidden' => false))--}}
    {{--->addColumn(array('name'=>'seller_request_id', 'index'=>'seller_request_id',--}}
    {{--'align'=>'center', 'hidden' => false))--}}
    {{--->addColumn(array('name'=>'ticket_id', 'index'=>'ticket_id', 'align'=>'center', 'hidden' => false))--}}

    {{--->addColumn(array('label'=>'Request Id', 'align'=>'center', 'index'=>'id'))--}}
    {{--->addColumn(array('label'=>'Request Date', 'align'=>'center', 'index'=>'created_at'))--}}

    {{--->addColumn(array('label' => 'Priority','index' => 'priority', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true, 'editoptions' => array('value' => $status),--}}
    {{--'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))--}}

    {{--->addColumn(array('label' => 'Group','index' => 'group_id', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true, 'editoptions' => array('value' => $group),--}}
    {{--'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))--}}

    {{--->addColumn(array('label' => 'Stage','index' => 'stage_id', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true, 'editoptions' => array('value' => $stage),--}}
    {{--'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))--}}

    {{--->addColumn(array('label' => 'Status','index' => 'status_id', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true, 'editoptions' => array('value' => $status),--}}
    {{--'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))--}}

    {{--->addColumn(array('label' => 'PhotoGrapher','index' => 'photographer_id', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true, 'editoptions' => array('value' => $photographer, 'defaultValue' =>-1),--}}
    {{--'edittype' => 'select', 'formatter' => 'select'))--}}

    {{--->addColumn(array('label' => 'PhotoSuiteDate','index' => 'photosuite_date', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true, 'editoptions' => array('formatter' => 'date','edittype' => 'date')))--}}

    {{--->addColumn(array('label' => 'PhotoSuiteLocation','index' => 'photosuite_location', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true))--}}

    {{--->addColumn(array('label' => 'MIF','index' => 'mif_id', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true, 'editoptions' => array('value' => $serviceassociates, 'defaultValue' =>'-1'),--}}
    {{--'edittype' => 'select', 'formatter' => 'select', 'editrules' => array('required' => true)))--}}

    {{--->addColumn(array('label' => 'ServiceAssociate SKU','index' => 'sa_sku', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true))--}}

    {{--->addColumn(array('label' => 'ServiceAssociate Variation','index' => 'sa_variation', 'align' => 'center',--}}
    {{--'width' => 90, 'editable' => true))--}}

    {{--->addColumn(array('label'=>'Seller Name', 'align'=>'center','index'=>'seller_name', ))--}}
    {{--->addColumn(array('label'=>'Seller Email ID', 'align'=>'center','index'=>'email' ))--}}
    {{--->addColumn(array('label'=>'Contact Number', 'align'=>'right','index'=>'contact_number'))--}}
    {{--->addColumn(array('label'=>'POC Name', 'align'=>'center','index'=>'poc_name' ))--}}
    {{--->addColumn(array('label'=>'POC Email ID', 'align'=>'center','index'=>'poc_email' ))--}}
    {{--->addColumn(array('label'=>'POC Contact Number','index'=>'poc_number'))--}}
    {{--->addColumn(array('label'=>'No. of SKUs', 'align'=>'center','index'=>'total_sku'))--}}
    {{--->addColumn(array('label'=>'Image Available', 'align'=>'center','index'=>'image_available' ))--}}
    {{--->addColumn(array('label'=>'Comment', 'align'=>'right','index'=>'comment'))--}}
    {{--->renderGrid();--}}
    {{--}}--}}
@stop