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
    		$this->rerank($data['playlist_id']);
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
    public function addret($ret_controller, $ret_action, $ret_id)
    {
    	$data = [
    		'set_id' => $this->request->data['set_id'],
    		'playlist_id' => $this->request->data['playlist_id'],
    		'order' => 10000
    	];
    	$redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];
    	$this->addsave($data, $redirect_array);
    }

    /**
     * Creates a new set entry and a playlist-set to link it to a specified playlist.
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
    			'playlist_id' => $this->request->data['playlist_id'],
    			'set' => [
    				'title' => $this->request->data['title'],
    				'performer_id' => $this->request->data['performer_id'],
    				'comment' => $this->request->data['comment']
    			],
    			'order' => $this->request->data['order']
    		];
    		$playlistSet = $this->PlaylistSets->newEntity($data);

    		if ($this->PlaylistSets->save($playlistSet)) {
    			$this->Flash->success(__('The set and playlist-set association have been saved.'));
    			$this->rerank($data['playlist_id']);
    			return $this->redirect($redirect_array);
    		} else {
    			$this->Flash->error(__('The set or playlist-set association  could not be saved. Please, try again.'));
    		}
    
    	} else {
    		$this->Flash->error(__('The set could not be created - no post data'));
    	}
    }

    /**
     * Edit method
     *
     * @param string|null $id Playlist Set id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null, $redirect_array = ['action' => 'index'])
    {
        $playlistSet = $this->PlaylistSets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $playlistSet = $this->PlaylistSets->patchEntity($playlistSet, $this->request->data);
            if ($this->PlaylistSets->save($playlistSet)) {
		        // In case the sort order has been changed using javascript - giving fractional ranking values
            	$this->rerank($playlistSet['playlist_id']);
                $this->Flash->success(__('The playlist set has been saved.'));
                return $this->redirect($redirect_array);
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
     * Version of the Edit method that sets a different redirect
     *
     * @param string|null $id Playlist Set id.
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
    public function deleteret($id, $ret_controller, $ret_action, $ret_id)
    {
    	$redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];
    	//expects POST data, not a html link.
        return $this->delete($id, $redirect_array);
    }
    
    /**
     * Function to go through the table for a specific set and revise the values in the sortOrder column into integers
     */
    private function rerank($playlist_id) {
    	$playlist = $this->PlaylistSets->find('all', [
    		'conditions' => ['PlaylistSets.playlist_id =' => $playlist_id],
    		'order' => ['PlaylistSets.order' => 'ASC']
    	]);
    	$current_order = 0;
    	foreach($playlist as $playlistSet) {
    		$current_order = $current_order + 1;
    		$playlistSet->order = $current_order;
    		$this->PlaylistSets->save($playlistSet);
    	}
    }
}
