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
		    	playlist_grouped_by_act = {acts: self.group_by_act(response.data)};
		    	console.log(playlist_grouped_by_act);
		    	all_playlists = jQuery(self.template.render(playlist_grouped_by_act)).appendTo(self.container);
		    	if(typeof(callback) === 'function') {
		    		callback(all_playlists);
		    	}
		    	self.pleasewait.hide();
    		}
		);
	},

	group_by_act: function (data) {
		var self = this, index, playlist_grouped_by_act = [], act, act_index = {};

		for (index = 0; index < data.playlists.length; index = index + 1) {
			act = data.playlists[index].act;
			if(act === '') {
				act = 'unknown';
			}
			if(typeof(act_index[act]) === 'undefined') {
				act_index[act] = playlist_grouped_by_act.length;
				playlist_grouped_by_act[act_index[act]] = {act: act, playlists: [data.playlists[index]]};
			} else {
				playlist_grouped_by_act[act_index[act]].playlists.push(data.playlists[index]);
			}
		}
		return  playlist_grouped_by_act;
	}
});