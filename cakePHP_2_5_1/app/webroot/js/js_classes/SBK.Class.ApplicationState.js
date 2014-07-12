/*global CYLON, jQuery, window, document */


SBK.ApplicationState = SBK.Class.extend({

    init: function (app) {
        var self = this;

        self.callbacks = new SBK.CallbackList();

        self.app = app;

        self.tab = 'playlist_list';
        self.id = null;
        self.capo = null;
        self.playlist_filename = null;
        self.set_index = null;
        self.song_index = null;
        
        self.tab_id = [];
        self.tab_id[0] = 'playlist_list';
        self.tab_id[1] = 'edit_playlist';
        self.tab_id[2] = 'song_lyrics';
        self.tab_id[3] = 'edit_song';
        self.tab_id[4] = 'playlist_book';
    },
    
    start_handling_hashchange: function () {
        var self = this;
        
        jQuery(window).bind('hashchange', function () {
            self.update_from_hash();
        });
    },
    
    stop_handling_hashchange: function () {
        var self = this;
    
        jQuery(window).unbind('hashchange');
    },
    
    startup: function () {
        var self = this;

        self.start_handling_hashchange();
        self.update_from_hash();
    },
    
    get_hash: function () {
        var self = this;
    
        return window.location.hash.substr(1);
    },
    
    set_hash: function (hash) {
        var self = this;

        window.location.hash = hash;
    },
    
    update_from_hash: function () {
        var self = this, index;

        hash = self.get_hash();

        hash_array = hash.split('&');

        valid_formats = {
            tab: /^\d+$/,
            id: /^\d+$/,            
        };

        for (index = 0; index < hash_array.length; index = index + 1) {
            split = hash_array[index].split('=');
            parameter = split[0];
            value = split[1];

            
            switch (parameter) {
            case 't':
                if (valid_formats.tab.test(value)) {
                    self.tab = self.tab_id[parseInt(value, 10)];
                }
                break;

            case 'id':
                if (valid_formats.id.test(value)) {
                    self.id = parseInt(value, 10);
                }
                break;

            case 'p':
                self.playlist_filename = value;
                break;

            case 'key':
                self.key = value;
                break;

            case 'capo':
                self.capo = value;
                break;
            }
        }

        self.run_callbacks();
    },

    get_current_state: function () {
        var self = this;
        
        return {
            tab: self.tab,
            id: self.id,
            playlist_filename: self.playlist_filename,
            key: self.key,
            capo: self.capo
        };
    },

    set: function (new_state, new_window) {
        var self = this, desired_state, state_string, index;

        desired_state = jQuery.extend({}, self.get_current_state(), new_state);
        self.store_back_state();

        // Tab
        for (index = 0; index < self.tab_id.length; index = index + 1) {
            if (self.tab_id[index] === desired_state.tab) {
                state_string = 't=' + index;
            }
        }
;
        // id
        if (SBK.StaticFunctions.undefined_to_null(desired_state.id) !== null) {
            state_string = state_string + '&id=' + desired_state.id;
        }

        // filename
        if (SBK.StaticFunctions.undefined_to_null(desired_state.filename) !== null) {
            state_string = state_string + '&p=' + desired_state.filename;
        }

        // key
        if (SBK.StaticFunctions.undefined_to_null(desired_state.key) !== null) {
            state_string = state_string + '&key=' + desired_state.key;
        }

        // capo
        if (SBK.StaticFunctions.undefined_to_null(desired_state.capo) !== null) {
            state_string = state_string + '&capo=' + desired_state.capo;
        }

        if (new_window === true) {
            window.open('#' + state_string);
        } else {
            if (self.get_hash() !== state_string) {
                self.set_hash(state_string);
            }
        }
    },
    
    store_back_state: function (callback) {
        var self = this;
        
        self.back_state = self.get_current_state();
    },
    
    back: function (callback) {
        var self = this;
        
        self.set(self.back_state);
    },
    
    register_callback: function (callback) {
        var self = this;
        
        self.callbacks.register(callback);
    },
    
    run_callbacks: function () {
        var self = this;

        self.callbacks.run();
    },

    register_set_callback: function (callback) {
        var self = this;
        
        self.set_callback = callback;
    }
});