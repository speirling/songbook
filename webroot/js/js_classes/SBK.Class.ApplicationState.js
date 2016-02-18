/*global CYLON, jQuery, window, document */


SBK.ApplicationState = SBK.Class.extend({

    init: function (app) {
        var self = this;

        self.callbacks = new SBK.CallbackList();

        self.app = app;

        self.initialise_state();
        
        self.tab_id = [];
        self.tab_id[0] = 'playlist_list';
        self.tab_id[1] = 'display_playlist';
        self.tab_id[2] = 'song_lyrics';
        self.tab_id[3] = 'edit_song';
        self.tab_id[4] = 'playlist_book';
        self.tab_id[5] = 'list_all_songs';
        self.tab_id[6] = 'add_new_song';
        self.tab_id[7] = 'playlist_alphabetical';
        self.tab_id[8] = 'edit_playlist';
    },
    
    initialise_state: function () {
        var self = this;

        self.tab = 'playlist_list';
        self.id = null;
        self.capo = null;
        self.playlist_filename = null;
        self.set_index = null;
        self.song_index = null;
        self.introductions_visible_in_list = false;
        self.buttons_visible_in_list = false;
        self.details_visible_in_list = false;
        self.offline = false;
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
        var self = this, hash, hash_array, valid_formats, index, previous_parameters, changed_parameters = {};

        hash = self.get_hash();
        hash_array = hash.split('&');
        valid_formats = {
            tab: /^\d+$/,
            id: /^\d+$/
        };

        //changes to buttons and details attributes should not trigger a reload
        previous_parameters = self.get_current_state();
        // Before extracting data from the hash, reset all current settings so that if for example p is not present then the playlist filename gets set to null to create a new playlist.
        self.initialise_state();
        for (index = 0; index < hash_array.length; index = index + 1) {
            split = hash_array[index].split('=');
            parameter = split[0];
            value = split[1];

            switch (parameter) {
            case 't':
                if (valid_formats.tab.test(value)) {
                    self.tab = self.tab_id[parseInt(value, 10)];
                    console.log(self.tab);
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

            case 'ti':
                self.set_index = value;
                break;

            case 'ni':
                self.song_index = value;
                break;

            case 'iv':
                self.introductions_visible_in_list = true;
                break;

            case 'bv':
                self.buttons_visible_in_list = true;
                break;

            case 'dv':
                self.details_visible_in_list = true;
                break;

            case 'offline':
                self.offline = true;
                break;
            }
        }

        property_names = [
            'tab', 
            'id', 
            'playlist_filename', 
            'key', 
            'capo', 
            'set_index', 
            'song_index', 
            'introductions_visible_in_list', 
            'buttons_visible_in_list', 
            'details_visible_in_list',
            'offline'
        ];

        for (index = 0; index < property_names.length; index = index + 1) {
            if (typeof(self[property_names[index]]) === 'undefined' || self[property_names[index]] === previous_parameters[property_names[index]]) {
                delete previous_parameters[property_names[index]];
            } else {
                changed_parameters[property_names[index]] = self[property_names[index]];
            }
        }
        
        self.run_callbacks(changed_parameters);
    },

    get_current_state: function () {
        var self = this;
        
        return {
            tab: self.tab,
            id: self.id,
            playlist_filename: self.playlist_filename,
            key: self.key,
            capo: self.capo,
            set_index: self.set_index,
            song_index: self.song_index,
            introductions_visible_in_list: self.introductions_visible_in_list,
            buttons_visible_in_list: self.buttons_visible_in_list,
            details_visible_in_list: self.details_visible_in_list,
            offline: self.offline
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

        // id
        if (SBK.StaticFunctions.undefined_to_null(desired_state.id) !== null) {
            state_string = state_string + '&id=' + desired_state.id;
        }

        // filename
        if (SBK.StaticFunctions.undefined_to_null(desired_state.playlist_filename) !== null) {
            state_string = state_string + '&p=' + desired_state.playlist_filename;
        }

        // key
        if (SBK.StaticFunctions.undefined_to_null(desired_state.key) !== null) {
            state_string = state_string + '&key=' + desired_state.key;
        }

        // capo
        if (SBK.StaticFunctions.undefined_to_null(desired_state.capo) !== null) {
            state_string = state_string + '&capo=' + desired_state.capo;
        }

        // set_index
        if (SBK.StaticFunctions.undefined_to_null(desired_state.set_index) !== null) {
            state_string = state_string + '&ti=' + desired_state.set_index;
        }

        // song_index
        if (SBK.StaticFunctions.undefined_to_null(desired_state.song_index) !== null) {
            state_string = state_string + '&ni=' + desired_state.song_index;
        }

        // introductions_visible_in_list
        if (SBK.StaticFunctions.undefined_to_null(desired_state.introductions_visible_in_list) !== null) {
            if (desired_state.introductions_visible_in_list === true) {
                state_string = state_string + '&iv';
            }
        }

        // buttons_visible_in_list
        if (SBK.StaticFunctions.undefined_to_null(desired_state.buttons_visible_in_list) !== null) {
            if (desired_state.buttons_visible_in_list === true) {
                state_string = state_string + '&bv';
            }
        }

        // details_visible_in_list
        if (SBK.StaticFunctions.undefined_to_null(desired_state.details_visible_in_list) !== null) {
            if (desired_state.details_visible_in_list === true) {
                state_string = state_string + '&dv';
            }
        }

        // offline
        if (SBK.StaticFunctions.undefined_to_null(desired_state.offline) !== null) {
            if (desired_state.offline === true) {
                state_string = state_string + '&offline';
            }
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
    
    run_callbacks: function (changed_parameters) {
        var self = this;

        self.callbacks.run(changed_parameters);
    },

    register_set_callback: function (callback) {
        var self = this;
        
        self.set_callback = callback;
    }
});