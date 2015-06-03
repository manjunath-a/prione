@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
    Local Team Lead Dashboard :: @parent
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
        <div class="col-md-2">
            <span class="status-title">Pending :</span>
            <span class="status-count">10</span>
        </div>
        <div class="col-md-2">
            <span class="status-title">Not Assigned :</span>
            <span class="status-count">07</span>
        </div>
        <div class="col-md-2">
            <span class="status-title">Local WIP :</span>
            <span class="status-count">30</span>
        </div>
        <div class="col-md-2">
            <span class="status-title">Central WIP :</span>
            <span class="status-count">09</span>
        </div>
        <div class="col-md-2">
            <span class="status-title">Returned :</span>
            <span class="status-count">12</span>
        </div>
        <div class="col-md-2">
            <span class="status-title">Photoshoot Pending :</span>
            <span class="status-count">02</span>
        </div>
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
                        {"label":"Action",'name':'act','index':'act', 'width':65,'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden": true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", formoptions:{rowpos:2, colpos:5},'editable': true, 'hidden': true, 'editrules': { 'edithidden': false }},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true, 'editable': true,"hidden":true},

                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},
                        {"label":"Assigned Date","index":"created_at","name":"created_at","width":100,"align":"center",},
                        {"label":"Seller Name", "index":"merchant_name","name":"merchant_name","width":130, "align":"center"},
                        {"label":"Seller Ph#", "index":"merchant_phone","name":"merchant_phone","width":90, "align":"center"},
                        {"label":"Status","index":"status_id","align":"center","width":80, formoptions:{rowpos:1, colpos:5},"editable":true,
                        "editoptions":{'value':'{{rtrim($status, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"Stage","index":"stage_id","align":"center","width":240, formoptions:{rowpos:3, colpos:3}, "editable":true,
                        "editoptions":{'value':'{{rtrim($stage, ";")}}'}, "edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Priority","index":"priority","align":"center","width":90,formoptions:{rowpos:1, colpos:3},"editable":true,
                        "editoptions":{'value':'{{rtrim($priority, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"priority"},
                        {"label":"Pending Reason","index":"pending_reason_id","align":"center","width":220, formoptions:{rowpos:3, colpos:4}, "editable":true,
                        "editoptions":{'value':'{{rtrim($pending, ";")}}','size': '1'},
                        "edittype":"select","formatter":"select","editrules":{"required":false},"name":"pending_reason_id"},
                        {"label":"Group","index":"group_id","align":"center","width":80, formoptions:{rowpos:1, colpos:4},"editable":true,
                        "editoptions":{'value':'{{rtrim($group, ";")}}'},"edittype":"select","formatter":"select","editrules":{"required":true,"edithidden": false},"name":"group_id"},


                        {"label":"Requester Name", 'hidden' : true, "align":"center","index":"requester_name","name":"requester_name"},
                        {"label":"Request Id",'width':75,"align":"center","index":"seller_request_id","name":"seller_request_id", 'hidden' : true},
                        {"label":"localteamlead",'width':75,"align":"center","index":"localteamlead_id","name":"localteamlead_id",
                            'editable': true, 'hidden': true},
                        {"index":"image_available","name":"image_available", key:true, 'hidden' : true, 'editable': true, 'editrules': { 'edithidden': false }},

                        {"label":"Photographer", "name":"photographer_id", "index":"photographer_id", "align":"center","width":130, formoptions:{rowpos:4, colpos:3},
                        "editable":true,hidden:true, "editoptions":{'value':'{{rtrim($photographer, ";")}}',class:'dropdown-content'},"edittype":"select","formatter":"select", "id":"photographer_id","editrules":{"edithidden":true}},
                        {"label":"Photoshoot Date","index":"photoshoot_date", 'hidden' : true, "align":"center","width":150, formoptions:{rowpos:4, colpos:5},"editable":true,"name":"photoshoot_date",'formatter': "date",
                        "formatoptions": { "newformat": "Y-m-d"}, "editrules":{"date":true, "required":false, "edithidden":false}, 'editoptions': { 'dataInit' :
                        function (elem) {
                            jQuery(elem).datepicker({dateFormat:"yy-mm-dd"});}} },

                        {"label":"Photoshoot Location","index":"photoshoot_location", 'hidden' : true, "align":"center","width":150, 'formoptions':{'rowpos':4, 'colpos':4},"editable":true,
                        "editoptions":{'value':'{{rtrim($photoshootLocation, ";")}}',class:'dropdown-content'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"photoshoot_location"},
                        {"label":"S3 Path","align":"center","index":"s3_folder","name":"s3_folder",  'hidden' : true, "width":90},
                        {"label":"No. of SKUs","align":"center","index":"total_sku","name":"total_sku",  'formoptions':{rowpos:5, colpos:3},"editable":true, 'hidden' : true, "editrules":{"edithidden":true}, "width":90},
                        {"label":"No. of Images","align":"center",  'hidden' : true,  "editrules":{"required":false}, "index":"total_images", "name":"total_images", formoptions:{rowpos:5, colpos:4},"editable":true,"width":100},

                        {"label":"Service Associate","index":"mif_id", 'hidden' : true, "align":"center","width":150,  formoptions:{rowpos:6, colpos:3},"editable":true,
                        "editoptions":{'value':'{{rtrim($serviceassociates, ";")}}',class:'dropdown-content'},"edittype":"select","formatter":"select","editrules":{"required":true, "edithidden":true},"name":"mif_id"},

                        {"label":"No. of parent SKUs","index":"sa_sku", "align":"center","width":130, formoptions:{rowpos:6, colpos:4},"editable":true, 'hidden' : true, "name":"sa_sku"},
                        {"label":"No. of variations","index":"sa_variation", 'hidden' : true, "align":"center","width":100, formoptions:{rowpos:6, colpos:5},"editable":true, "editrules":{"edithidden":true},"name":"sa_variation"},
                        {"label":"Comments","align":"right","index":"comment","name":"comment", 'hidden' : true, 'formoptions':{rowpos:7, colpos:3},"editable":true ,'edittype':"textarea", 'editoptions':{'rows':"1",'cols':"30"},"editrules":{"edithidden":true}},
                        {"label":"Comments","align":"center","index":"commentLink","formatter":function() {return  "<a href='#' class='comment-popover' data-toggle='popover' data-placement='bottom' data-container='body' >comments</a>"},"formatoptions":{"target":"#","rowpos":8, "colpos":3},"name":"commentLink"}
                    ],

                    ondblClickRow: function(rowid, iRow, iCol, e){
                    if(rowid && rowid!==lastsel2){
                        $(this).restoreRow(lastsel2);
                        lastsel2=rowid;
                        }
                        $(this).editRow(rowid,true);
                    },

                    loadComplete:function() {
                        $(this).find('tbody tr:odd td').css('background-color','#fbfbfb');
                        $(this).find('tbody tr:even td').css('background-color','#f6ede4');

                    },

                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = $(this).jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' class='edit-button' type='button' value='E' onclick=\"jQuery('#locallead').editGridRow('"+cl+"',{width:'1287',closeAfterEdit:true,\n\
                                   beforeShowForm : function(formid) {$('#editmodlocallead').css('height','370');$('.dropdown-content').parent().addClass('dropdown-parent');customEditForm(formid);},\n\
                                    });\" />";
                            se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#locallead').saveRow('"+cl+"', '' , '' ,'' ,aftersavefunc, '' );jQuery('#locallead').trigger('reloadGrid');\" />";
                            ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#locallead').restoreRow('"+cl+"');\" />";
                            $(this).jqGrid('setRowData',ids[i],{act:se+ce+be});
                        }
                        $('.comment-popover').popover({
                            //trigger: 'click',
                            html: true,
                            content: function () {
                            var rowId, rowData, popOverHtml;
                            rowId = jQuery("#locallead").jqGrid('getGridParam','selrow');
                            rowData = jQuery("#locallead").jqGrid('getRowData',rowId);
                            $.ajax({
                              type:"POST",
                              url:"comments",
                              data: rowData,
                              dataType: "json",
                              success: function(data) {
                                $.each(data.allComment, function(index,value){
                                    
                                });
                              }
                            });
                            }
                        });
                    },

                         
                    // "subGrid":true,
                    // "subGridUrl":"seller",
                    // "subGridModel" :[
                    //     {
                    //         name  : ['Requested Date','Category', 'POC Name', 'POC Email', 'POC Number', 'Requester Email',  'Requester Number', 'Seller Provided Images',
                    //                 'Local Team Lead', 'Photographer', 'Service Associate' ,
                    //                 'Editing Manager', 'Editing Team Lead', 'Editor',
                    //                 'Cataloging Manager', 'Cataloging Team Lead', 'Cataloger','Rejected By'],
                    //         width : [200,200,200,150,120,120,160,120,120,120,120,120,120,120,120,120],
                    //         colModel: [
                    //             {"label":"Requested Date","index":"request_created","name":"request_created"},
                    //             {"label":"Category","align":"center","index":"category","name":"category"},
                    //             {"label":"POC Name","align":"center","index":"poc_name","name":"poc_name"},
                    //             {"label":"POC Email","align":"center","index":"poc_email","name":"poc_email"},
                    //             {"label":"POC Contact Number","index":"poc_number","name":"poc_number"},

                    //             {"label":"Requester Email","align":"center","index":"email","name":"email"},
                    //             {"label":"Requester Number","index":"contact_number","name":"contact_number"},
                    //             {"label":"Seller Provided Images","align":"center","index":"image_available","name":"image_available"},
                    //             {"label":"Local Team Lead","align":"center","index":"LocalTeamLead","name":"LocalTeamLead"},
                    //             {"label":"Photographer","align":"center","index":"Photographer","name":"Photographer"},
                    //             {"label":"Service Associate","align":"center","index":"ServiceAssociate","name":"ServiceAssociate"},
                    //             {"label":"Editing Manager","align":"center","index":"EditingManager","name":"EditingManager"},
                    //             {"label":"Editing Team Lead","align":"center","index":"EditingTeamLead","name":"EditingTeamLead"},
                    //             {"label":"Editor","align":"center","index":"Editor","name":"Editor"},
                    //             {"label":"Cataloging Manager","align":"center","index":"CatalogingManager","name":"CatalogingManager"},
                    //             {"label":"Cataloging Team Lead","align":"center","index":"CatalogingTeamLead","name":"CatalogingTeamLead"},
                    //             {"label":"Cataloger","align":"center","index":"Cataloger","name":"Cataloger"},
                    //             {"label":"Reject By","align":"center","index":"RejectedBy","name":"RejectedBy"}
                    //         ]
                    //     }
                    // ],
                }
        );
        
        function customEditForm(formId) {
            formId.html("<div>Hi</div>");
            console.log(formId);
        }
    </script>
    <!-- ./ content -->
    </div>
@stop
