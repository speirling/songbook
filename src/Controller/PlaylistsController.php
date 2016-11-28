<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\SetSong;
use App\Model\Entity\PlaylistSet;
use App\Model\Entity\Set;
use App\Model\Entity\Song;
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
            'contain' => ['Performers', 'PlaylistSets' => ['Sets'=> ['Performers', 'SetSongs'=>['Songs', 'sort' => ['SetSongs.order' => 'ASC'], 'Performers']], 'sort' => ['PlaylistSets.order' => 'ASC']]]
        ]);

        $song = new Song();
        $this->set(compact('playlist', 'song'));
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
     * sortSets method
     *
     * @param string|null $id Playlist id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function sortSets($id = null)
    {
        return $this->edit($id);
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
            'contain' => ['Performers', 'PlaylistSets' => ['Sets'=> ['Performers', 'SetSongs'=>['Songs', 'sort' => ['SetSongs.order' => 'ASC']]], 'sort' => ['PlaylistSets.order' => 'ASC']]]
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
        $playlistSet = new PlaylistSet();
        $set = new Set();
        $song = new Song();
        $this->set(compact('playlist', 'setSong', 'songs', 'playlistSet', 'set', 'song'));
        $this->set('_serialize', ['playlist']);
        $this->set('performers', $this->Playlists->Performers->find('list', [
            		'keyField' => 'id',
            		'valueField' => 'nickname'
                ]
            )
        );
        $this->set('sets', $this->Playlists->PlaylistSets->sets->find('list', [
            		'keyField' => 'id',
            		'valueField' =>  function ($e) {
	        			return $e['title']."  (performer ".$e['performer_id'].")";
	        		}
                ]
            )
        );
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
