SBK.AllPlaylists = SBK.Class.extend({
	init: function (container) {
		var self = this;

		self.container = container;
		self.template = self.get_template();
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.http_request = new SBK.HTTPRequest();
	},
	
	get_template: function () {
		return jQuery('#jsr-all-playlists');
	},
	
	render: function (callback) {
		var self = this, all_playlists;
		
		self.pleasewait.show();
		self.http_request.api_call(
		    {action: 'get_available_playlists'},
		    function (response) {
		    	all_playlists = jQuery(self.template.render(response.data)).appendTo(self.container);
		    	if(typeof(callback) === 'function') {
		    		callback(all_playlists);
		    	}
		    	self.pleasewait.hide();
    		}
		);
	}
});