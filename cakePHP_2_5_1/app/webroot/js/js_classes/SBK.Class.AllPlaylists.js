SBK.AllPlaylists = SBK.Class.extend({
	init: function (container, app) {
		var self = this;

		self.container = container;
		self.app = app; 
		self.pleasewait = new SBK.PleaseWait(self.container);
		self.http_request = new SBK.HTTPRequest();
	},
    
    render: function (callback) {
        var self = this, all_playlists;

        self.container.html('');
        jQuery('<h1>List of playlists:</h1>').appendTo(self.container);
        self.pleasewait.show();
        self.http_request.api_call(
            'get_all_playlists',
            {},
            function (response) {
                all_playlists = self.render_response(response).appendTo(self.container);
                if(typeof(callback) === 'function') {
                    callback(all_playlists);
                }
                self.pleasewait.hide();
            }
        );
    },
    
    render_response: function (response) {
        var self = this, playlist_grouped_by_act, ul, act_index, li_act, ul_act, li_pl, pl_index, link;

        playlist_grouped_by_act = self.group_by_act(response.data);
        ul = jQuery('<ul class="all_playlists"></ul>');

        for (act_index = 0; act_index < playlist_grouped_by_act.length; act_index = act_index + 1) {
            this_act = playlist_grouped_by_act[act_index];
            li_act = jQuery('<li>' + this_act.act + '</li>').appendTo(ul);
            ul_act = jQuery('<ul></ul>').appendTo(li_act);
            for (pl_index = 0; pl_index < this_act.playlists.length; pl_index = pl_index + 1) {
                this_playlist = this_act.playlists[pl_index];
                li_pl = jQuery('<li></li>').appendTo(ul_act);
                link = jQuery('<a>' + this_playlist.title[0] + '</a>').appendTo(li_pl);
                link.click(
                    self.create_display_playlist_function(this_playlist.filename)
                );
            }
        }

        return ul;
    },
    
    create_display_playlist_function: function (filename) {
        var self = this;
   
        return function () {
            self.app.display_playlist(filename);
        };
    },

	group_by_act: function (data) {
		var self = this, index, playlist_grouped_by_act = [], act, act_index = {};

		for (index = 0; index < data.playlists.length; index = index + 1) {
		    if(data.playlists[index].act === null) {
                act = 'unknown';
            } else if(typeof(data.playlists[index].act) === 'undefined') {
                act = 'unknown';
            } else if(typeof(data.playlists[index].act[0]) === 'undefined') {
                act = 'unknown';
            } else if (data.playlists[index].act[0] === '') {
                act = 'unknown';
            } else {
                act = data.playlists[index].act[0];
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