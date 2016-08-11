<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\SetSong;
/**
 * Playlists Controller
 *
 * @property \App\Model\Table\PlaylistsTable $Playlists
 */
class PlaylistsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {   
        $playlists = $this->Playlists->find()->contain('Performers');
        $this->set('playlists', $this->paginate($playlists));
        $this->set('_serialize', ['playlists']);
    }

    /**
     * View method
     *
     * @param string|null $id Playlist id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $playlist = $this->Playlists->get($id, [
            'contain' => ['Performers', 'PlaylistSets' => ['Sets'=> ['Performers', 'SetSongs'=>['Songs']]]]
        ]);
        $this->set('playlist', $playlist); 
        $this->set('_serialize', ['playlist']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $playlist = $this->Playlists->newEntity();
        if ($this->request->is('post')) {
            $playlist = $this->Playlists->patchEntity($playlist, $this->request->data);
            if ($this->Playlists->save($playlist)) {
                $this->Flash->success(__('The playlist has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The playlist could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('playlist'));
        $this->set('_serialize', ['playlist']);
        $this->set('performers', $this->Playlists->Performers->find('list'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Playlist id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $playlist = $this->Playlists->get($id, [
            'contain' => ['PlaylistSets' => ['Sets'=> ['Performers', 'SetSongs'=>['Songs']]]]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $playlist = $this->Playlists->patchEntity($playlist, $this->request->data);
            if ($this->Playlists->save($playlist)) {
                $this->Flash->success(__('The playlist has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The playlist could not be saved. Please, try again.'));
            }
        }
        $setSong = new SetSong();
        $songs = $this->Playlists->PlaylistSets->Sets->SetSongs->Songs->find('list', [
        		'keyField' => 'id',
        		'valueField' => function ($e) {
        		return $e['title']."  (".$e['performed_by'].")";
        		}
        	]
        );
        $this->set(compact('playlist', 'setSong', 'songs'));
        $this->set('_serialize', ['playlist']);
        $this->set('performers', $this->Playlists->Performers->find('list'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Playlist id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $playlist = $this->Playlists->get($id);
        if ($this->Playlists->delete($playlist)) {
            $this->Flash->success(__('The playlist has been deleted.'));
        } else {
            $this->Flash->error(__('The playlist could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
