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

    <form method="POST" action="http://prione.app/dashboard/photographer" accept-charset="UTF-8" id="sellerrequestExportForm">
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
    <table id="photographer">

    </table>
    <div id="photographerPager">

    </div>
    <script type="text/javascript">
        var lastsel3;
        jQuery("#photographer").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"photographer",
                    "editurl":'/request/updatePhotographer',
                    "rowNum":25,
                    "viewrecords":false,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':75, 'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden":true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center",
                        'editable': true, 'hidden': true, 'editrules': { 'edithidden': true } ,
                        },
                        {'label':'Ticket id', 'name':'ticket_id', 'index':'ticket_id',
                        'editable': true,'align':'center', 'key':true, 'hidden' : false},

                        {'label':'mif_id', 'name':'mif_id', 'index':'mif_id',
                        'editable': true, 'align':'center', 'key':true, 'hidden' : false},
                        {'label':'group_id', 'name':'group_id', 'index':'group_id',
                        'editable': true, 'align':'center', 'key':true, 'hidden' : false},
                         {'label':'priority', 'name':'priority', 'index':'priority',
                        'editable': true, 'align':'center', 'key':true, 'hidden' : false},


                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true},
                          {"label":"Status","index":"status_id","align":"center","width":110,"editable":true,
                        "editoptions":{'value':'{{rtrim($status, ";")}}'},"edittype":"select",
                        "formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"PhotoSuiteDate","index":"photosuite_date","align":"center","width":150,"name":"photosuite_date",'formatter': "date", "editrules":{"date":true},
                        'editoptions': { 'dataInit' : function (elem) {jQuery(elem).datepicker({dateFormat:"yy-mm-dd"});}} },
                        {"label":"PhotoSuiteLocation","index":"photosuite_location","align":"center","width":150,"name":"photosuite_location"},
                        {"label":"S3 Path","align":"center","index":"s3_path","name":"s3_path","width":90},
                        {"label":"Seller Name","align":"center","index":"seller_name","name":"seller_name"},
                        {"label":"Category","align":"center","index":"category","name":"category"},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","editable":true,"width":90},
                        {"label":"No. of Images","align":"center","index":"total_images","name":"total_images","editable":true,"width":100},
                        {"label":"Stage","index":"stage_id","align":"center","width":350,"editable":true,
                        "editoptions":{'value':'{{rtrim($stage, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Comment","align":"right","index":"comment","name":"comment","editable":true}
                    ],
                    jsonReader: { repeatitems : false, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#photographer").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#photographer').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#photographer').saveRow('"+cl+"');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#photographer').restoreRow('"+cl+"');\" />";
                            jQuery("#photographer").jqGrid('setRowData',ids[i],{act:be+se+ce});
                        }
                    },
                    "subGrid":true,
                    "subGridUrl":"seller",
                    "subGridModel" :[
                        {
                            name  : ['Seller Name', 'Seller Email ID', 'Contact Number',
                            'POC Name', 'POC Email ID','POC Contact Number'],
                            width : [300,200,120,120,120,200],
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
                    "pager":"photographerPager"
                }
        );
    </script>
    <!-- ./ content -->
    </div>

@stop