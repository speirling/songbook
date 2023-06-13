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
 * Viewer Controller
 *
 * @property \App\Model\Table\SongsTable $Songs
 */
class ViewerController extends AppController
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
		$this->songlist->setPagination('off');
		$this->songlist->setSortBy('title','ASC');
		$this->songlist->filterAllSongs();
		//now $filtered_list is available in the view.
		$this->set('title', $this->page_title);
	}
	
}
