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
		$this->loadModel('Songs');
		$filtered_list_query = $this->Songs->find();
		$filtered_list_query->contain(['SongTags'=>['Tags']]);
		$filtered_list_query->contain(['SetSongs'=>function($query){
			return $query->find('all')->distinct(['performer_id', 'key']);
		}]);
		$filtered_list_query->contain(['SetSongs.Performers'=>function($query){
			return $query->find('all')->distinct(['performer_id', 'key']);
		}]);
		
		if ($this->request->is(array('post', 'put'))) {
			if($this->request->data['text_search']) {
				$search_string = $this->request->data['text_search'];
				$filtered_list_query->where(['title LIKE' => '%'.$search_string.'%']);	
			} else {
				$search_string = '';
			}
			if($this->request->data['performer_id']) {
				$selected_performer = $this->request->data['performer_id'];
				$filtered_list_query->matching(
				    'SetSongs.Performers', function ($q) use($selected_performer)  {
				        return $q->where(['Performers.id' => $selected_performer]);
				    }
				);
			} else {
				$selected_performer = '';
			}
/*
			if($this->request->data['tag_id']) {
				$selected_tag_array = $this->request->data['tag_id'];
				$filtered_list_query->matching(
				    'SongTags.Tags', function ($q) use($selected_tag_array)  {
				        return $q->where(['Tags.id IN' => $selected_tag_array]);
				    }
				);
			} else {
				$selected_tag_array =[];
			}*/
		} else {
			$search_string = '';
			$selected_performer  = '';
			$selected_tag_array = [];
		}
		
		$filtered_list_query->distinct(['Songs.id']);
		$filtered_list_query->order(['Songs.id' =>'DESC']);

		//echo debug($filtered_list_query); die();
        
		//pass the list of all tags to the view
        $this->loadModel('Tags');
        $all_tags = $this->Tags->find('list');
        $this->set('all_tags', $all_tags);
        
        //pass the list of all Performers to the view        
        $this->loadModel('Performers');
        $performers = $this->Performers->find('all');
		$this->set('performers', $performers);
		
		//pass the list of all known venues to the view
		$this->loadModel('Events');
        $venues = $this->Events->find('list', [
		    'keyField' => 'venue',
		    'valueField' => 'venue'
		])->distinct(['venue']);
		$this->set('venues', $venues);
		
		//a setSong object is required in order to set up the key form on each song row
		$setSong = new SetSong();
        $this->set('setSong', $setSong);
        
        $this->set('title', 'Homepage');
		$this->set('search_string', $search_string);
		$this->set('selected_tags', $selected_tag_array);
		$this->set('filtered_list', $this->paginate($filtered_list_query));
		//$this->set('_serialize', ['songs']);

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
     * This isn't usually required - the index method carries out searched.
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
        	return $query->find('all')->distinct('performer_id', 'key');
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
        	return $query->find('all')->distinct('performer_id', 'key');
        }, 'SetSongs.Performers']]);
        $key = null;
        $capo = null;
        if(array_key_exists('key', $_GET)) {
        	$key = $_GET['key'];
        }
        if(array_key_exists('capo', $_GET)) {
        	$capo = $_GET['capo'];
        }
        $song['content'] = StaticFunctionController::convert_song_content_to_HTML($song['content'], $song['base_key'], $key, $capo);
        $setSong = new SetSong();
        $this->set('song', $song);
        $this->set('setSong', $setSong);
        $this->set('title', $song['title']);
        $this->set('current_key', $key);
        $this->set('capo', $capo);
        $this->set('tags', $this->Songs->SongTags->Tags->find('list'));
        $this->set('songTag', new SongTag());
        $this->set('_serialize', ['song']);
        $this->set('performers', $this->Songs->SetSongs->Performers->find('list', [
            		'keyField' => 'id',
            		'valueField' => 'nickname'
                ]
            )
        );
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
        $song = $this->Songs->newEntity();
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
        	return $query->find('all')->distinct('performer_id', 'key');
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
    public function editret($id = null, $ret_controller, $ret_action, $ret_id)
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
