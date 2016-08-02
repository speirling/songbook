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
    public function add()
    {
        $song = $this->Songs->newEntity();
        if ($this->request->is('post')) {
            $song = $this->Songs->patchEntity($song, $this->request->data);
            if ($this->Songs->save($song)) {
                $this->Flash->success(__('The song has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The song could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('song'));
        $this->set('_serialize', ['song']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Song id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $song = $this->Songs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $song = $this->Songs->patchEntity($song, $this->request->data);
            if ($this->Songs->save($song)) {
                $this->Flash->success(__('The song has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The song could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('song'));
        $this->set('_serialize', ['song']);
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