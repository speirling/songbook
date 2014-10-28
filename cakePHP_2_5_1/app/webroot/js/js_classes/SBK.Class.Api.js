/*global jQuery SBK alert */

SBK.Api = SBK.Class.extend({
    init: function (app) {
        var self = this;

        self.http_request = new SBK.HTTPRequest();
        self.app = app;
        self.playlist_list_data = self.format_all_playlist_list(app.all_playlists);
        self.playlists_indexed_by_name = self.get_playlists_indexed_by_name(app.all_playlists);
        self.allsongs_list_data = self.format_all_songs_list(app.all_songs);
        self.songs_indexed_by_id = self.get_songs_indexed_by_id(app.all_songs);
    },

    format_all_playlist_list: function (source_data) {
        var self = this, index, playlists;

        playlists = [];
        for (index = 0; index < source_data.length; index = index + 1) {
            playlists.push({
                act: {0: source_data[index]['act']},
                filename: source_data[index]['filename'],
                title: {0: source_data[index]['title']}
            });
        }
        return playlists;
    },
    
    get_playlists_indexed_by_name: function (source_data)  {
        var self = this, index, playlists_indexed_by_name;
        
        playlists_indexed_by_name = {};
        for (index = 0; index < source_data.length; index= index + 1) {
            playlists_indexed_by_name[source_data[index].name] = source_data[index];
        }

        return playlists_indexed_by_name;
    },

    format_all_songs_list: function (source_data) {
        var self = this, index, songs;

        songs = {};
        for (index = 0; index < source_data.length; index = index + 1) {
            songs[source_data[index]['id']] = source_data[index]['title'];
        }

        return songs;
    },
    
    get_songs_indexed_by_id: function (source_data)  {
        var self = this, index, songs_indexed_by_id;
        
        songs_indexed_by_id = {};
        for (index = 0; index < source_data.length; index= index + 1) {
            songs_indexed_by_id[source_data[index].id] = source_data[index];
        }

        return songs_indexed_by_id;
    },
    
    

    api_call: function (api_call, data, success, failure) {
        var self = this;

        return self[api_call](data, success, failure);
    },




    
    get_playlist: function (data, success, failure)  {
        var self = this;

        success({data: self.playlists_indexed_by_name[data.playlist_name]});
    },
    
    get_available_songs: function (data, success, failure)  {
        var self = this;

        success({data: {songs: self.allsongs_list_data}}, '', {status: 1});
        //return self.http_request.api_call('get_available_songs', data, success, failure);
    },
    
    get_all_playlists: function (data, success, failure)  {
        var self = this, index, playlists;

        success({data: {playlists: self.playlist_list_data}}, '', {status: 1});
        //return self.http_request.api_call('get_all_playlists', data, success, failure);
    },
    
    get_song: function (data, success, failure) {
        var self = this, index;

        success ({data: {Song: self.songs_indexed_by_id[data.id]}}, '', {status: 1});
        //return self.http_request.api_call('get_song', data, success, failure);
    },

    
    update_playlist: function (data, success, failure) {
        var self = this;

        return self.http_request.api_call('update_playlist', data, function (server_data) {
            success(server_data);
            self.app.render();
        }, failure);
    },
    
    update_song: function (data, success, failure) {
        var self = this;

        return self.http_request.api_call('update_song', data, function (server_data) {
            success(server_data);
            self.app.render();
        }, failure);
    }
});
