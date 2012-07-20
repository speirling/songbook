SBK.AllPlaylists = SBK.Class.extend({
	init: function (container) {
		var self = this;

		self.container = container;
		self.template = jQuery('#jsr-all-playlists');
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.http_request = new SBK.HTTPRequest();
	},
	
	render: function () {
		var self = this;
		
		self.pleasewait.show();
		self.http_request.api_call(
		    {action: 'get_available_playlists'},
		    function (response) {
		    	jQuery(self.template.render(response.data)).appendTo(self.container);
		    	self.pleasewait.hide();
    		}
		);
	}
});