SBK.SongFilterList = SBK.SongList.extend({
	init: function (container) {
		var self = this;

		self.call_super(container, '#jsr-song-selector-list');
		self.fetch_parameters = {action: 'get_available_songs', search_string: ''};
	},

	display_content: function (server_data) {
		var self = this, search_form_html, onsubmit;
		
		search_form_html = '<form id="allsongsearch">' +
        '<span class="label">Filter: </span><input type="text" id="search_string" value="" />' + 
        '<span class="label">Number of songs displayed: </span><span class="number-of-records"></span>' + 
        '</form>';
		self.container.html(search_form_html + self.playlist_html);
		
		jQuery('.number-of-records', self.container).html(self.data_json.songs.length);
		jQuery('#search_string', self.container).val(self.fetch_parameters.search_string);
		
		self.filter_form = jQuery('#allsongsearch', self.container);
		onsubmit = jQuery.proxy(self.on_filter_form_submit, self);
		self.filter_form.bind('submit', onsubmit);
		self.hide_introductions(self.data_json.songs.length);
		self.set_up_context_menus();
		jQuery('.songlist', self.container).sortable({connectWith: '#playlist-holder .songlist'});
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
		var self = this, value;
		
		value = jQuery('#search_string', self.filter_form).val();
		self.fetch_parameters.search_string = value;
		self.render();
		
		return false;
	}
});