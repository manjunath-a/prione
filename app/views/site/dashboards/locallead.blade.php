@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
    Local Team Lead Dashboard :: @parent
@stop

{{-- New Laravel 4 Feature in use --}}
@section('styles')
    @parent
    body {
    background: #fff;
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
        var lastsel2, commentHtml;
        jQuery("#locallead").jqGrid({
                    "datatype":"json",
                    "mtype":"POST",
                    "url":"locallead",
                    "editurl":'/request/update',
                    "rowNum":25,
                    "height": 440,
                    "resizable":false,
                    "viewrecords":true,
                    "colModel":[
                        {"label":"Action",'name':'act','index':'act', 'width':70,'sortable':false},
                        {"name":"id", "index":"id", "align":"center", "hidden": true},
                        {"name":"transaction_id", "index":"transaction_id", "align":"center", formoptions:{rowpos:2, colpos:5},'editable': true, 'hidden': true, 'editrules': { 'edithidden': false }},
                        {'name':'ticket_id', 'index':'ticket_id','align':'center', 'key':true, 'editable': true,"hidden":true},

                        {'label':'Ticket ID', 'name':'ticket_id', 'index':'ticket_id', 'width':65, 'align':'center'},
                        {"label":"Assigned Date","index":"created_at","name":"created_at","width":140,"align":"center",},
                        {"label":"Seller Name", "index":"merchant_name","name":"merchant_name","width":130, "align":"center"},
                        {"label":"Seller Ph#", "index":"merchant_phone","name":"merchant_phone","width":108, "align":"center"},
                        {"label":"Status","index":"status_id","align":"center","width":70, formoptions:{rowpos:1, colpos:5},"editable":true,
                        "editoptions":{'value':'{{rtrim($status, ";")}}',class:'dropdown-content'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"status_id"},
                        {"label":"Stage","index":"stage_id","align":"center","width":240, formoptions:{rowpos:3, colpos:3}, "editable":true,
                        "editoptions":{'value':'{{rtrim($stage, ";")}}',class:'dropdown-content'}, "edittype":"select","formatter":"select","editrules":{"required":true},"name":"stage_id"},
                        {"label":"Priority","index":"priority","align":"center","width":65,formoptions:{rowpos:1, colpos:3},"editable":true,
                        "editoptions":{'value':'{{rtrim($priority, ";")}}',class:'dropdown-content'},"edittype":"select","formatter":"select","editrules":{"required":true},"name":"priority"},
                        {"label":"Pending Reason","index":"pending_reason_id","align":"center","width":210, formoptions:{rowpos:3, colpos:4}, "editable":true,
                        "editoptions":{'value':'{{rtrim($pending, ";")}}','size': '1',class:'dropdown-content'},
                        "edittype":"select","formatter":"select","editrules":{"required":false},"name":"pending_reason_id"},
                        {"label":"Group","index":"group_id","align":"center","width":80, formoptions:{rowpos:1, colpos:4},"editable":true,
                        "editoptions":{'value':'{{rtrim($group, ";")}}',class:'dropdown-content'},"edittype":"select","formatter":"select","editrules":{"required":true,"edithidden": false},"name":"group_id"},


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
                        {"label":"Comments","align":"center","index":"commentLink",
                            "formatter":function() {
                            return  "<a href='javascript:;' class='comment-popover' data-toggle='popover' data-placement='left' data-container='body' >comments</a>"},
                            "formatoptions":{"target":"#","rowpos":8, "colpos":3}, "name":"commentLink"},
                        {"label":"Merchant Name :", "index":"merchant_name","name":"merchant_name","width":130, "align":"center",formoptions:{rowpos:1, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"POC Name :", "index":"poc_name","name":"poc_name","width":130, "align":"center",formoptions:{rowpos:1, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Merchant Email :", "index":"merchant_email","name":"merchant_email","width":130, "align":"center",formoptions:{rowpos:3, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"POC Email :", "index":"poc_email","name":"poc_email","width":130, "align":"center",formoptions:{rowpos:3, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"City :", "index":"city","name":"city","width":130, "align":"center",formoptions:{rowpos:4, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"POC Contact# :", "index":"merchant_phone","name":"merchant_phone","width":130, "align":"center",formoptions:{rowpos:4, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Requester Name :", "index":"requester_name","name":"requester_name","width":130, "align":"center",formoptions:{rowpos:6, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Category :", "index":"category_name","name":"category_name","width":130, "align":"center",formoptions:{rowpos:6, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Requester email :", "index":"email","name":"email","width":130, "align":"center",formoptions:{rowpos:7, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Requester Contact# :", "index":"requester_no","name":"requester_no","width":130, "align":"center",formoptions:{rowpos:8, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Images avialable :", "index":"image_avail","name":"image_avail","width":130, "align":"center",formoptions:{rowpos:8, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"ETL Name :", "index":"etl_name","name":"etl_name","width":130, "align":"center",formoptions:{rowpos:11, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"CTL Name :", "index":"ctl_name","name":"ctl_name","width":130, "align":"center",formoptions:{rowpos:11, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Editor Name :", "index":"editor_name","name":"editor_name","width":130, "align":"center",formoptions:{rowpos:12, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Cataloger Name :", "index":"cataloger_name","name":"cataloger_name","width":130, "align":"center",formoptions:{rowpos:12, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Assigned on:", "index":"etl_assigned_on","name":"etl_assigned_on","width":130, "align":"center",formoptions:{rowpos:13, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Assigned on:", "index":"ctl_assigned_on","name":"ctl_assigned_on","width":130, "align":"center",formoptions:{rowpos:13, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Reason for rejection:", "index":"etl_rejection_reason","name":"etl_rejection_reason","width":130, "align":"center",formoptions:{rowpos:14, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Reason for rejection:", "index":"ctl_rejection_reason","name":"ctl_rejection_reason","width":130, "align":"center",formoptions:{rowpos:14, colpos:2},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
                        {"label":"Sales channel:", "index":"sale_channel","name":"sale_channel","width":130, "align":"center",formoptions:{rowpos:5, colpos:1},
                        edittype: 'custom', editoptions: { custom_element: customLabel, custom_value: customLabelValue}, "editable":true},
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
                        /*$('.EditTable tr td.CaptionTD').each(function(){$(this).replaceWith('<div>'+$(this).text()+'</div>')});*/
                        
                    },

                    jsonReader: { repeatitems : true, id: 'id' },
                    sortname: 'id',
                    gridComplete: function(){
                        var ids = $(this).jqGrid('getDataIDs');
                        for(var i=0;i < ids.length;i++)
                        {
                            var cl = ids[i];
                            be = "<input style='height:22px;width:20px;' class='edit-button' type='button' value='E' onclick=\"jQuery('#locallead').editGridRow('"+cl+"',{width:'1294',top:'35',left:'29',closeAfterEdit:true,\n\
                                   beforeShowForm : function(formid) {$('#editmodlocallead').css('height','542');$('.dropdown-content').parent().addClass('dropdown-parent');$('.comments-content').parent().attr('colspan','3');$('.EditTable tr td.CaptionTD').each(function(){if($(this).text().length <= 1){$(this).remove();}});$('.EditTable tr td.DataTD').each(function(){if(($(this).text().length <= 1)&&((!($(this).children().is('textarea')))&&(!($(this).children().is('input'))))){$(this).remove();}});$('.EditTable tr td.CaptionTD').each(function(){$(this).replaceWith('<div><div>'+$(this).html()+'</div>');});customEditForm(formid);},\n\
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
                            var divId =  "tmp-id-" + $.now();
                            return getAllComment(rowData, divId);
                        }
                        });
                    },

                }
        );
            function customLabel(value, options) {
              var el = document.createElement("label");
              console.log(el);
              el.innerText = value;
              return el;
            }
            function customLabelValue(elem) {
              return $(elem).val();
            }


        
        function customEditForm(formId) {
        }
            /*console.log(formId);
            var complete_html="",splitted_html;
            $.each( formId[0], function( key, value ) {
                splitted_html = "<div class='col-md-4'><label class='col-md-12 CaptionTD'>"+ value.id +"</label><div class='col-md-12 DataTD'>" + value.outerHTML+"</div></div>";
                complete_html=complete_html+splitted_html;
            });
            formId.html(complete_html);*/

        function getAllComment(rowData, divId) {
            $.ajax({
                        type:"POST",
                        url:"comments",
                        data: rowData,
                        dataType: "json",
                        success: function(data) {
                          var commentHtml = '<table width="300px">';
                          $.each(data.allComment, function(index, row){
                              commentHtml += '<tr><td>' + row.name + '</td>';
                              commentHtml += '<td>' + row.created_at + '</td>';
                              commentHtml += '<td>' + row.comment + '</td></tr>';
                          });
                          commentHtml += '</table>';
                          $('#'+divId).html(commentHtml);
                        }
                      });
            return '<div id="'+ divId +'">Loading...</div>';
        }
    </script>
    <!-- ./ content -->
    </div>
@stop
