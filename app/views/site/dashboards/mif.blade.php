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
                    "height": 340,
                    "viewrecords":true,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':55, 'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden":true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center",
                            'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'name':'group_id', 'index':'group_id', 'editable': true, 'align':'center',  'hidden' : true},
                        {'name':'priority', 'index':'priority', 'editable': true, 'align':'center', 'hidden' : true},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true,  'editable': true,
                                "hidden":true},
                        {'name':'image_available', 'index':'image_available','align':'center',
                        'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':63, 'align':'center'},
                        {'label':'Assigned Date','name':'assigned_date', 'index':'assigned_date','align':'center', 'width':140},
                        {"label":"Seller Name","align":"center","index":"merchant_name","name":"merchant_name", 'width':120,},
                        {"label":"Seller Ph#", "index":"merchant_phone","name":"merchant_phone","width":80, "align":"center"},
                        {"label":"Category","align":"center","index":"category_name","name":"category_name"},
                        {"label":"Photographer", "index":"photographer_id","align":"center","width":120,"editable":true, "editoptions":{'value':'{{rtrim($photographer, ";")}}',"disabled": 'disabled'},"edittype":"select","formatter":"select","name":"photographer_id"},
                        {"label":"Photoshoot Date","index":"photoshoot_date","align":"center", "editable":true, "editoptions": { "disabled": 'disabled' },
                        "width":120,"name":"photoshoot_date",'formatter': "date", "formatoptions": { "newformat": "Y-m-d"}},
                        {"label":"Requester Name","align":"center", 'hidden': true, "index":"requester_name","name":"requester_name"},
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id",key:true, "hidden":true},
                        {"label":"Status","index":"status_id","align":"center","width":110,"editable":true,
                        'hidden': true,  "editoptions":{'value':'{{rtrim($status, ";")}}', "disabled": 'disabled'},"edittype":"select", "formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"Pending Reason","index":"pending_reason_id","align":"center","width":240,"editable":true,
                            "editoptions":{'value':'{{rtrim($pending, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"pending_reason_id"},

                        {"label":"Photoshoot Location", 'hidden': true,  "index":"photoshoot_location","align":"center","width":150,"editable":true,
                        "editoptions":{'value':'{{rtrim($photoshootLocation, ";")}}', "disabled": 'disabled' },"edittype":"select","formatter":"select","editrules":{"required":true},"name":"photoshoot_location"},
                        {"label":"S3 Path","align":"center", 'hidden': true, "index":"s3_folder","name":"s3_folder","width":90},
                        {"label":"Service Associate", 'hidden': true, "index":"mif_id","align":"center","width":150,"editable":true, "editoptions":{'value':'{{rtrim($serviceassociates, ";")}}'},"edittype":"select","formatter":"select","name":"mif_id", 'hidden': true},
                        {"label":"#SKUs", 'hidden': true, "align":"center","index":"total_sku","name":"total_sku","editable":true,"width":90},
                        {"label":"#Images", 'hidden': true, "align":"center","index":"total_images","name":"total_images","editable":true,"width":100},
                        {"label":"#Parent SKU","index":"sa_sku","align":"center","width":120,"editable":true,"name":"sa_sku"},
                        {"label":"#Variation","index":"sa_variation","align":"center","width":100,"editable":true,"name":"sa_variation"},
                        {"label":"Stage","index":"stage_id", 'hidden': true, "align":"center","width":350, "editable":true, 'hidden' : true,
                            "editoptions":{'value':'{{rtrim($stage, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Comments","align":"right","index":"comment","name":"comment","editable":true,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"}}
                    ],

                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',

                    ondblClickRow: function(rowid, iRow, iCol, e){
                    if(rowid && rowid!==lastsel3){
                        $(this).restoreRow(lastsel3);
                        lastsel3=rowid;
                        }
                        $(this).editRow(rowid,true);
                    },

                    loadComplete:function() {
                        $(this).find('tbody tr:odd td').css('background-color','#fbfbfb');
                        $(this).find('tbody tr:even td').css('background-color','#f6ede4');
                    },

                    gridComplete: function(){
                        var ids = jQuery("#mif").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#mif').saveRow('"+cl+"', '' , '', '', aftersavefunc, '');jQuery('#mif').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#mif').restoreRow('"+cl+"');\" />";
                            jQuery("#mif").jqGrid('setRowData',ids[i],{act:se+ce});
                        }
                    },
                    //"subGrid":true,
                    "subGridUrl":"sellerinfo",
                    "subGridModel" :[
                        {
                            name  : ['Requested Date','Category','POC Name','POC Email ID','POC Contact Number',
                                    'Requester Name','Requester Email',  'Requester Number', 'Seller Provided Images',
                                    'Rejected By'],
                            width : [200,200,200,200,150,120,120,120,160],
                            colModel: [
                                {"label":"Requested Date","index":"created_at","name":"created_at"},
                                {"label":"Category","align":"center","index":"category","name":"category"},
                                {"label":"POC Name","align":"center","index":"poc_name","name":"poc_name"},
                                {"label":"POC Email ID","align":"center","index":"poc_email","name":"poc_email"},
                                {"label":"POC Contact Number","index":"poc_number","name":"poc_number"},
                                {"label":"Requester Email","align":"center","index":"email","name":"email"},
                                {"label":"Requester Number","index":"contact_number","name":"contact_number"},
                                {"label":"Seller Provided Images","align":"center","index":"image_available","name":"image_available"},
                                {"label":"Reject By","align":"center","index":"RejectedBy","name":"RejectedBy"}
                            ]
                        }
                    ],
                }
        );
    </script>
    <!-- ./ content -->
    </div>

@stop
