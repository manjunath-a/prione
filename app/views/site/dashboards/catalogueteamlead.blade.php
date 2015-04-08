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
        <h3>Dashboard : Cataloguing Team Lead</h3>
    </div>

    {{ Form::open(array('url' => 'dashboard/catalogueteamlead', 'method' => 'post', 'id'=> "catalogueteamleadForm")) }}
    <input name="fileProperties" type="hidden" value='[]'>
    <input name="sheetProperties" type="hidden" value='[]'>
    {{ Form::close() }}
    <table id="catalogueteamlead">

    </table>
    <div id="catalogueteamleadPager">

    </div>
    <script type="text/javascript">
        var lastsel3;
        jQuery("#catalogueteamlead").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"catalogueteamlead",
                    "editurl":'/request/updateCatalogueTeamLead',
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':75, 'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden":true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", 'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true,  'editable': true,"hidden":true},
                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},
                        {"label":"Seller Name","align":"center","index":"merchant_name","name":"merchant_name"},
                        {"label":"Category","align":"center","index":"category_name","name":"category_name"},
                        {"label":"Priority","index":"priority","align":"center","width":90,"editable":true,
                            "editoptions":{'value':'{{rtrim($priority, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"priority"},

                        {"label":"localteamlead",'width':75,"align":"center","index":"localteamlead_id","name":"localteamlead_id",
                            'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {"label":"editingmanager",'width':75,"align":"center","index":"editingmanager_id","name":"editingmanager_id",
                            'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {"label":"editingteamlead_id",'width':75,"align":"center","index":"editingteamlead_id","name":"editingteamlead_id",
                            'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {"label":"catalogingmanager_id",'width':75,"align":"center","index":"catalogingmanager_id","name":"catalogingmanager_id",
                            'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {"label":"catalogingteamlead_id",'width':75,"align":"center","index":"catalogingteamlead_id","name":"catalogingteamlead_id",
                            'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},


                        {"label":"Group","index":"group_id","align":"center","width":150,"editable":true,
                            "editoptions":{'value':'{{rtrim($group, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"group_id"},
                        {"label":"Stage","index":"stage_id","align":"center","width":350,"editable":true,
                            "editoptions":{'value':'{{rtrim($stage, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Editor","index":"editor","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($editor, ";")}}'},
                            "edittype":"select","editrules":{"required":true},"formatter":"select","name":"editor", "hidden":true},
                        {"label":"Editing Team Lead","index":"editingteamlead","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($editingteamlead, ";")}}'},
                            "edittype":"select","editrules":{"required":true},"formatter":"select","name":"editingteamlead", 'hidden' : true},
                        {"label":"Cataloger","index":"cataloguer","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($cataloguer, ";")}}'},
                            "edittype":"select","editrules":{"required":true},"formatter":"select","name":"cataloguer"},
                        {"label":"No. of Parent SKU","index":"sa_sku","align":"center","width":130,"editable":true,"name":"sa_sku"},
                        {"label":"No. of variation","index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation"},
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true, "hidden":true},
                        {"label":"Status","index":"status_id","align":"center","width":110,"editable":true, "editoptions":{'value':'{{rtrim($status, ";")}}',"disabled": 'disabled'},"edittype":"select", "formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"Pending Reason","index":"pending_reason_id","align":"center","width":280,"editable":true,
                            "editoptions":{'value':'{{rtrim($pending, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"pending_reason_id"},

                        {"label":"Photographer","index":"photographer_id","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($photographer, ";")}}',"disabled": 'disabled'},"edittype":"select","formatter":"select","name":"photographer_id","hidden":true},
                        {"label":"Photoshoot Date","index":"photoshoot_date","align":"center", "editable":true, "editoptions": { "disabled": 'disabled' },"width":150,"name":"photoshoot_date",'formatter': "date", "formatoptions": { "newformat": "Y-m-d"},"hidden":true},

                        {"label":"Photoshoot Location","index":"photoshoot_location","align":"center","width":90,"editable":true,  'hidden': true,
                            "editoptions":{'value':'{{rtrim($photoshootLocation, ";")}}', "disabled": 'disabled' },"edittype":"select","formatter":"select","editrules":{"required":true},"name":"photoshoot_location"},
                        {"label":"S3 Path","align":"center","index":"s3_folder","name":"s3_folder","width":90},
                        {"label":"MIF","index":"mif_id","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($serviceassociates, ";")}}',"disabled": 'disabled'},"edittype":"select","formatter":"select","name":"mif_id","hidden":true},
                        {"align":"center","index":"total_sku","name":"total_sku","width":90,"editable":true,"hidden":true},
                        {"align":"center","index":"total_images","name":"total_images","width":100,"editable":true,"hidden":true},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","width":90},
                        {"label":"No. of Images","align":"center","index":"total_images","name":"total_images","width":100},
                        {"label":"Comments","align":"right","index":"comment","name":"comment","editable":true,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"}}
                    ],
                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#catalogueteamlead").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#catalogueteamlead').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#catalogueteamlead').saveRow('"+cl+"');jQuery('#catalogueteamlead').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#catalogueteamlead').restoreRow('"+cl+"');\" />";
                            jQuery("#catalogueteamlead").jqGrid('setRowData',ids[i],{act:be+se+ce});
                        }
                    },
                    "subGrid":true,
                    "subGridUrl":"editing",
                    "subGridModel" :[
                        {
                            name  : ['City','City Team Lead','Photographer Name','Service Associate' ,'Editor'],
                            width : [200,200,200,200,200]
                        }
                    ],
                    "pager":"catalogueteamleadPager"
                }
        );
        jQuery("#catalogueteamlead").jqGrid('navGrid', '#catalogueteamleadPager', {add: false,edit:false,view:false,del:false,refresh: true,search:false});
    </script>
    <!-- ./ content -->
    </div>

@stop