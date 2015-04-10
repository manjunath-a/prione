
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