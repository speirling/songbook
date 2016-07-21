<?php
/**
 * The Front Controller for handling every request
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
// for built-in server
if (php_sapi_name() === 'cli-server') {
    $_SERVER['PHP_SELF'] = '/' . basename(__FILE__);

    $url = parse_url(urldecode($_SERVER['REQUEST_URI']));
    $file = __DIR__ . $url['path'];
    if (strpos($url['path'], '..') === false && strpos($url['path'], '.') !== false && is_file($file)) {
        return false;
    }
}
require dirname(__DIR__) . '/config/bootstrap.php';

use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Routing\DispatcherFactory;


/* Songbook configuration */
Cake\Core\Configure::write(
    'Songbook', array(
        'playlist_directory' => '/fileserver/data/playlists',
        'js_library' => array(
            'js_dependencies/jquery-1.10.2.js',
            'js_dependencies/jquery-ui-1.10.4.custom.min.js',
            'js_dependencies/jsrender.min.js',
            'js_dependencies/select2-4.0.3/dist/js/select2.min.js',
            'js_classes/SBK.Namespace.js',
            'js_classes/SBK.Constants.js',
            'js_classes/SBK.StaticFunctions.js',
            'js_classes/SBK.StaticFunctions.LyricChordHTML.js',
            'js_classes/SBK.Class.js',
            'js_classes/SBK.Class.CallbackList.js',
            'js_classes/SBK.Class.ApplicationState.js',
            'js_classes/SBK.Class.PleaseWait.js',
            'js_classes/SBK.Class.Button.js',
            'js_classes/SBK.Class.ChordEditor.js',
            'js_classes/SBK.Class.HTTPRequest.js',
            'js_classes/SBK.Class.Api.js',
            'js_classes/SBK.Class.SongList.js',
            'js_classes/SBK.Class.SongList.PlayList.js',
            'js_classes/SBK.Class.SongList.PlayList.Alphabetical.js',
            'js_classes/SBK.Class.SongList.PlayList.Book.js',
            'js_classes/SBK.Class.SongList.PlayList.Edit.js',
            'js_classes/SBK.Class.SongList.PlayList.Selector.js',
            'js_classes/SBK.Class.SongList.PlayList.Table.js',
            'js_classes/SBK.Class.SongList.PlayList.Text.js',
            'js_classes/SBK.Class.SongList.SongFilterList.js',
            'js_classes/SBK.Class.SongList.SongFilterList.Lyrics.js',
            'js_classes/SBK.Class.SongPicker.js',
            'js_classes/SBK.Class.AllPlaylists.js',
            'js_classes/SBK.Class.AllPlaylists.PlaylistPicker.js',
            'js_classes/SBK.Class.PaginatedHTML.js',
            'js_classes/SBK.Class.SongbookApplication.js',
            'js_classes/SBK.Class.SongListItemSet.js',
            'js_classes/SBK.Class.SongListItemSet.Edit.js',
            'js_classes/SBK.Class.SongListItemSet.Selector.js',
            'js_classes/SBK.Class.SongListItemSet.Print.js',
            'js_classes/SBK.Class.SongListItemSet.Book.js',
            'js_classes/SBK.Class.SongListItemSong.js',
            'js_classes/SBK.Class.SongListItemSong.Edit',
            'js_classes/SBK.Class.SongListItemSong.Selector.js',
            'js_classes/SBK.Class.SongListItemSong.Lyrics.js',
            'js_classes/SBK.Class.SongListItemSong.Print.js',
            'js_classes/SBK.Class.SongListItemSong.Book.js',
            'js_classes/SBK.Class.SongLyricsDisplay.js',
            'js_classes/SBK.Class.SongLyricsDisplay.Book.js',
            'js_classes/SBK.Class.SongLyricsEdit.js',
            'index.js'
        ),
        'css_library' => array(
        	'../js/js_dependencies/select2-4.0.3/dist/css/select2.min.css',
            'common.css',
            'playlists-list.css',
            'songs-list.css',
            'playlist-book.css',
            'playlist-edit.css',
            'playlist-print.css',
            'song-edit.css',
            'song-lyrics.css',
            'chord_editor.css',
            'baked_ui.css'
        )
    )
);
/* end Songbook configuration */




$dispatcher = DispatcherFactory::create();
$dispatcher->dispatch(
    Request::createFromGlobals(),
    new Response()
);
