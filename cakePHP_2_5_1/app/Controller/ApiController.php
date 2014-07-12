<?php

class ApiController extends AppController {
    public $components = array ( 
        'RequestHandler'   // so that json handler can be used - if a .json extension is used by the requested view 
                           //(or if the relevant method contains $this->RequestHandler->renderAs($this, 'json');) 
                           //you won't need a .ctp
    ); 
    public $uses = array('Song');

    public function get_playlist() {
        $this->RequestHandler->renderAs($this, 'json');
        if (array_key_exists ( 'playlist_name', $this->request->data )) {
            $filename = Configure::read('Songbook.playlist_directory') . DS . $this->request->data ['playlist_name'] . '.playlist';
            if (file_exists ( $filename )) {
                $thisPlaylistContent = simplexml_load_file ( $filename );
                $result = json_decode($this->convert_simpleXML_playlist_to_json_string ( $thisPlaylistContent ));
                
                $this->set ( 'success', true );
                $this->set ( 'data', $result );
            } else {
                $this->set ( 'success', false );
                $this->set ( 'data', "playlist ['.$playlist_name.'] does not exist" );
            }
        } else {
            $this->set ( 'success', false );
            $this->set ( 'data', "no playlist specified" );
        }
        $this->set ( '_serialize', array (
                        'success',
                        'data' 
        ) );
    }

    public function update_playlist() {
        $this->RequestHandler->renderAs($this, 'json');
        if (array_key_exists ( 'playlist_name', $this->request->data )) {
            if (array_key_exists ( 'playlist_data', $this->request->data )) {
                return $this->update_playlist_file ( $this->request->data ['playlist_name'], $this->request->data ['playlist_data'] );
            } else {
                $this->set ( 'success', false );
                $this->set ( 'data', "no playlist_data given" );
            }
        } else {
            $this->set ( 'success', false );
            $this->set ( 'data', "no playlist name specified" );
        }
        $this->set ( '_serialize', array (
                        'success',
                        'data' 
        ) );
    }

    public function get_available_songs() {
        $this->RequestHandler->renderAs($this, 'json');
        $conditions =  array();
        if (array_key_exists ( 'search_string', $this->request->data )) {
            $search_string = $this->request->data ['search_string'];
            if ($search_string !== '') {
                $conditions = array('OR' => array(
                    'Song.title LIKE' => '%'.$search_string.'%',
                    'Song.content LIKE' => '%'.$search_string.'%'
                ));
            }
        }
        $all_songs = $this->Song->find('list', array(
            'fields' => array('Song.id', 'Song.title'),
            'conditions' => $conditions
        ));

        $this->set ( 'success', true );
        $this->set ( 'data', array ( 'songs' => $all_songs ) );
        $this->set ( '_serialize', array ( 'success', 'data' ) );
    }

    public function get_all_playlists() { // accessed by https://fps:9134/songbook-cake/api/all_playlists.json
        $this->RequestHandler->renderAs($this, 'json');
        $all_playlists = array();
        $directoryList = scandir ( Configure::read('Songbook.playlist_directory') );
        foreach ( $directoryList as $filename ) {
            if (! is_dir ( $filename )) {
                $path_parts = pathinfo ( $filename );
                if (array_key_exists ( 'extension', $path_parts ) && $path_parts ['extension'] === 'playlist' && $path_parts ['filename'] != '') {
                    $thisPlaylistContent = simplexml_load_file ( Configure::read('Songbook.playlist_directory') . '/' . $path_parts ['filename'] . '.playlist' );
                    $all_playlists[] = array (
                        'filename' => $path_parts ['filename'],
                        'title' => $thisPlaylistContent ['title'],
                        'act' => $thisPlaylistContent ['act']
                    );
                }
            }
        }
        
        $this->set ( 'success', true );
        $this->set ( 'data', array ( 'playlists' => $all_playlists ) );
        $this->set ( '_serialize', array ( 'success', 'data' ) );
    }

    public function get_song() {
        $this->RequestHandler->renderAs($this, 'json');
        $conditions =  array();
        $id = $this->request->data ['id'];
        
        $song = $this->Song->find('first', array(
            //'fields' => array('Song.id', 'Song.title'),
            'conditions' => array('Song.id' => $id)
        ));

        $this->set ( 'success', true );
        $this->set ( 'data', $song );
        $this->set ( '_serialize', array ( 'success', 'data' ) );
    }

    public function update_song() {
        $this->RequestHandler->renderAs($this, 'json');

        if ($this->request->is('post')) {
            // If the form data can be validated and saved...
            if ($this->Song->save($this->request->data)) {
                $this->set ( 'success', true );
                $this->set ( 'data', '' );
            } else {
                $this->set ( 'success', true );
                $this->set ( 'data', $this->Song->validationErrors );
            }
        }
        $this->set ( '_serialize', array ( 'success', 'data' ) );
    }
    
    
    
    /* ---------------------------------------------------------------------------------------------------------- */
    

    protected function convert_simpleXML_playlist_to_json_string($playlist_simplexml) {
        $text_as_attributes = $this->playlist_text_to_attributes ( $playlist_simplexml );
        $json_string = json_encode ( $text_as_attributes );
        $json_string = str_replace ( '"song"', '"songs"', $json_string );
        $json_string = str_replace ( '"set"', '"sets"', $json_string );
        $json_string = preg_replace ( '/"\@attributes"\:\{(.*?)\}/', '$1', $json_string );
        return $json_string;
    }

    protected function playlist_text_to_attributes($playlist_simplexml) {
        if (sizeof ( $playlist_simplexml->introduction ) > 0) {
            $playlist_simplexml->introduction [0]->addAttribute ( 'text', $playlist_simplexml->introduction [0] );
        }
        $playlist_simplexml->introduction [0] = '';
        foreach ( $playlist_simplexml->set as $this_set ) {
            if (sizeof ( $this_set->introduction ) > 0) {
                $this_set->introduction [0]->addAttribute ( 'text', $this_set->introduction [0] );
            }
            $this_set->introduction [0] = '';
            foreach ( $this_set->song as $this_song ) {
                if (sizeof ( $this_song->introduction ) > 0) {
                    $this_song->introduction [0]->addAttribute ( 'text', $this_song->introduction [0] );
                }
                $this_song->introduction [0] = '';
                $this_song ['title'] = $this->get_song_title ( $this_song ['id'] );
                $this_song ['id'] = $this_song ['id'];
            }
        }
        return $playlist_simplexml;
    }

    protected function get_song_title($song_id) {
        $song = $this->Song->find('first', array(
            'conditions' => array('Song.id' => $song_id)
        ));
        return utf8_encode($song['Song']['title']);
    }

    protected function update_playlist_file($filename, $data_string) {
        $data_string = str_replace ( '\\"', '"', trim ( $data_string, '()' ) );
        $data_string = str_replace ( '\"', '"', trim ( $data_string, '()' ) );
        $data_string = str_replace ( "\'", "'", trim ( $data_string, '()' ) );
        $data = json_decode ( trim ( $data_string, '()' ) );
        $playlist_XML = sbk_convert_parsedjson_to_playlistXML ( $data );
        $destination = str_replace ( '\\', '/', getcwd () ) . '/' . Configure::read('Songbook.playlist_directory') . '/' . $filename . '.playlist';
        if (file_exists ( $destination )) {
            copy ( $destination, str_replace ( '\\', '/', getcwd () ) . '/' . Configure::read('Songbook.playlist_directory') . '/safety/' . $filename . date ( 'YmdHis' ) . '.playlist' );
        }
        if ($playlist_XML->saveXML ( $destination )) {
            $this->set ( 'success', true );
        } else {
            $this->set ( 'success', false );
        }
        $this->set ( '_serialize', array ( 'success', 'data' ) );
    }
}

?>
