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
	public function printable()
	{
		$this->loadComponent('songlist');
		$this->songlist->filterAllSongs();
		$this->viewBuilder()->layout('printable');
		$this->set('title', $this->page_title);
	}
}
