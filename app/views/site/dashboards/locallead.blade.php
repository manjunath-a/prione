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
    </div><div id="myMessage"> </div>
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
//        $(function() {
//            // setTimeout() function will be fired after page is loaded
//            // it will wait for 5 sec. and then will fire
//            // $("#myMessage").hide() function
//            setTimeout(function() {
//                $("#myMessage").hide();
//            }, 5000);
//        });
        var lastsel3;
//         // define handler function for 'afterSubmit' event.
//        var deleteMessage = function(response,postdata){
//            var json   = response.responseText; // response text is returned from server.
//            var result = JSON.parse(json); // convert json object into javascript object.
//            console.log(result.status);
//            return [result.status,result.message,null];
//        }

//        function isError(text) {alert(text);
//            if(text.indexOf('ERROR') >= 0) {
//                return [false, text];
//            }
//            return [true,''];
//        }
        aftersavefunc = function(response,postdata) {
            data = JSON.parse(postdata.responseText);
//            alert(data.status);
//            alert(data.message);
//
           // alert(JSON.stringify(data));
            if(data.status == false)
            {
//                alert(data.status);
                $("#myMessage").addClass( "alert alert-error" );
            } else if(data.status == true) {
//                alert(data.status);
                $("#myMessage").addClass( "alert alert-warning" );
            }
            $("#myMessage").text(data.message);
            //$("#myMessage").text(JSON.stringify(postdata));
            var $self = $(this);
            setTimeout(function () {
                $self.trigger("reloadGrid");
            }, 50);
            setTimeout(function() {
                $("#myMessage").hide();
            }, 5000);
            return true;
//            var json   = response.responseText; // response text is returned from server.
//            var result = JSON.parse(json); // convert json object into javascript object.
//            console.log(result.status);
//            return [result.status,result.message,null];
        }

//        onErrorHandler = function(response,postdata) {
//            alert(JSON.stringify(response));
//            alert(JSON.stringify(postdata));
////            alert(JSON.stringify(error));
//            var $self = $(this);
//            setTimeout(function () {
//                $self.trigger("reloadGrid");
//            }, 50);
//            return true;
////            var json   = response.responseText; // response text is returned from server.
////            var result = JSON.parse(json); // convert json object into javascript object.
////            console.log(result.status);
////            return [result.status,result.message,null];
//        }
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
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id", 'hidden' : true},
                        {"label":"localteamlead",'width':75,"align":"center","index":"localteamlead_id","name":"localteamlead_id",
                            'editable': true, 'hidden': true, 'editrules': { 'edithidden': true }},
                        {"index":"image_available","name":"image_available", key:true, 'hidden' : true, 'editable': true, 'editrules': { 'edithidden': true }},

                        {"label":"Request / Assigned Date","align":"center","index":"created_at","name":"created_at","width":160},
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
                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = jQuery("#locallead").jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#locallead').editRow('"+cl+"');\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#locallead').saveRow('"+cl+"', '' , '' ,'' ,aftersavefunc, '' );jQuery('#locallead').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#locallead').restoreRow('"+cl+"');\" />";
                            jQuery("#locallead").jqGrid('setRowData',ids[i],{act:be+se+ce});
                        }
                    },
                    "subGrid":true,
                    "subGridUrl":"seller",
                    "subGridModel" :[
                        {
                            name  : ['Seller name','POC Name', 'POC Email ID', 'POC Contact Number', 'Seller provider Image'],
                            width : [250,150,120,180,160],
                            colModel: [
                                {"label":"Seller Name","align":"center","index":"merchant_name","editable":true,"name":"merchant_name"},
                                {"label":"POC Name","align":"center","index":"poc_name","name":"poc_name"},
                                {"label":"POC Email ID","align":"center","index":"poc_email","name":"poc_email"},
                                {"label":"POC Contact Number","index":"poc_number","name":"poc_number"},
                                {"label":"Seller provider Image","align":"center","index":"image_available","name":"image_available"}
                            ]
                        }
                    ],
//                    reloadAfterSubmit:true,
//                    afterSubmitEvent:function(response, postdata) {alert("it Works");
//                        return isError(response.responseText);
//
//                    },
                    "pager":"localleadPager"
                    //'cellEdit': true
                }
        );
       jQuery("#locallead").jqGrid('navGrid', '#localleadPager',
        {add: false,edit:false,view:false,del:false,refresh: true,search:false});

        //saveRow (rowid, onSuccessHandler , url, extraparam, aftersavefunc, onErrorHandler);
    </script>
    <!-- ./ content -->
    </div>

@stop