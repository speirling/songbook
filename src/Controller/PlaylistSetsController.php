<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PlaylistSets Controller
 *
 * @property \App\Model\Table\PlaylistSetsTable $PlaylistSets
 */
class PlaylistSetsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Sets', 'Playlists']
        ];
        $this->set('playlistSets', $this->paginate($this->PlaylistSets));
        $this->set('_serialize', ['playlistSets']);
    }

    /**
     * View method
     *
     * @param string|null $id Playlist Set id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $playlistSet = $this->PlaylistSets->get($id, [
            'contain' => ['Sets', 'Playlists']
        ]);
        $this->set('playlistSet', $playlistSet);
        $this->set('_serialize', ['playlistSet']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $playlistSet = $this->PlaylistSets->newEntity();
        if ($this->request->is('post')) {
            $playlistSet = $this->PlaylistSets->patchEntity($playlistSet, $this->request->data);
            if ($this->PlaylistSets->save($playlistSet)) {
                $this->Flash->success(__('The playlist set has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The playlist set could not be saved. Please, try again.'));
            }
        }
        $sets = $this->PlaylistSets->Sets->find('list', [
		    'keyField' => 'id',
		    'valueField' => function ($e) {
                return $e['title']."  (".$e['performer']['name'].")";
		    }
		])->contain(['Performers']);
        $playlists = $this->PlaylistSets->Playlists->find('list', [
		    'keyField' => 'id',
		    'valueField' => function ($e) {
                return $e['title']."  (".$e['performer']['name'].")";
		    }
		])->contain(['Performers']);
        $this->set(compact('playlistSet', 'sets', 'playlists'));
        $this->set('_serialize', ['playlistSet']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Playlist Set id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $playlistSet = $this->PlaylistSets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $playlistSet = $this->PlaylistSets->patchEntity($playlistSet, $this->request->data);
            if ($this->PlaylistSets->save($playlistSet)) {
                $this->Flash->success(__('The playlist set has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The playlist set could not be saved. Please, try again.'));
            }
        }
        $sets = $this->PlaylistSets->Sets->find('list');
        $playlists = $this->PlaylistSets->Playlists->find('list', [
		    'keyField' => 'id',
		    'valueField' => function ($e) {
                return $e['title']."  (".$e['performer_id'].")";
		    }
		]);
        $this->set(compact('playlistSet', 'sets', 'playlists'));
        $this->set('_serialize', ['playlistSet']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Playlist Set id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $playlistSet = $this->PlaylistSets->get($id);
        if ($this->PlaylistSets->delete($playlistSet)) {
            $this->Flash->success(__('The playlist set has been deleted.'));
        } else {
            $this->Flash->error(__('The playlist set could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
