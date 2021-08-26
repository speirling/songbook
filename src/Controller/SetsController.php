<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\SetSong; //for the Set edit view

/**
 * Sets Controller
 *
 * @property \App\Model\Table\SetsTable $Sets
 */
class SetsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Performers']
        ];
        $this->set('sets', $this->paginate($this->Sets));
        $this->set('_serialize', ['sets']);
    }

    /**
     * View method
     *
     * @param string|null $id Set id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $set = $this->Sets->get($id, [
            'contain' => ['Performers', 'SetSongs']
        ]);
        $this->set('set', $set);
        $this->set('_serialize', ['set']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add($redirect_array = ['action' => 'index'])
    {
    	$id = $this->add_base();
    	if($id) {
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
        $set = $this->Sets->newEntity();
        if ($this->request->is('post')) {
            $set = $this->Sets->patchEntity($set, $this->request->data);
            if ($result = $this->Sets->save($set)) {
                $this->Flash->success(__('The set has been saved.'));
                return $result->id;
            } else {
                $this->Flash->error(__('The set could not be saved. Please, try again.'));
            }
        }
        $performers = $this->Sets->Performers->find('list', ['limit' => 200]);
        $this->set(compact('set', 'performers'));
        $this->set('_serialize', ['set']);
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
     * Version of Add method that sets a different redirect
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function addforward($forward_controller, $ret_controller, $ret_action, $ret_id)
    {
    	$set_id = $this->add_base();
    	$playlist_id = $ret_id;
    	if($set_id) {
    		return $this->redirect([
    			'controller' => $forward_controller, 
    			'action' => 'add_ret', 
    			$ret_controller, 
    			$ret_action, 
    			$ret_id, 
    			$set_id, 
    			$playlist_id
    		]);
    	} else {
    		$this->Flash->error(__('No set ID - The set was not properly added.'));
    	}
    }

    /**
     * Edit method
     *
     * @param string|null $id Set id.
     * @param array $redirect_array - defines the page that will open after the action.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null, $redirect_array = ['action' => 'index'])
    {
        $set = $this->Sets->get($id, [
            'contain' => ['SetSongs' => ['Songs']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $set = $this->Sets->patchEntity($set, $this->request->data);
            if ($this->Sets->save($set)) {
                $this->Flash->success(__('The set has been saved.'));
                return $this->redirect($redirect_array);
            } else {
                $this->Flash->error(__('The set could not be saved. Please, try again.'));
            }
        }
        $performers = $this->Sets->Performers->find('list', ['limit' => 200]);

        $setSong = new SetSong();
        $songs = $this->Sets->SetSongs->Songs->find('list', [
        		'keyField' => 'id',
        		'valueField' => function ($e) {
        		return $e['title']."  (".$e['performed_by'].")";
        		}
        	]
        );
        
        $this->set(compact('set', 'performers', 'setSong', 'songs'));
        $this->set('_serialize', ['set']);
        
        
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
     * @param string|null $id Set id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $set = $this->Sets->get($id);
        if ($this->Sets->delete($set)) {
            $this->Flash->success(__('The set has been deleted.'));
        } else {
            $this->Flash->error(__('The set could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Printable method
     * Passes to a view (template) that calls the printable method for each of the included songs
     * then displays HTML ready for printing
     *
     * @param string|null $id Song id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function printable($id = null)
    {
        $set = $this->Sets->get($id, [
            'contain' => ['SetSongs' => ['Songs' ]]
        ]);
        foreach ($set->set_songs as $setSong) {
            //debug($setSong);
            $song_parameters["id"] = $setSong->song["id"];
            $song_parameters["title"] = $setSong->song["title"];
            $song_parameters["written_by"] = $setSong->song["written_by"];
            $song_parameters["performed_by"] = $setSong->song["performed_by"];
            $song_parameters["current_key"] = $setSong["key"];
            $song_parameters["capo"] = $setSong["capo"];
            
            $html = StaticFunctionController::convert_song_content_to_HTML(
                $setSong->song->content, 
                $setSong->song["base_key"], 
                $setSong["key"], 
                $setSong["capo"]
            );
            
            $pages = StaticFunctionController::convert_content_HTML_to_columns(
                $html, 
                $song_parameters
            );

            $setSong->song->html_pages = $pages;
        } //foreach
        $this->set('set', $set);
        $this->set('_serialize', ['set']);
        
        //after CakePHP 3.4
        //$this->viewBuilder()->setLayout('printable');
        //before CakePHP 3.4
        $this->viewBuilder()->Layout('printable');
    }
    
}
