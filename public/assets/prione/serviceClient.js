/*Usage*/
/*GSC.doGet({
  url: 'service URL',
  data: data,
  success: function(data){
    // On Ajax Success
  },
  error: function(e){
    // Error handle
    console.log('Error Getting Artist:' + e.message);
  },
  complete: function(){
    // Complelte + Erro
  }
});*/

(function () {

	var httpRex = /^[https?]\:/;

    var GSC = function (params) {
        return this;
    };

    _buildUrl = function (url) {
    	if (httpRex.test(url)) return url;
      return (baseURL) + url;
    },

    _ajaxCall = function (method, params) {
    	var /*sessionId =  $.cookie("session_id"),*/ // Get the session cookie
        	url = _buildUrl(params.url);
        	dataToSend = params.data;

      console.log('Called ajaxCall with method "%s" and url "%s"',
      method, url,dataToSend);

      	$.ajax({
      		type: method || 'GET',
			url: url,
			//data: dataToSend ? JSON.stringify(dataToSend) : null,
                        data:dataToSend,
			contentType: 'application/json',
			dataType: 'json',
      async: params.async,
      		beforeSend: function(xhr) {
      			// xhr.setRequestHeader('session-id', sessionId);
      		},
      		success: function (data) {
				params.success(data);
                                console.log("success");
			},
			error: function (xhr) {
				params.error(JSON.parse(xhr.responseText));
                                console.log(xhr+"error");
			},
			complete: function () {
				params.complete();
			}
		});
    },

  	GSC.doGet = function (params) {
        return _ajaxCall('GET',params);
    },

    GSC.doPost = function (params) {
      return _ajaxCall('POST', params);
    },

    GSC.doDelete = function (params) {
      return _ajaxCall('DELETE', params);
    },

    GSC.doPut = function (params) {
      return _ajaxCall('PUT', params);
    },

    GSC.doHead = function (params) {
      return _ajaxCall('HEAD', params);
    };

    if(!window.GSC) {
        window.GSC = GSC;
    }
})();