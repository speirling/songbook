<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\StaticFunctionController;
use App\Model\Entity\Song;
use App\Model\Entity\SongTag;
use App\Model\Entity\Tag;
use App\Model\Entity\SetSong;
use App\Model\Entity\Performer;

/**
 * Dashboard Controller
 *
 * @property \App\Model\Table\SongsTable $Songs
 */
class DashboardController extends AppController
{

	public $paginate = [
			'limit' => 35
	];
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->loadComponent('songlist');
		$this->songlist->filterAllSongs();
		$this->set('title', $this->page_title);
	}
	/*
	public function requestIs($type) {
	    return $this->request->is($type);
	}

	public function requestQuery() {
	    return $this->request->query;
	}
*/
	public function printable()
	{
		$this->loadComponent('songlist');
		$this->songlist->filterAllSongs();
		$this->viewBuilder()->layout('printable');
		$this->set('title', $this->page_title);
		$print_page = "A4";
		$print_size = "default";
		$this->set('print_page', $print_page);
		$this->set('print_size', $print_size);
	}
	
	/**
	 * printLyricSheets method
	 *
	 */
	public function printLyricSheets($id = null)
	{
	    $this->loadComponent('songlist');
	    $this->songlist->filterAllSongs();
	    $pages = array();
	    //debug($this->songlist->filtered_list);	    die();
	    
	    foreach ($this->songlist->filtered_list as $song){
	        //debug($song);
	        //debug($song["set_songs"][0]);
	        if(sizeof($song["set_songs"]) > 0) {
	            $setSong = $song["set_songs"][0];
	        } else {
	            $setSong = array (
	                "key" => '',
	                "capo" => ''
	            );
	        }
	        //debug($setSong);
	        
	        $song_parameters["id"] = $song["id"];
	        $song_parameters["title"] = $song["title"];
	        $song_parameters["written_by"] = $song["written_by"];
	        $song_parameters["performed_by"] = $song["performed_by"];
	        $song_parameters["current_key"] = $setSong["key"];
	        $song_parameters["capo"] = $setSong["capo"];
	        $song_parameters["style_set_or_song"] = "multiple-songs";
	        $print_page = "A4";
	        $print_size = "default";
	        //debug($song["content"]);
	        //debug( $song["base_key"]);;debug($setSong["key"]);debug($setSong["capo"]);
	        //debug(StaticFunctionController::convert_song_content_to_HTML);
	         $html = StaticFunctionController::convert_song_content_to_HTML(
    	         $song["content"],
    	         $song["base_key"],
    	         $setSong["key"],
    	         $setSong["capo"]
	         );
	        
	         $pages[] = StaticFunctionController::convert_content_HTML_to_columns(
    	         $html,
    	         $song_parameters
	         );
	         
	         $this->set('print_page', $print_page);
	         $this->set('print_size', $print_size);
	         $this->set('pages', $pages);
	         $this->set('_serialize', ['pages']);
	         
	    }

	    //after CakePHP 3.4
	    //$this->viewBuilder()->setLayout('printable');
	    //before CakePHP 3.4
	    $this->viewBuilder()->Layout('printable');
	}
	
}
