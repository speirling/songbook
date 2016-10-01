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
    public function add($redirect_array = ['action' => 'index'])
    {
        if ($this->request->is('post')) {
            return addsave($this->request->data, $redirect_array);
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
     * The Save section of the Add method
     * Separated out so that it can be used for non-post data
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    private function addsave($data, $redirect_array)
    {
        $playlistSet = $this->PlaylistSets->newEntity();
    	$playlistSet = $this->PlaylistSets->patchEntity($playlistSet, $data);
    	if ($this->PlaylistSets->save($playlistSet)) {
    		$this->Flash->success(__('The playlist set has been saved.'));
    		return $this->redirect($redirect_array);
    	} else {
    		$this->Flash->error(__('The playlist set could not be saved. Please, try again.'));
    	}
    }
    /**
     * Version of Add method that sets a different redirect
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function addret($ret_controller, $ret_action, $ret_id, $set_id = null, $playlist_id = null)
    {
    	$data = [
    		'set_id' => $set_id,
    		'playlist_id' => $playlist_id,
    		'order' => 10000
    	];
    	$redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];
    	$this->addsave($data, $redirect_array);
    }

    /**
     * Creates a new song entry and a set-song to link it to a specified set.
     * The return to the playlist
     *
     * @param string $ret_controller
     * @param string $ret_action
     * @param integer $ret_id
     */
    public function addAndLinkSet($ret_controller, $ret_action, $ret_id)
    {
    	$redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];
    	 
    	if ($this->request->is('post')) {
    		$data = [
    				'set_id' => $this->request->data['set_id'],
    				'song' => [
    						'title' => $this->request->data['title']
    				],
    				'key' => $this->request->data['key'],
    				'order' => $this->request->data['order'],
    				'performer_id' => $this->request->data['performer_id']
    		];
    		$setSong = $this->SetSongs->newEntity($data);
    
    		$setSong = $this->SetSongs->newEntity($this->request->data, [
    			'associated' => ['Songs']
    		]);
    		
    		if ($this->SetSongs->save($setSong)) {
    			$this->Flash->success(__('The song and set-song association have been saved.'));
    			return $this->redirect($redirect_array);
    		} else {
    			$this->Flash->error(__('The set song could not be saved. Please, try again.'));
    		}
    
    	} else {
    		$this->Flash->error(__('The song could not be created - no post data'));
    	}
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
     * Delete method
     *
     * @param string|null $id Playlist Set id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null, $redirect_array = ['action' => 'index'])
    {
        $this->request->allowMethod(['post', 'delete']);
        $playlistSet = $this->PlaylistSets->get($id);
        if ($this->PlaylistSets->delete($playlistSet)) {
            $this->Flash->success(__('The playlist set has been deleted.'));
        } else {
            $this->Flash->error(__('The playlist set could not be deleted. Please, try again.'));
        }
        return $this->redirect($redirect_array);
    }

    /**
     * Version of Delete method that sets a different redirect
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function deleteret($ret_controller, $ret_action, $ret_id)
    {
        $this->request->allowMethod(['post', 'delete']);
    	$redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];
        $playlistSet = $this->PlaylistSets->get($id);
        if ($this->PlaylistSets->delete($playlistSet)) {
            $this->Flash->success(__('The playlist set has been deleted.'));
        } else {
            $this->Flash->error(__('The playlist set could not be deleted. Please, try again.'));
        }
        return $this->redirect($redirect_array);
    }
}
