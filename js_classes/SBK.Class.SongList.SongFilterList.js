SBK.SongFilterList = SBK.SongList.extend({
	init: function (container) {
		var self = this;

		self.call_super(container, '#jsr-song-selector-list');
	},
	
	fetch: function (callback) {
		var self = this;
		
		self.pleasewait.show();
		self.http_request.api_call(
		    {action: 'get_available_songs'},
		    function (data) {
		    	callback(data);
		    	self.pleasewait.hide();
    		}
		);
	},

	display_content: function (server_data) {
		var self = this, search_form_html;
		
		search_form_html = '<form id="allsongsearch">' +
        '<span class="label">Filter: </span><input type="text" id="search_string" value="" />' + 
        '<span class="label">Number of songs displayed: </span><span class="number-of-records"></span>' + 
        '</form>';
		self.container.html(search_form_html + self.playlist_html);
		jQuery('.number-of-records', self.container).html(self.data_json.songs.length);
		self.filter_form = jQuery('#allsongsearch', self.container);
		self.filter_form.bind('submit', self.on_filter_form_submit);
		self.hide_introductions(self.data_json.songs.length);
		self.set_up_context_menus();
	},

	set_up_context_menus: function () {
		var self = this;

		jQuery('ul', self.container).sortable();
		jQuery('ul ol', self.container).sortable({
			connectWith: '.playlist ol'
		});
		jQuery('li li', self.container).contextMenu('context-menu', {
		    'show lyrics': {
		        click: function(element){ 
		        	window.open('?action=displaySong&id=' + element.attr('id').replace('id_', '') + 
	        			'&key=' + escape(element.children('.key').val()) +
	        			'&singer=' + element.children('.singer').val() +
	        			'&capo=' + element.children('.capo').val()
		        	);
		        }
		    },
		    'edit song': {
		        click: function(element){ window.open('?action=editSong&id=' + element.attr('id').replace('id_', '')); }
		    }
		});
	},

	on_filter_form_submit: function () {
		var self = this;
		
		value = jQuery('#search_string', self.filter_form).val();
		return false;
	}
});