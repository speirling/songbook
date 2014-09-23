/*global jQuery SBK alert */

SBK.Api = SBK.Class.extend({
	init: function () {
		var self = this;

		self.http_request = new SBK.HTTPRequest();
		self.all_playlists = jQuery.getJSON('../local_data/all_playlists.json');
		self.all_songs = jQuery.getJSON('../local_data/all_songs.json');
	},
    
    get_playlist: function (data, success, failure)  {
        var self = this;

        return self.http_request.api_call('get_playlist', data, success, failure);
    },
    
    update_playlist: function () {
        var self = this;

        return self.http_request.api_call('update_playlist', data, success, failure);
    },
    
    get_available_songs: function (data, success, failure)  {
        var self = this;

        return self.http_request.api_call('get_available_songs', data, success, failure);
    },
    
    get_all_playlists: function (data, success, failure)  {
        var self = this;

        return self.http_request.api_call('get_all_playlists', data, success, failure);
    },
    
    get_song: function (data, success, failure) {
        var self = this;

        return self.http_request.api_call('get_song', data, success, failure);
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