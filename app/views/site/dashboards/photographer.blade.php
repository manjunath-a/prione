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
            {{--ID : #{{$user->id}}--}}
            City : {{City::Where('id',$user->city_id)->first()->city_name}}</h3>
    </div>
    <div id="myMessage" role="alert"> </div>
    {{ Form::open(array('url' => 'dashboard/photographer', 'method' => 'post',
            'id'=> "photographerForm")) }}
    <input name="fileProperties" type="hidden" value='[]'>
    <input name="sheetProperties" type="hidden" value='[]'>
    {{ Form::close() }}
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
                    "height": 340,
                    "viewrecords":false,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':75, 'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden":true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", 'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true,  'editable': true,"hidden":true},

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


                        {'label':'Assigned Date','name':'assigned_date', 'index':'assigned_date','align':'center'},
                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},
                        {"label":'No Of Parent Images',"index":"sa_sku","align":"center","width":130,"editable":true,"name":"sa_sku", "hidden":true},
                        {"label":'No Of Variation',"index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation", "hidden":true},
                        {'name':'group_id', 'index':'group_id', 'editable': true, 'align':'center', 'hidden' : true},
                        {'name':'priority', 'index':'priority', 'editable': true, 'align':'center',  'hidden' : true},
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true, "hidden":true},
                        {"label":"Status","index":"status_id","align":"center","width":110,"editable":true, 'hidden': true,
                        "editoptions":{'value':'{{rtrim($status, ";")}}', "disabled": 'disabled'},"edittype":"select",
                        "formatter":"select","editrules":{"required":true, 'edithidden': true },"name":"status_id"},
                        {"label":"Pending Reason","index":"pending_reason_id","align":"center","width":280,"editable":true,
                            "editoptions":{'value':'{{rtrim($pending, ";")}}'},"edittype":"select",
                            "formatter":"select","editrules":{"required":true},"name":"pending_reason_id"},
                        {"label":"Photographer", "name":"photographer_id", "index":"photographer_id","align":"center","width":130, "hidden":true,
                        "editable":true, "editoptions":{'value':'{{rtrim($photographer, ";")}}'},"edittype":"select","formatter":"select", "id":"photographer_id"},

                        {"label":"Photoshoot Date","index":"photoshoot_date","align":"center", "editable":true, "editoptions": { "disabled": 'disabled' },
                        "width":150,"name":"photoshoot_date",'formatter': "date", "formatoptions": { "newformat": "Y-m-d"}},
                        {"label":"Photoshoot Location","index":"photoshoot_location","align":"center","width":150,"editable":true,
                        "editoptions":{'value':'{{rtrim($photoshootLocation, ";")}}', "disabled": 'disabled' },"edittype":"select","formatter":"select","editrules":{"required":true},"name":"photoshoot_location"},
                        {"label":"S3 Path","align":"center",  "index":"s3_folder","name":"s3_folder","width":90},
                        {"label":"Category","align":"center","index":"category_name","name":"category_name"},
                        {"label":"Service Associate","index":"mif_id","align":"center","width":150,"editable":true,
                         "editoptions":{'value':'{{rtrim($serviceassociates, ";")}}',"disabled": 'disabled'},"edittype":"select",
                         "formatter":"select","name":"mif_id"},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku","editable":true,"width":90},
                        {"label":"No. of Images","align":"center","index":"total_images","name":"total_images","editable":true,"width":100},
                        {"label":"Stage","index":"stage_id","align":"center","width":350,"editable":true,'hidden': true,
                        "editoptions":{'value':'{{rtrim($stage, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Comments","align":"right","index":"comment","name":"comment","editable":true,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"}}
                    ],
                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#photographer").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#photographer').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#photographer').saveRow('"+cl+"', '' , '', '', aftersavefunc, '');jQuery('#photographer').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#photographer').restoreRow('"+cl+"');\" />";
                            jQuery("#photographer").jqGrid('setRowData',ids[i],{act:be+se+ce});
                        }
                    },
                    "subGrid":true,
                    "subGridUrl":"seller",
                    "subGridModel" :[
                        {
                            name  : ['Seller name','Category','POC Name','POC Email ID','POC Contact Number', 'Seller Provided Images'],
                            width : [250,150,120,180],
                            colModel: [
                                {"label":"Seller Name","align":"center","index":"merchant_name","editable":true,"name":"merchant_name"},
                                {"label":"Category","align":"center","index":"category","name":"category"},
                                {"label":"POC Name","align":"center","index":"poc_name","name":"poc_name"},
                                {"label":"POC Email ID","align":"center","index":"poc_email","name":"poc_email"},
                                {"label":"POC Contact Number","index":"poc_number","name":"poc_number"},
                                {"label":"Seller Provided Images","index":"image_available","name":"image_available"}
                            ]
                        }
                    ],
                    "pager":"photographerPager"
                }
        );
        jQuery("#photographer").jqGrid('navGrid', '#photographerPager', {add: false,edit:false,view:false,del:false,refresh: true,search:false});
    </script>
    <!-- ./ content -->
    </div>

@stop