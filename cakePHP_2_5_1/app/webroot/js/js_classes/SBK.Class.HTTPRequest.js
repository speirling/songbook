/*global jQuery SBK alert */

SBK.HTTPRequest = SBK.Class.extend({
	init: function (container) {
		var self = this;

		self.api_server = '/songbook/api/';
	},

    make_post_request: function (url, data, success, failure) {
        var self = this;

        try {
            jQuery.ajax({
                cache: true,
                data: data,
                dataType: 'json',
                type: 'post',
                url: url,
                success: function (server_data, text_status, xhr) {
                    // jQuery treats 0 as success, but this is what some browsers return when the XHR failed due to a
                    // network error
                    if (xhr.status === 0) {
                        throw "Network Error";
                    } else {
                    	if(server_data === null) {
                    		throw('The server returned Null');
                    	}
                    	if(server_data.success !== false) { // could be a number!
                    		success(server_data);
                    	} else {
                    	    if (typeof(failure) === 'function') {
                    	        failure(server_data);
                    	    } else {
                    		    throw('Got a response from server, but the data indicated an error');
                    	    }
                    	}
                    }
                },
                error: function (xhr, text_status, error_thrown) {
                    throw('post to [' + url + '] gave an error condition : [' + error_thrown +']');
                }
            });
        } catch (e) {
            //
        }
    },
    
    api_call: function (api_call, data, success, failure) {
        var self = this;

        return self.make_post_request(self.api_server + api_call, data, success, failure);
    }
});