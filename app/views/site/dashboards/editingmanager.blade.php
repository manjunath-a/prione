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
        <h3>Dashboard : Editing Manager</h3>
    </div>
    <div id="myMessage" role="alert"> </div>
    {{ Form::open(array('url' => 'dashboard/editingmanager', 'method' => 'post', 'id'=> "editingmanagerForm")) }}
    <input name="fileProperties" type="hidden" value='[]'>
    <input name="sheetProperties" type="hidden" value='[]'>
    {{ Form::close() }}
    <table id="editingmanager">

    </table>
    <div id="editingmanagerPager">

    </div>
    <script type="text/javascript">
        var lastsel3;
        jQuery("#editingmanager").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"editingmanager",
                    "editurl":'/request/updateEditingManager',
                    "rowNum":25,
                    "height": 340,
                    "viewrecords":false,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':75, 'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden":true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", 'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true,  'editable': true,"hidden":true},
                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},
                        {"index":"sa_sku","align":"center","width":130,"editable":true,"name":"sa_sku", "hidden":true},
                        {"index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation", "hidden":true},
                        {"label":"Group","index":"group_id","align":"center","width":110,"editable":true,
                        "editoptions":{'value':'{{rtrim($group, ";")}}', "disabled": 'disabled' },"edittype":"select","formatter":"select","editrules":{"required":true },"name":"group_id"},

                        {'name':'priority', 'index':'priority', 'editable': true, 'align':'center',  'hidden' : true},
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true, "hidden":true},
                        {"label":"Status","index":"status_id","align":"center","width":110, 'hidden': true,
                        "editable":true, "editoptions":{'value':'{{rtrim($status, ";")}}',"disabled": 'disabled'},"edittype":"select", "formatter":"select", "editrules":{"required":true},"name":"status_id"},

                        {"label":"S3 Path","align":"center","index":"s3_folder","name":"s3_folder","width":90},
                        {"label":"Seller Name","align":"center","index":"merchant_name","name":"merchant_name"},
                        {"label":"Category","align":"center","index":"category_name","name":"category_name"},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","width":90,
                        "editable":true, "editrules":{'edithidden': true },"editoptions":{"disabled": 'disabled' }},
                        {"label":"No. of Images","align":"center","index":"total_images","name":"total_images","width":100,
                        "editable":true, "editrules":{'edithidden': true },"editoptions":{"disabled": 'disabled' }},
                        {"label":"Editing Team Lead","index":"editingteamlead_id", "name":"editingteamlead_id", "align":"center","width":150,
                        "editable":true, "editoptions":{'value':'{{rtrim($editingteamlead, ";")}}'},"edittype":"select","formatter":"select"},
                        {"label":"Stage","index":"stage_id","align":"center","width":350,"editable":true,
                        "editoptions":{'value':'{{rtrim($stage, ";")}}',"disabled": 'disabled'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Comments","align":"right","index":"comment","name":"comment","editable":true,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"}}
                    ],
                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#editingmanager").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#editingmanager').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#editingmanager').saveRow('"+cl+"', '' , '', '', aftersavefunc, '');jQuery('#editingmanager').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#editingmanager').restoreRow('"+cl+"');\" />";
                            jQuery("#editingmanager").jqGrid('setRowData',ids[i],{act:be+se+ce});
                        }
                    },
                    "subGrid":true,
                    "subGridUrl":"editing",
                    "subGridModel" :[
                        {
                            name  : ['Category','City','City Team Lead','Photographer Name','Service Associate' ,'Editor'],
                            width : [200, 200,200,200,200,200]
                        }
                    ],
                    "pager":"editingmanagerPager"
                }
        );
        jQuery("#editingmanager").jqGrid('navGrid', '#editingmanagerPager', {add: false,edit:false,view:false,del:false,refresh: true,search:false});
    </script>
    <!-- ./ content -->
    </div>

@stop
