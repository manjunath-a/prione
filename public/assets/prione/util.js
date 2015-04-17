
    // After Save Event Function
aftersavefunc = function(response, postdata) {
  data = JSON.parse(postdata.responseText);

  if(!data.status) {
    $("#myMessage").removeClass( "alert alert-success" );
    $("#myMessage").addClass( "alert alert-danger" );
  }
  if(data.status == true) {
    $("#myMessage").removeClass( "alert alert-danger" );
    $("#myMessage").addClass( "alert alert-success" );
  }
  $("#myMessage").text(data.message);
  $("#myMessage").fadeIn();
  var $self = $(this);
  setTimeout(function() {
    $("#myMessage").fadeOut();
  }, 5000);
  return true;
}

function getRequestCountByStatus(statusId)
{
  GSC.doPost({
        url: "/admin/report/" + statusId + '/status/',
        async: false,
        cache: false,
        success: function (data) {
            console.log(data);
            response = data;
            $('#total_status_count').text(data.count);
        },
        error: function (e) {
            console.log('Error in genres:' + e.message);
        },
        complete: function () {
            console.log('List of Ticket');
        }
    });
}

function getRequestCountByStage(stageId)
{
  GSC.doPost({
        url: "/admin/report/" + stageId + '/stage/',
        async: false,
        cache: false,
        success: function (data) {
            console.log(data);
            response = data;
            $('#total_stage_count').text(data.count);
        },
        error: function (e) {
            console.log('Error in genres:' + e.message);
        },
        complete: function () {
            console.log('List of Ticket');
        }
    });
}

function getRequestCountByRoleWithActive(roleId)
{
  GSC.doPost({
        url: "/admin/report/" + roleId + "/role/",
        async: false,
        cache: false,
        success: function (data) {
            console.log(data);
            response = data;
            $('#total_role_count').text(data.count);
        },
        error: function (e) {
            console.log('Error in genres:' + e.message);
        },
        complete: function () {
            console.log('List of Ticket');
        }
    });
}
