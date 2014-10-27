/*global jQuery SBK alert */

SBK.Api = SBK.Class.extend({
	init: function (all_playlists, all_songs) {
		var self = this;

		self.http_request = new SBK.HTTPRequest();
		self.all_playlists = all_playlists;
		self.all_songs = all_songs;
	},
    
    get_playlist: function (data, success, failure)  {
        var self = this, index;
        
        for (index = 0; index < self.all_playlists.length; index= index + 1) {
            if (self.all_playlists[index].name === data.playlist_name) {
                success ({data: self.all_playlists[index]}, '', {status: 1});       
            }
        }
    },
    
    update_playlist: function () {
        var self = this;

        return self.http_request.api_call('update_playlist', data, success, failure);
    },
    
    get_available_songs: function (data, success, failure)  {
        var self = this, index, songs;

        songs = {};
        for (index = 0; index < self.all_songs.length; index = index + 1) {
            songs[self.all_songs[index]['id']] = self.all_songs[index]['title'];
        }
        success({data: {songs: songs}}, '', {status: 1});
        //return self.http_request.api_call('get_available_songs', data, success, failure);
    },
    
    get_all_playlists: function (data, success, failure)  {
        var self = this, index, playlists;

        console.log(data, success);
        playlists = [];
        for (index = 0; index < self.all_playlists.length; index = index + 1) {
            playlists.push({
                act: {0: self.all_playlists[index]['act']},
                filename: self.all_playlists[index]['filename'],
                title: {0: self.all_playlists[index]['title']}
            });
        }
        success({data: {playlists: playlists}}, '', {status: 1});
        //return self.http_request.api_call('get_all_playlists', data, success, failure);
    },
    
    get_song: function (data, success, failure) {
        var self = this, index;

        for (index = 0; index < self.all_songs.length; index= index + 1) {
            if (parseInt(self.all_songs[index].id) === parseInt(data.id)) {
                success ({data: {Song: self.all_songs[index]}}, '', {status: 1});
                break;
            }
        }

        //return self.http_request.api_call('get_song', data, success, failure);
    },
    
    update_song: function (data, success, failure) {
        var self = this;

        return self.http_request.api_call('update_song', data, success, failure);
    },
    
    download_all_playlists: function (data, success, failure)  {
        var self = this;

        return self.http_request.api_call('download_all_playlists');
    },
    
    download_all_songs: function ()  {
        var self = this;

        return self.http_request.api_call('download_all_songs');
    },
    
    api_call: function (api_call, data, success, failure) {
        var self = this;

        return self[api_call](data, success, failure);
    }
});
