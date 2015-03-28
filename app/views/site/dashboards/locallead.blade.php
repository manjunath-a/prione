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
    {{ Form::open(array('url' => 'dashboard/locallead', 'method' => 'post',
        'id'=> "sellerrequestExportForm")) }}
        <input name="fileProperties" type="hidden" value='[]'>
        <input name="sheetProperties" type="hidden" value='[]'>
    {{ Form::close() }}
    <table id="locallead">

    </table>
    <div id="localleadPager">

    </div>
    <script type="text/javascript">
       // $.jgrid.no_legacy_api = false;
       // $.jgrid.useJSON = true;
       // var $ =jQuery.noConflict();
        var lastsel3;
        jQuery("#locallead").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"locallead",
                    "editurl":'/request/update',
                    "rowNum":25,
                    "viewrecords":false,
                    "refersh":true,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':75,'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden":true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", 'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true,  'editable': true,"hidden":true},
                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true, 'hidden' : true},
                        {"index":"image_available","name":"image_available", key:true, 'hidden' : true, 'editable': true, 'editrules': { 'edithidden': true }},

                        {"label":"Request Date","align":"center","index":"created_at","name":"created_at"},
                        {"label":"Priority","index":"priority","align":"center","width":90,"editable":true,
                        "editoptions":{'value':'{{rtrim($priority, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"priority"},

                        {"label":"Group","index":"group_id","align":"center","width":110,"editable":true,
                        "editoptions":{'value':'{{rtrim($group, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"group_id"},

                        {"label":"Stage","index":"stage_id","align":"center","width":350,"editable":true,
                        "editoptions":{'value':'{{rtrim($stage, ";")}}'}, "edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},

                        {"label":"Status","index":"status_id","align":"center","width":110,"editable":true,
                        "editoptions":{'value':'{{rtrim($status, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"status_id"},

                        {"label":"PhotoGrapher", "name":"photographer_id", "index":"photographer_id","align":"center","width":130,
                        "editable":true, "editoptions":{'value':'{{rtrim($photographer, ";")}}'},"edittype":"select","formatter":"select", "id":"photographer_id"},

                        {"label":"PhotoSuiteDate","index":"photosuite_date","align":"center","width":150,"editable":true,"name":"photosuite_date",'formatter': "date",
                        "formatoptions": { "newformat": "Y-m-d"}, "editrules":{"date":true, "required":true}, 'editoptions': { 'dataInit' :
                        function (elem) {
                            jQuery(elem).datepicker({dateFormat:"yy-mm-dd"});}} },
                        {"label":"PhotoSuiteLocation","index":"photosuite_location","align":"center","width":150,"editable":true, "editrules":{"required":false}, "name":"photosuite_location"},

                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","editable":true,"width":90},
                        {"label":"No. of Images","align":"center", "editrules":{"required":false}, "index":"total_images", "name":"total_images","editable":true,"width":100},
                        {"label":"MIF","index":"mif_id","align":"center","width":150,"editable":true,
                        "editoptions":{'value':'{{rtrim($serviceassociates, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"mif_id"},
                        {"label":"No. of parent SKUs","index":"sa_sku","align":"center","width":130,"editable":true,"name":"sa_sku"},
                        {"label":"No. of variations","index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation"},
                        {"label":"Comment","align":"right","index":"comment","name":"comment","editable":true ,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"}}
                    ],
                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#locallead").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#locallead').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#locallead').saveRow('"+cl+"');jQuery('#locallead').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#locallead').restoreRow('"+cl+"');\" />";
                            jQuery("#locallead").jqGrid('setRowData',ids[i],{act:be+se+ce});
                        }
                    },

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
       jQuery("#locallead").jqGrid('navGrid', '#localleadPager', {add: false,edit:false,view:false,del:false,refresh: true});
    </script>
    <!-- ./ content -->
    </div>

@stop