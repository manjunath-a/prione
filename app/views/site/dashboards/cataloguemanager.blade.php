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
        <h3>Dashboard : Cataloging Manager</h3>
    </div>

    {{ Form::open(array('url' => 'dashboard/cataloguemanager', 'method' => 'post', 'id'=> "cataloguemanagerForm")) }}
    <input name="fileProperties" type="hidden" value='[]'>
    <input name="sheetProperties" type="hidden" value='[]'>
    {{ Form::close() }}
    <table id="cataloguemanager">

    </table>
    <div id="cataloguemanagerPager">

    </div>
    <script type="text/javascript">
        var lastsel3;
        jQuery("#cataloguemanager").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"cataloguemanager",
                    "editurl":'/request/updateAssignCatalogue',
                    "rowNum":25,
                    "viewrecords":false,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':75, 'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden":true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", 'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true,  'editable': true,"hidden":true},
                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},
                        {"index":"sa_sku","align":"center","width":130,"editable":true,"name":"sa_sku", "hidden":true},
                        {"index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation", "hidden":true},
                        {'name':'group_id', 'index':'group_id', 'editable': true, 'align':'center', 'key':true, 'hidden' : true},
                        {'name':'priority', 'index':'priority', 'editable': true, 'align':'center', 'key':true, 'hidden' : true},
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true, "hidden":true},
                        {"label":"Status","index":"status_id","align":"center","width":110,"editable":true, "editoptions":{'value':'{{rtrim($status, ";")}}',"disabled": 'disabled'},"edittype":"select", "formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"PhotoGrapher","index":"photographer_id","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($photographer, ";")}}',"disabled": 'disabled'},"edittype":"select","formatter":"select","name":"photographer_id","hidden":true},
                        {"label":"PhotoSuiteDate","index":"photosuite_date","align":"center", "editable":true, "editoptions": { "disabled": 'disabled' },"width":150,"name":"photosuite_date",'formatter': "date", "formatoptions": { "newformat": "Y-m-d"},"hidden":true},
                        {"label":"PhotoSuiteLocation","index":"photosuite_location","align":"center", "editable":true, "editoptions": { "disabled": 'disabled' },"width":150,"name":"photosuite_location","hidden":true},
                        {"label":"S3 Path","align":"center","index":"s3_folder","name":"s3_folder","width":90},
                        {"label":"Seller Name","align":"center","index":"merchant_name","name":"merchant_name"},
                        {"label":"Category","align":"center","index":"category_name","name":"category_name"},
                        {"label":"MIF","index":"mif_id","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($serviceassociates, ";")}}',"disabled": 'disabled'},"edittype":"select","formatter":"select","name":"mif_id","hidden":true},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","width":90,"editable":true,"hidden":true},
                        {"label":"No. of Images","align":"center","index":"total_images","name":"total_images","width":100,"editable":true,"hidden":true},
                        {"label":"Editing Team Lead","index":"editingteamlead","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($editingteamlead, ";")}}'},"edittype":"select","formatter":"select","name":"editingteamlead","hidden":true},
                        {"label":"Editor","index":"editor","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($editor, ";")}}'},
                            "edittype":"select","editrules":{"required":true},"formatter":"select","name":"editor", 'hidden' : true},
                        {"label":"Catalog Team Lead","index":"catalogueTeamLead","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($catalogueTeamLead, ";")}}'},"edittype":"select","formatter":"select","name":"catalogueTeamLead"},
                        {"label":"Stage","index":"stage_id","align":"center","width":350,"editable":true,
                        "editoptions":{'value':'{{rtrim($stage, ";")}}',"disabled": 'disabled'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Comment","align":"right","index":"comment","name":"comment","editable":true,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"}}
                    ],
                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#cataloguemanager").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#cataloguemanager').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#cataloguemanager').saveRow('"+cl+"');jQuery('#cataloguemanager').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#cataloguemanager').restoreRow('"+cl+"');\" />";
                            jQuery("#cataloguemanager").jqGrid('setRowData',ids[i],{act:be+se+ce});
                        }
                    },
                    "subGrid":true,
                    "subGridUrl":"editing",
                    "subGridModel" :[
                        {
                            name  : ['City','City Team Lead','Photographer Name','MIF Name'],
                            width : [200,200,200,200]
                        }
                    ],
                    "pager":"cataloguemanagerPager"
                }
        );
        jQuery("#cataloguemanager").jqGrid('navGrid', '#cataloguemanagerPager', {add: false,edit:false,view:false,del:false,refresh: true,search:false});
    </script>
    <!-- ./ content -->
    </div>

@stop