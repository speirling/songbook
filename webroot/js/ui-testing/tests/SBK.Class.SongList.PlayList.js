/*global SBK, jQuery, document, module, deepEqual, ok, test, asyncTest, start */

jQuery(document).ready(function () {
    module('SBK.Class.SongList.PlayList');
    
    test('filter_playlist_songs', 8, function () {
        var container, test_object, playlist_filename, exclusion_list, app, playlist_container;

        playlist_filename = '';
        container = jQuery('<div class="songlist-playlist-1"></div>').appendTo(jQuery('body'));
        exclusion_list = '';
        app = {
            application_state: {
                introductions_visible_in_list: false,
                set: function () {}
            },
            container: {
                height: function () { return 0; },
                width: function () { return 0; }
            }
        };
        data_json = {
            "title":"Foundry",
            "act":"All Mixed Up",
            "introduction":{"duration":"","text":""},
            "sets":[{
                "label":"First Set",
                "introduction":{"duration":"","text":""},
                "songs":[
                     {"id":"244","key":"D","singer":"Midge","capo":"","duration":"","title":"Song One","introduction":{"duration":"","text":""}},
                     {"id":"57","key":"D","singer":"Midge","capo":"","duration":"","title":"Song Two","introduction":{"duration":"","text":""}}
                 ]
            }, {
                "label":"Second Set",
                "introduction":{"duration":"","text":""},
                "songs":[
                     {"id":"66","key":"G","singer":"","capo":"","duration":"","title":"Two One","introduction":{"duration":"","text":""}},
                     {"id":"105","key":"G","singer":"","capo":"","duration":"","title":"Two Tick Tock","introduction":{"duration":"","text":""}},
                     {"id":"107","key":"A","singer":"","capo":"","duration":"","title":"Three","introduction":{"duration":"","text":""}}
                 ]
            }]
        };
        
        test_object = new SBK.PlayList(playlist_filename, container, exclusion_list, app);
        test_object.render = function () {
            var self = this;
            
            self.data_json = data_json;
            self.redraw();
        };
        playlist_container = test_object.render();
        deepEqual(jQuery('li ol li:visible', test_object.playlist_ul).length, 5, 'every item in the list is visible');
        deepEqual(jQuery('li ol:visible', test_object.playlist_ul).length, 2, 'Both sets are visible');
        test_object.filter_playlist_songs('Three');
        deepEqual(jQuery('li ol li:visible', test_object.playlist_ul).length, 1, 'only one item is visible');
        deepEqual(jQuery('li ol:visible', test_object.playlist_ul).length, 1, 'Only one set is visible');
        test_object.filter_playlist_songs('One');
        deepEqual(jQuery('li ol li:visible', test_object.playlist_ul).length, 2, 'two items are visible');
        deepEqual(jQuery('li ol:visible', test_object.playlist_ul).length, 2, 'Both sets are visible');
        test_object.filter_playlist_songs('Two');
        deepEqual(jQuery('li ol li:visible', test_object.playlist_ul).length, 3, 'three items are visible');
        deepEqual(jQuery('li ol:visible', test_object.playlist_ul).length, 2, 'Both sets are visible');

    });
});
