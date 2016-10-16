<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SongPerformances Controller
 *
 * @property \App\Model\Table\SongPerformancesTable $SongPerformances
 */
class SongPerformancesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Songs']
        ];
        $this->set('songPerformances', $this->paginate($this->SongPerformances));
        $this->set('_serialize', ['songPerformances']);
    }

    /**
     * View method
     *
     * @param string|null $id Song Performance id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $songPerformance = $this->SongPerformances->get($id, [
            'contain' => ['Songs']
        ]);
        $this->set('songPerformance', $songPerformance);
        $this->set('_serialize', ['songPerformance']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add($redirect_array = ['action' => 'index'])
    {
        if ($this->request->is('post')) {
            if ($this->SongPerformances->save($songPerformance)) {
                $this->Flash->success(__('The song performance has been saved.'));
                return $this->redirect($redirect_array);
            } else {
                $this->Flash->error(__('The song performance could not be saved. Please, try again.'));
            }
        }
        $songs = $this->SongPerformances->Songs->find('list', ['limit' => 200]);
        $this->set(compact('songPerformance', 'songs'));
        $this->set('_serialize', ['songPerformance']);
    }

    /**
     * The Save section of the Add method
     * Separated out so that it can be used for non-post data
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    private function addsave($data, $redirect_array)
    {
    	$songPerformance = $this->SongPerformances->newEntity();
    	$songPerformance = $this->SongPerformances->patchEntity($songPerformance, $data);
    	if ($this->SongPerformances->save($songPerformance)) {
            $this->Flash->success(__('The song performance has been saved.'));
            return $this->redirect($redirect_array);
        } else {
            $this->Flash->error(__('The song performance could not be saved. Please, try again.'));
        }
    }

    /**
     * Version of Add method that sets a different redirect
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function addret($song_id, $ret_controller, $ret_action, $ret_id)
    {
    	$data = [
    		'song_id' => $song_id
    	];
    	$redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];
    	$this->addsave($data, $redirect_array);
    }

    /**
     * Edit method
     *
     * @param string|null $id Song Performance id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $songPerformance = $this->SongPerformances->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $songPerformance = $this->SongPerformances->patchEntity($songPerformance, $this->request->data);
            if ($this->SongPerformances->save($songPerformance)) {
                $this->Flash->success(__('The song performance has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The song performance could not be saved. Please, try again.'));
            }
        }
        $songs = $this->SongPerformances->Songs->find('list', ['limit' => 200]);
        $this->set(compact('songPerformance', 'songs'));
        $this->set('_serialize', ['songPerformance']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Song Performance id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $songPerformance = $this->SongPerformances->get($id);
        if ($this->SongPerformances->delete($songPerformance)) {
            $this->Flash->success(__('The song performance has been deleted.'));
        } else {
            $this->Flash->error(__('The song performance could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
