<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\StaticFunctionController;
use App\Model\Entity\SongTag;
use App\Model\Entity\Tag;
use App\Model\Entity\SetSong;
use App\Model\Entity\Performer;

/**
 * Songs Controller
 *
 * @property \App\Model\Table\SongsTable $Songs
 */
class SongsController extends AppController
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
		$this->Songs = $this->Songs->find();
		$this->Songs = $this->Songs->contain('SetSongs.Performers');
		if ($this->request->is('post')) {
			$search_string = $this->request->data['Search'];
			$this->Songs = $this->Songs->where(['title LIKE' => '%'.$search_string.'%']);
		} else {
			$search_string = '';
		}

		$this->Songs = $this->Songs->order(['id' =>'DESC']);

        $setSong = new SetSong();
        $this->set('setSong', $setSong);

        $this->set('title', 'Song list');
		$this->set('search_string', $search_string);
		$this->set('songs', $this->paginate($this->Songs));
		$this->set('_serialize', ['songs']);

        $this->loadModel('Performers');
        $this->set('performers', $this->Performers->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'nickname'
                ]
            )
        );
	}
    /**
     * Search method
     * This isn't usually required - the index method carries out searches.
     * This is only here becuase I want to return from a 'vote' call to the same search as it was called from
     *
     * @return void
     */
	public function search()
	{
		$search_string = $this->request->pass[0];
			$this->Songs = $this->Songs->find()
			->where(['title LIKE' => '%'.$search_string.'%'])
			->order(['id' =>'DESC'])->contain(['SetSongs'=>function($query){
                return $query->find('all')->distinct(['Performers__id', 'SetSongs__key']);
        }])->contain('SetSongs.Performers');
        $setSong = new SetSong();
        $this->set('setSong', $setSong);
        $this->set('title', 'Search Results');
		$this->set('search_string', $search_string);
		$this->set('songs', $this->paginate($this->Songs));
		$this->set('_serialize', ['songs']);

        $this->loadModel('Performers');
        $this->set('performers', $this->Performers->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'nickname'
                ]
            )
        );
	}

	/**
	 * View method
	 *
	 * @param string|null $id Song id.
	 * @return void
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$song = $this->Songs->get($id, [
				'contain' => ['SongTags'=>['Tags'], 'SetSongs'=>function($query){
				return $query->find('all')->distinct(['Performers__id', 'SetSongs__key']);
				}, 'SetSongs.Performers']]);

		$default_print_page_configuration = \Cake\Core\Configure::read('Songbook.print_page.A4');
		$print_page_configuration = [
		    "page_width" => isset($_GET['vw']) ? $_GET['vw'] : $default_print_page_configuration['page_width'], //px
		    "page_height"  => isset($_GET['vh']) ? $_GET['vh'] : $default_print_page_configuration['page_height'], //px
		];

	    $song["current_key"] = isset($_GET['key']) ? $_GET['key'] : null;
	    $song["capo"] = isset($_GET['capo']) ? $_GET['capo'] : null;

		if(strpos($song['base_key'], " ") > 0) { debug("Cannot transpose this song because the given base key contains a space!!");}
		$song['content'] = StaticFunctionController::convert_song_content_to_HTML(
		    $song['content'], 
		    $song['base_key'], 
		    $song["current_key"], 
		    $song["capo"]
		);
		
	    $song['printable_content'] = StaticFunctionController::convert_content_HTML_to_columns(
		    $song,  
	        StaticFunctionController::derive_page_parameters($print_page_configuration)
		);
		
		$this->set('song', $song);
		$this->set('setSong', new SetSong());
		$this->set('title', $song['title']);
		$this->set('current_key', $song["current_key"]);
		$this->set('capo', $song['capo']);
		$this->set('tags', $this->Songs->SongTags->Tags->find('list'));
		$this->set('songTag', new SongTag());
		$this->set('_serialize', ['song']);
		$this->set('performers', $this->Songs->SetSongs->Performers->find('list', [
				    'keyField' => 'id',
				    'valueField' => 'nickname'
		        ]
		    )
		);
		
		$this->set('title_block_html', StaticFunctionController::generate_title_html($song));
	}

	/**
	 * Print method
	 * copied from the View method
	 *
	 * @param string|null $id Song id.
	 * @return void
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function printable($id = null)
	{
		$song = $this->Songs->get($id, [
				'contain' => ['SongTags'=>['Tags'], 'SetSongs'=>function($query){
				return $query->find('all')->distinct(['Performers__id', 'SetSongs__key']);
				}, 'SetSongs.Performers']]);
		
		$print_size = "default";
		$print_page = "A4";
		$print_page_configuration = \Cake\Core\Configure::read('Songbook.print_page.' . $print_page);
		
		$song["current_key"] = isset($_GET['key']) ? $_GET['key'] : null;
		$song["capo"] = isset($_GET['capo']) ? $_GET['capo'] : null;

		if(array_key_exists('key', $_GET)) {
		    $song["current_key"] = $_GET['key'];
		} else {
		    $song["current_key"] = $song['base_key'];
		}
		if(array_key_exists('capo', $_GET)) {
		    $song["capo"] = $_GET['capo'];
		}
		if(strpos($song['base_key'], " ") > 0) {
		    debug("Cannot transpose this song because the given base key contains a space!!");
		}

		$song['content'] = StaticFunctionController::convert_song_content_to_HTML(
		    $song['content'],
		    $song['base_key'],
		    $song["current_key"],
		    $song["capo"]
	    );

		$song['printable_content'] = StaticFunctionController::convert_content_HTML_to_columns(
		    $song,
		    StaticFunctionController::derive_page_parameters($print_page_configuration)
		);
		
		$this->set('song', $song);
		$this->set('setSong', new SetSong());
		$this->set('title', $song['title']);
		$this->set('current_key', $song['key']);
		$this->set('capo', $song['capo']);
		$this->set('tags', $this->Songs->SongTags->Tags->find('list'));
		$this->set('songTag', new SongTag());
		$this->set('_serialize', ['song']);
		$this->set('performers', $this->Songs->SetSongs->Performers->find('list', [
        		    'keyField' => 'id',
        		    'valueField' => 'nickname'
		        ]
		    )
		);
		
		$this->set('print_size', $print_size);
		$this->set('title_block_html', StaticFunctionController::generate_title_html($song));
		//after CakePHP 3.4
		//$this->viewBuilder()->setLayout('printable');
		//before CakePHP 3.4
		$this->viewBuilder()->Layout('printable');
	}
	
    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add($redirect_array = ['action' => 'view'])
    {
    	$id = $this->add_base();
    	if($id) {
    		$redirect_array[] = $id;
    		return $this->redirect($redirect_array);
    	}
    }
    
    /**
     * Add-base method
     * To be used by Add method - which does as expected, 
     * Add-ret, which runs the Add method but then redirects to a different page
     * and
     * Add-forward - which is like add-ret except it forwards 
     * the newly-added id to add-ret on another model, along with 
     * the original return destination.  
     *
     *
     * @return id of saved record on success, nothing on failure (raises an error).
     */
    public function add_base()
    {
        $song = $this->Songs->newEntity([]);
        if ($this->request->is('post')) {
            $song = $this->Songs->patchEntity($song, $this->request->data);
            if ($result = $this->Songs->save($song)) {
                $this->Flash->success(__('The song has been saved.'));
                return $result->id;
            } else {
                $this->Flash->error(__('The song could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('song'));
        $this->set('_serialize', ['song']);
    }

    /**
     * Version of Add method that redirects back to a previous page
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function addret($ret_controller, $ret_action, $ret_id)
    {
    	$this->add(['controller' => $ret_controller, 'action' => $ret_action, $ret_id]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Song id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null, $redirect_array = ['action' => 'view'])
    {
        $song = $this->Songs->get($id, [
            'contain' => ['SongTags'=>['Tags'], 'SetSongs'=>function($query){
            return $query->find('all')->distinct(['Performers__id', 'SetSongs__key']);
        }, 'SetSongs.Performers']]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $song = $this->Songs->patchEntity($song, $this->request->data);
            if ($this->Songs->save($song)) {
                $this->Flash->success(__('The song has been saved.'));
                $redirect_array[] = $id;
                return $this->redirect($redirect_array);
            } else {
                $this->Flash->error(__('The song could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('song'));
        $this->set('tags', $this->Songs->SongTags->Tags->find('list'));
        $this->set('songTag', new SongTag());
        $this->set('_serialize', ['song']);
    }

    /**
     * Version of the Edit method that sets a different redirect
     *
     * @param string|null $id Set id.
     * @param string|null $id Controller to return to.
     * @param string|null $id View of the return controller.
     * @param string|null $id id of the return controller.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function editret($id = null, $ret_controller = null, $ret_action = null, $ret_id = null)
    {
    	$this->edit($id, ['controller' => $ret_controller, 'action' => $ret_action, $ret_id]);
    }
    
    /**
     * Delete method
     *
     * @param string|null $id Song id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $song = $this->Songs->get($id);
        if ($this->Songs->delete($song)) {
            $this->Flash->success(__('The song has been deleted.'));
        } else {
            $this->Flash->error(__('The song could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
