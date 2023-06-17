/*global jQuery SBK alert */

SBK.PlayList.Selector = SBK.PlayList.extend({


    to_html: function (data_json) {
        var self = this, set_index, playlist_container, playlist_introduction_container, ul;

        playlist_container = jQuery('<div class="playlist playlist-selector"></div>');
        
        ul = jQuery('<ul></ul>').appendTo(playlist_container);
     
        for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
            self.set_objects[set_index] = new SBK.SongListItemSet.Selector(ul, self, set_index, data_json.sets[set_index]);
            self.set_objects[set_index].render();
        }
       
        return playlist_container;
    },


	display_content: function () {
		var self = this, button_bar;
	
		self.container.html('');
		button_bar = jQuery('<span class="button-bar"></span>').appendTo(self.container);
		self.container.append(self.to_html(self.data_json));

	},
    
    get_selected: function () {
        var self = this, set_index, song_index, set_selected_data, selected_songs = [];

        for (set_index = 0; set_index < self.set_objects.length; set_index = set_index + 1) {
           set_selected_data = self.set_objects[set_index].get_selected();
           for (song_index = 0; song_index < set_selected_data.length; song_index = song_index + 1) {
               selected_songs.push(set_selected_data[song_index]);
           }
        }
        
        return selected_songs;
    }
});