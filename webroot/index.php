<?php
/**
 * The Front Controller for handling every request
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.2.9
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 */

// Check platform requirements
require dirname(__DIR__) . '/config/requirements.php';

// For built-in server
if (PHP_SAPI === 'cli-server') {
    $_SERVER['PHP_SELF'] = '/' . basename(__FILE__);

    $url = parse_url(urldecode($_SERVER['REQUEST_URI']));
    $file = __DIR__ . $url['path'];
    if (strpos($url['path'], '..') === false && strpos($url['path'], '.') !== false && is_file($file)) {
        return false;
    }
}
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;
use Cake\Http\Server;

/* Songbook configuration */
Cake\Core\Configure::write(
    'Songbook', [
        'playlist_directory' => '/fileserver/data/playlists',
        'js_library' => [
            //'js_dependencies/jquery-1.10.2.js',
            'js_dependencies/jquery-3.7.0.min.js',
            'js_dependencies/jquery-ui-1.10.4.custom.min.js',
            'js_dependencies/jsrender.min.js',
            //'js_dependencies/select2-4.0.3/dist/js/select2.min.js',
            'js_dependencies/select2-4.1.0/select2.min.js',
            'js_classes/SBK.Namespace.js',
            'js_classes/SBK.Constants.js',
            'js_classes/SBK.StaticFunctions.js',
            'js_classes/SBK.StaticFunctions.LyricChordHTML.js',
            'js_classes/SBK.CakeUI.js',
            'js_classes/SBK.Class.js',
            //'js_classes/SBK.Class.CallbackList.js',
            //'js_classes/SBK.Class.ApplicationState.js',
            //'js_classes/SBK.Class.PleaseWait.js',
            'js_classes/SBK.Class.Button.js',
            'js_classes/SBK.Class.ChordEditor.js',
            //'js_classes/SBK.Class.HTTPRequest.js',
            //'js_classes/SBK.Class.Api.js',
            //'js_classes/SBK.Class.SongList.js',
            //'js_classes/SBK.Class.SongList.PlayList.js',
            //'js_classes/SBK.Class.SongList.PlayList.Alphabetical.js',
            //'js_classes/SBK.Class.SongList.PlayList.Book.js',
            //'js_classes/SBK.Class.SongList.PlayList.Edit.js',
            //'js_classes/SBK.Class.SongList.PlayList.Selector.js',
            //'js_classes/SBK.Class.SongList.PlayList.Table.js',
            //'js_classes/SBK.Class.SongList.PlayList.Text.js',
            //'js_classes/SBK.Class.SongList.SongFilterList.js',
            //'js_classes/SBK.Class.SongList.SongFilterList.Lyrics.js',
            //'js_classes/SBK.Class.SongPicker.js',
            //'js_classes/SBK.Class.AllPlaylists.js',
            //'js_classes/SBK.Class.AllPlaylists.PlaylistPicker.js',
            //'js_classes/SBK.Class.PaginatedHTML.js',
            //'js_classes/SBK.Class.SongbookApplication.js',
            //'js_classes/SBK.Class.SongListItemSet.js',
            //'js_classes/SBK.Class.SongListItemSet.Edit.js',
            //'js_classes/SBK.Class.SongListItemSet.Selector.js',
            //'js_classes/SBK.Class.SongListItemSet.Print.js',
            //'js_classes/SBK.Class.SongListItemSet.Book.js',
           // 'js_classes/SBK.Class.SongListItemSong.js',
            //'js_classes/SBK.Class.SongListItemSong.Edit',
            //'js_classes/SBK.Class.SongListItemSong.Selector.js',
            //'js_classes/SBK.Class.SongListItemSong.Lyrics.js',
            //'js_classes/SBK.Class.SongListItemSong.Print.js',
            //'js_classes/SBK.Class.SongListItemSong.Book.js',
            //'js_classes/SBK.Class.SongLyricsDisplay.js',
            //'js_classes/SBK.Class.SongLyricsDisplay.Book.js',
            //'js_classes/SBK.Class.SongLyricsEdit.js',
            'index.js'
        ],
        'css_library' => [
        	//'../js/js_dependencies/select2-4.0.3/dist/css/select2.min.css',
            '../js/js_dependencies/select2-4.1.0/select2.min.css',
            'common.css',
            'playlists-list.css',
            'songs-list.css',
            //'playlist-book.css',
            //'playlist-edit.css',
            //'playlist-print.css',
            'song-edit.css',
            'song-lyrics.css',
            'baked_ui.css',
            'chord_editor.css',
            'viewer.css',
            'printable.css'
        ],
        'print_page' => [
            'A4' => [
                "page_height" => 1000, //px
                "page_width" => 690, //px
            ],
        ],
        'print_size' => [
            'default' => [
                "font_sizes" => [
                    "lyrics" => 16, //px
                    "chords" => 12, //px
                    "title" => 27, //px
                    "attributions" =>  10 //px
                ],
                "lyric_width_per_100_characters" => 670, //px
                "content_padding" => 30, //px  empirically this value makes the content appear on all browser sizes without scrolling. Sometimes too much of a border, but better than having to scroll to see something.
                "lyric_line_top_margin" => 8, //px
                "font_family" => 'verdana',
                "column_width" => [
                    "1_column" => 100, //characters
                    "2_column" => 45 //characters
                ]
            ]
         ]
    ]
);
/* end Songbook configuration */

// Bind your application to the server.
$server = new Server(new Application(dirname(__DIR__) . '/config'));

// Run the request/response through the application and emit the response.
$server->emit($server->run());
