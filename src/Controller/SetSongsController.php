<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SetSongs Controller
 *
 * @property \App\Model\Table\SetSongsTable $SetSongs
 */
class SetSongsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Sets', 'Songs', 'Performers']
        ];
        $this->set('setSongs', $this->paginate($this->SetSongs));
        $this->set('_serialize', ['setSongs']);
    }

    /**
     * View method
     *
     * @param string|null $id Set Song id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setSong = $this->SetSongs->get($id, [
            'contain' => ['Sets', 'Songs', 'Performers']
        ]);
        $this->set('setSong', $setSong);
        $this->set('_serialize', ['setSong']);
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
        $sets = $this->SetSongs->Sets->find('list');
        $songs = $this->SetSongs->Songs->find('list', [
		    'keyField' => 'id',
		    'valueField' => function ($e) {
                return $e['title']."  (".$e['performed_by'].")";
		    }
		]);
        $performers = $this->SetSongs->Performers->find('list');
        $this->set(compact('setSong', 'sets', 'songs', 'performers'));
        $this->set('_serialize', ['setSong']);
    }

    /**
     * The Save section of the Add method
     * Separated out so that it can be used for non-post data
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    private function addsave($data, $redirect_array)
    {
        $setSong = $this->SetSongs->newEntity();
    	$setSong = $this->SetSongs->patchEntity($setSong, $data);
        if ($this->SetSongs->save($setSong)) {
            $this->Flash->success(__('The set song has been saved.'));
            return $this->redirect($redirect_array);
        } else {
            $this->Flash->error(__('The set song could not be saved. Please, try again.'));
        }
    }

    /**
     * Version of Add method that sets a different redirect
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function addret($ret_controller, $ret_action, $ret_id, $song_id = null, $set_id = null, $performer_id = null, $key = null)
    {
    	if ($this->request->is('post')) {
    		$data = $this->request->data;
    	} else {
    		$data = [
    			'song_id' => $song_id,
    			'set_id' => $set_id,
    			'performer_id' => $performer_id,
    			'key' => $key,
    			'order' => 10000
    		];
    	}
    	$redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];
    	$this->addsave($data, $redirect_array);
    }

    /**
     * Edit method
     *
     * @param string|null $id Set Song id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null, $redirect_array = ['action' => 'index'])
    {
        $setSong = $this->SetSongs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setSong = $this->SetSongs->patchEntity($setSong, $this->request->data);
            if ($this->SetSongs->save($setSong)) {
		        // In case the sort order has been changed using javascript - giving fractional ranking values
		        $this->rerank($setSong['set_id']);
                $this->Flash->success(__('The set song has been saved.'));
                return $this->redirect($redirect_array);
            } else {
                $this->Flash->error(__('The set song could not be saved. Please, try again.'));
            }
        }
        $sets = $this->SetSongs->Sets->find('list');
        $songs = $this->SetSongs->Songs->find('list', [
		    'keyField' => 'id',
		    'valueField' => function ($e) {
                return $e['title']."  (".$e['performed_by'].")";
		    }
		]);
        $performers = $this->SetSongs->Performers->find('list');
        $this->set(compact('setSong', 'sets', 'songs', 'performers'));
        $this->set('_serialize', ['setSong']);
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
     * @param string|null $id Set Song id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null, $redirect_array = ['action' => 'index'])
    {
        $this->request->allowMethod(['post', 'delete']);
        $setSong = $this->SetSongs->get($id);
        if ($this->SetSongs->delete($setSong)) {
            $this->Flash->success(__('The set song has been deleted.'));
        } else {
            $this->Flash->error(__('The set song could not be deleted. Please, try again.'));
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
    	$this->delete(['controller' => $ret_controller, 'action' => $ret_action, $ret_id]);
    }
    
    /**
     * Function to go through the table for a specific set and revise the values in the sortOrder column into integers
     */
    private function rerank($set_id) {
    	$set = $this->SetSongs->find('all', [
    		'conditions' => ['SetSongs.set_id =' => $set_id],
    		'order' => ['SetSongs.order' => 'ASC']
    	]);
    	$current_order = 0;
    	foreach($set as $setSong) {
    		$current_order = $current_order + 1;
    		$setSong->order = $current_order;
    		$this->SetSongs->save($setSong);
    	}
    }
}
