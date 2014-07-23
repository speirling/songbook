SBK.PlaylistPicker = SBK.AllPlaylists.extend({
    render_response: function (response) {
        var self = this, playlist_grouped_by_act, select_container, act_index, li_act, ul_act, li_pl, pl_index, link;

        playlist_grouped_by_act = self.group_by_act(response.data);
        select_container = jQuery('<select class="playlist-chooser"></select>');
        jQuery('<option value="">Select a playlist...</option>').appendTo(select_container);
        jQuery('<option value="all">all songs</option>').appendTo(select_container);
        

        for (act_index = 0; act_index < playlist_grouped_by_act.length; act_index = act_index + 1) {
            this_act = playlist_grouped_by_act[act_index];
            for (pl_index = 0; pl_index < this_act.playlists.length; pl_index = pl_index + 1) {
                this_playlist = this_act.playlists[pl_index];
                jQuery('<option value="' + this_playlist.filename + '">' + this_act.act + ' : ' + this_playlist.title[0] + '</option>').appendTo(select_container);
            }
        }

        return select_container;
    }
});