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

    {{ Form::open(array('url' => 'dashboard/mif', 'method' => 'post', 'id'=> "mifForm")) }}
    <input name="fileProperties" type="hidden" value='[]'>
    <input name="sheetProperties" type="hidden" value='[]'>
    {{ Form::close() }}
    <table id="mif">

    </table>
    <div id="mifPager">

    </div>
    <script type="text/javascript">
        var lastsel3;
        jQuery("#mif").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"mif",
                    "editurl":'/request/updateMIF',
                    "rowNum":25,
                    "viewrecords":false,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':75, 'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden":true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", 'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'name':'group_id', 'index':'group_id', 'editable': true, 'align':'center', 'key':true, 'hidden' : true},
                        {'name':'priority', 'index':'priority', 'editable': true, 'align':'center', 'key':true, 'hidden' : true},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true,  'editable': true,"hidden":true},
                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true, "hidden":true},
                        {"label":"Status","index":"status_id","align":"center","width":110,"editable":true, "editoptions":{'value':'{{rtrim($status, ";")}}'},"edittype":"select", "formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"Appointment Date","index":"photosuite_date","align":"center", "editable":true, "editoptions": { "disabled": 'disabled' },"width":150,"name":"photosuite_date",'formatter': "date", "formatoptions": { "newformat": "Y-m-d"}},
                        {"label":"PhotoSuiteLocation","index":"photosuite_location","align":"center", "editable":true, "editoptions": { "disabled": 'disabled' },"width":150,"name":"photosuite_location", 'hidden': true},
                        {"label":"S3 Path","align":"center","index":"s3_path","name":"s3_path","width":90},
                        {"label":"Seller Name","align":"center","index":"seller_name","name":"seller_name"},
                        {"label":"Category","align":"center","index":"category","name":"category"},
                        {"label":"MIF","index":"mif_id","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($serviceassociates, ";")}}'},"edittype":"select","formatter":"select","name":"mif_id", 'hidden': true},
                        {"label":"PhotoGrapher","index":"photographer_id","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($photographer, ";")}}',"disabled": 'disabled'},"edittype":"select","formatter":"select","name":"photographer_id"},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","editable":true,"width":90, 'hidden': true},
                        {"label":"No. of Images","align":"center","index":"total_images","name":"total_images","editable":true,"width":100, 'hidden': true},
                        {"index":"sa_sku","align":"center","width":130,"editable":true,"name":"sa_sku"},
                        {"index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation"},
                        {"label":"Stage","index":"stage_id","align":"center","width":350,"editable":true,
                            "editoptions":{'value':'{{rtrim($stage, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Comment","align":"right","index":"comment","name":"comment","editable":true,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"}}
                    ],
                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#mif").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#mif').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#mif').saveRow('"+cl+"');jQuery('#mif').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#mif').restoreRow('"+cl+"');\" />";
                            jQuery("#mif").jqGrid('setRowData',ids[i],{act:be+se+ce});
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
                    "pager":"mifPager"
                }
        );
    </script>
    <!-- ./ content -->
    </div>

@stop