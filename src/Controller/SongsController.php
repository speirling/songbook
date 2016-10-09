<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\StaticFunctionController;

/**
 * Songs Controller
 *
 * @property \App\Model\Table\SongsTable $Songs
 */
class SongsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
    	if ($this->request->is('post')) {
    		$this->Songs = $this->Songs->find()->where(['title LIKE' => '%'.$this->request->data['Search'].'%']);
    	}
        $this->set('songs', $this->paginate($this->Songs));
        $this->set('_serialize', ['songs']);
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
        $song = $this->Songs->get($id);
        $key = null;
        $capo = null;
        if(array_key_exists('key', $_GET)) {
        	$key = $_GET['key'];
        }
        if(array_key_exists('capo', $_GET)) {
        	$capo = $_GET['capo'];
        }
        $song['content'] = StaticFunctionController::convert_song_content_to_HTML($song['content'], $song['base_key'], $key, $capo);
        $this->set('song', $song);
        $this->set('current_key', $key);
        $this->set('capo', $capo);
        $this->set('_serialize', ['song']);
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
            'contain' => []
        ]);
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
