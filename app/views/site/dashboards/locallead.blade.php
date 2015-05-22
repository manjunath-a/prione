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
    <div id="myMessage" role="alert"> </div>
    {{ Form::open(array('url' => 'dashboard/locallead', 'method' => 'post',
        'id'=> "sellerrequestExportForm")) }}
    {{ Form::close() }}
    <table id="locallead">
    </table>
    <div id="localleadPager">
    </div>
    <script type="text/javascript">
        var lastsel2;
        jQuery("#locallead").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"locallead",
                    "editurl":'/request/update',
                    "rowNum":25,
                    "height": 340,
                    "viewrecords":true,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':75,'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden": true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", 'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true,  'editable': true,"hidden":true},
                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id", 'hidden' : true},
                        {"label":"Seller Name","align":"center","index":"merchant_name","name":"merchant_name"},
                        {"label":"Requester Name","align":"center","index":"requester_name","name":"requester_name"},
                        {"label":"localteamlead",'width':75,"align":"center","index":"localteamlead_id","name":"localteamlead_id",
                            'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {"index":"image_available","name":"image_available", key:true, 'hidden' : true, 'editable': true, 'editrules': { 'edithidden': true }},

                        {"label":"Assigned Date","align":"center","index":"created_at","name":"created_at","width":160},
                        {"label":"Priority","index":"priority","align":"center","width":90,"editable":true,
                        "editoptions":{'value':'{{rtrim($priority, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"priority"},

                        {"label":"Group","index":"group_id","align":"center","width":110,"editable":true,
                        "editoptions":{'value':'{{rtrim($group, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"group_id"},

                        {"label":"Stage","index":"stage_id","align":"center","width":350,"editable":true,
                        "editoptions":{'value':'{{rtrim($stage, ";")}}'}, "edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},

                        {"label":"Status","index":"status_id","align":"center","width":110,"editable":true,
                        "editoptions":{'value':'{{rtrim($status, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"status_id"},

                        {"label":"Pending Reason","index":"pending_reason_id","align":"center","width":280,"editable":true,
                            "editoptions":{'value':'{{rtrim($pending, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"pending_reason_id"},

                        {"label":"Photographer", "name":"photographer_id", "index":"photographer_id","align":"center","width":130,
                        "editable":true, "editoptions":{'value':'{{rtrim($photographer, ";")}}'},"edittype":"select","formatter":"select", "id":"photographer_id"},

                        {"label":"Photoshoot Date","index":"photoshoot_date","align":"center","width":150,"editable":true,"name":"photoshoot_date",'formatter': "date",
                        "formatoptions": { "newformat": "Y-m-d"}, "editrules":{"date":true, "required":false}, 'editoptions': { 'dataInit' :
                        function (elem) {
                            jQuery(elem).datepicker({dateFormat:"yy-mm-dd"});}} },

                        {"label":"Photoshoot Location","index":"photoshoot_location","align":"center","width":150,"editable":true,
                        "editoptions":{'value':'{{rtrim($photoshootLocation, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"photoshoot_location"},
                        {"label":"S3 Path","align":"center","index":"s3_folder","name":"s3_folder","width":90},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","editable":true,"width":90},
                        {"label":"No. of Images","align":"center", "editrules":{"required":false}, "index":"total_images", "name":"total_images","editable":true,"width":100},

                        {"label":"Service Associate","index":"mif_id","align":"center","width":150,"editable":true,
                        "editoptions":{'value':'{{rtrim($serviceassociates, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"mif_id"},

                        {"label":"No. of parent SKUs","index":"sa_sku","align":"center","width":130,"editable":true,"name":"sa_sku"},
                        {"label":"No. of variations","index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation"},
                        {"label":"Comments","align":"right","index":"comment","name":"comment","editable":true ,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"}}
                    ],
                    
                    onSelectRow: function(id){
                        console.log(id);
                    if(id && id!==lastsel2){
                        jQuery('#locallead').restoreRow(lastsel2);
                        lastsel2=id;
                        }
                        jQuery('#locallead').editRow(id,true);
                    },
                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#locallead").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            //be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#locallead').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#locallead').saveRow('"+cl+"', '' , '' ,'' ,aftersavefunc, '' );jQuery('#locallead').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#locallead').restoreRow('"+cl+"');\" />";
                            jQuery("#locallead").jqGrid('setRowData',ids[i],{act:se+ce});
                        }
                    },
                    "subGrid":true,
                    "subGridUrl":"seller",
                    "subGridModel" :[
                        {
                            name  : ['Requested Date','Category', 'POC Name', 'POC Email', 'POC Number', 'Requester Email',  'Requester Number', 'Seller Provided Images',
                                    'Local Team Lead', 'Photographer', 'Service Associate' ,
                                    'Editing Manager', 'Editing Team Lead', 'Editor',
                                    'Cataloging Manager', 'Cataloging Team Lead', 'Cataloger','Rejected By'],
                            width : [200,200,200,150,120,120,160,120,120,120,120,120,120,120,120,120],
                            colModel: [
                                {"label":"Requested Date","index":"request_created","name":"request_created"},
                                {"label":"Category","align":"center","index":"category","name":"category"},
                                {"label":"POC Name","align":"center","index":"poc_name","name":"poc_name"},
                                {"label":"POC Email","align":"center","index":"poc_email","name":"poc_email"},
                                {"label":"POC Contact Number","index":"poc_number","name":"poc_number"},

                                {"label":"Requester Email","align":"center","index":"email","name":"email"},
                                {"label":"Requester Number","index":"contact_number","name":"contact_number"},
                                {"label":"Seller Provided Images","align":"center","index":"image_available","name":"image_available"},
                                {"label":"Local Team Lead","align":"center","index":"LocalTeamLead","name":"LocalTeamLead"},
                                {"label":"Photographer","align":"center","index":"Photographer","name":"Photographer"},
                                {"label":"Service Associate","align":"center","index":"ServiceAssociate","name":"ServiceAssociate"},
                                {"label":"Editing Manager","align":"center","index":"EditingManager","name":"EditingManager"},
                                {"label":"Editing Team Lead","align":"center","index":"EditingTeamLead","name":"EditingTeamLead"},
                                {"label":"Editor","align":"center","index":"Editor","name":"Editor"},
                                {"label":"Cataloging Manager","align":"center","index":"CatalogingManager","name":"CatalogingManager"},
                                {"label":"Cataloging Team Lead","align":"center","index":"CatalogingTeamLead","name":"CatalogingTeamLead"},
                                {"label":"Cataloger","align":"center","index":"Cataloger","name":"Cataloger"},
                                {"label":"Reject By","align":"center","index":"RejectedBy","name":"RejectedBy"}
                            ]
                        }
                    ],
                    "pager":"localleadPager"
                    //'cellEdit': true
                }
        );
       jQuery("#locallead").jqGrid('navGrid', '#localleadPager',
        {add: false,edit:false,view:false,del:false,refresh: true,search:false});
    </script>
    <!-- ./ content -->
    </div>
@stop
