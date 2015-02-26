SBK.PlayListPrint = SBK.PlayList.extend({

    to_html: function (data_json) {
        var self = this, set_index, playlist_container, ul;

        playlist_container = jQuery('<div class="playlist print"></div>');
        jQuery('<span class="playlist-title">' + self.value_or_blank(data_json.title) + '</span>').appendTo(playlist_container);
        jQuery('<span class="act">' + self.value_or_blank(data_json.act) + '</span>').appendTo(playlist_container);

        self.introduction_container = jQuery('<span class="introduction songlist" style="display: none"></span>').appendTo(playlist_container);
        
        jQuery('<span class="introduction_text" placeholder="Introduction text">' + self.value_or_blank(data_json.introduction.text) + '</span>').appendTo(self.introduction_container);
        jQuery('<span class="introduction_duration" placeholder="Introduction duration" value="' + self.value_or_blank(data_json.introduction.duration) + '"></span>').appendTo(self.introduction_container);
        
        ul = jQuery('<ul></ul>').appendTo(playlist_container);
     
        for (set_index = 0; set_index < data_json.sets.length; set_index = set_index + 1) {
            /* WORKAROUND:: simplexml converts a single song into an object, not an array. make sure data_json.sets[set_index].songs is an array!!! */
            data_json.sets[set_index].songs = [].concat(data_json.sets[set_index].songs);
            
            self.set_objects[set_index] = new SBK.SongListItemSetPrint(ul, self, set_index, data_json.sets[set_index]);
            self.set_objects[set_index].render();
        }
       
        return playlist_container;
    },

    display_content: function () {
        var self = this, button_bar;
    
        self.container.html('');
        self.container.append(self.to_html(self.data_json));

        self.hide_introductions();
    }
});