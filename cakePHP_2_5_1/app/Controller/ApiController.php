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
                if ($this->update_playlist_file ( $this->request->data ['playlist_name'], $this->request->data ['playlist_data'] ) == true) {
                    $this->set ( 'success', true );
                    $this->set ( 'data', "update playlist file [".$this->request->data ['playlist_name']."] was successful" );
                } else {
                    $this->set ( 'success', false );
                    $this->set ( 'data', "could not update playlist file [".$this->request->data ['playlist_name']."]" );
                }
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
        $id = $this->request->data['id'];
        $this->log($id);
        if($song = $this->Song->find('first', array(
            'conditions' => array('Song.id' => $id)
        ))) {
            $this->set ( 'success', true );
            $this->set ( 'data', $song );
        } else {
            $this->set ( 'success', false );
            $this->set ( 'data', "id=".$id." does not match any songs" );
        }
        $this->set ( '_serialize', array ( 'success', 'data' ) );
    }

    public function update_song() {
        $this->RequestHandler->renderAs($this, 'json');

        if ($this->request->is('post')) {
            // If the form data can be validated and saved...
            if ($this->Song->save($this->request->data)) {
                $this->set ( 'success', true );
                if (array_key_exists('id', $this->request->data)) {
                    $this->set ( 'data', '' );
                } else {
                    $this->set ( 'data', array( 'id' => $this->Song->getLastInsertID()));
                }
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
//print_r($text_as_attributes);
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

        if(array_key_exists('Song', $song)) {
            return $song['Song']['title'];
        } else {
            return null;
        }
    }

    protected function update_playlist_file($filename, $data_array) {

        $playlist_XML = $this->convert_parsedjson_to_playlistXML ( $data_array );

        $destination = Configure::read('Songbook.playlist_directory') . '/' . $filename . '.playlist';

        if (file_exists ( $destination )) {
            copy ( $destination, Configure::read('Songbook.playlist_directory') . '/safety/' . $filename . date ( 'YmdHis' ) . '.playlist' );
        }

        if ($playlist_XML->saveXML ( $destination )) {
            return true;
        } else {
            return false;
        }
    }
    
    protected function convert_parsedjson_to_playlistXML($data_array) {
        $playlistContent = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><songlist></songlist>');
        $playlistContent->addAttribute('title', $data_array["title"]);
        $playlistContent->addAttribute('act', $data_array["act"]);
    
        $show_introduction = $playlistContent->addChild('introduction', (string) $data_array["introduction"]["text"]);
        $show_introduction->addAttribute('duration',(string) $data_array["introduction"]["duration"]);
    
        foreach($data_array["sets"] as $thisSet) {
            $set_duration = 0;
            $XMLset = $playlistContent->addChild('set');
            if(array_key_exists("label", $thisSet)) {
                $XMLset->addAttribute('label', $thisSet["label"]);
            } else {
                $XMLset->addAttribute('label', '(unnamed)');
            }
    
            if(array_key_exists("introduction", $thisSet)) {
                $set_introduction = $XMLset->addChild('introduction', (string) $thisSet["introduction"]["text"]);
                $set_introduction->addAttribute('duration',(string) $thisSet["introduction"]["duration"]);
            } else {
                $set_introduction = $XMLset->addChild('introduction', '');
                $set_introduction->addAttribute('duration', '');
            }
    
            if(array_key_exists("songs", $thisSet)) {
                foreach($thisSet["songs"] as $thisSong) {
                    $this_id = (string) $thisSong["id"];
                    $this_id = str_replace('id_', '', $this_id); // not required, 'id_' has been removed... but this should still work
                    if(is_numeric($this_id)) {
                        if(array_key_exists("duration", $thisSong)) {
                            $set_duration = $set_duration + $this->duration_string_to_seconds((string) $thisSong["duration"]);
                        }
        
                        $XMLsong = $XMLset->addChild('song', '');
                        $XMLsong->addAttribute('id', $this_id);
                        if(array_key_exists("key", $thisSong)) {
                            $XMLsong->addAttribute('key',(string) $thisSong["key"]);
                        } else {
                            $XMLsong->addAttribute('key', '');
                        }
                        if(array_key_exists("singer", $thisSong)) {
                            $XMLsong->addAttribute('singer',(string) $thisSong["singer"]);
                        } else {
                            $XMLsong->addAttribute('singer', '');
                        }
                        if(array_key_exists("capo", $thisSong)) {
                            $XMLsong->addAttribute('capo',(string) $thisSong["capo"]);
                        } else {
                            $XMLsong->addAttribute('capo', '');
                        }
                        if(array_key_exists("duration", $thisSong)) {
                            $XMLsong->addAttribute('duration',(string) $thisSong["duration"]);
                        } else {
                            $XMLsong->addAttribute('duration', '');
                        }
        
                        if (array_key_exists("introduction", $thisSong)) {
                            $introduction = $XMLsong->addChild('introduction', (string) $thisSong["introduction"]["text"]);
                            $introduction->addAttribute('duration',(string) $thisSong["introduction"]["duration"]);
                        }
                    }
                }
            }
        }
    
        return $playlistContent;
    }
    
    protected function duration_string_to_seconds($individual_duration_string) {
        if($individual_duration_string !== '') {
            //assumes duration is mm:ss - so less than a minute would be 00:ss
            $time_bits = preg_split('/:/', $individual_duration_string);
            if(sizeof($time_bits) == 2) {
                $duration_seconds = $time_bits[0] * 60 + $time_bits[1];
            } else {
                //not a valid time string!
                $duration_seconds = 0;
            }
        } else {
            $duration_seconds = 0;
        }
        return $duration_seconds;
    }
        

}




?>
