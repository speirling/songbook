<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SongVotes Controller
 *
 * @property \App\Model\Table\SongVotesTable $SongVotes
 */
class SongVotesController extends AppController
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
        $this->set('songVotes', $this->paginate($this->SongVotes));
        $this->set('_serialize', ['songVotes']);
    }

    /**
     * View method
     *
     * @param string|null $id Song Vote id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $songVote = $this->SongVotes->get($id, [
            'contain' => ['Songs']
        ]);
        $this->set('songVote', $songVote);
        $this->set('_serialize', ['songVote']);
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
        $songs = $this->SongVotes->Songs->find('list', ['limit' => 200]);
        $this->set(compact('songVote', 'songs'));
        $this->set('_serialize', ['songVote']);
    }

    /**
     * The Save section of the Add method
     * Separated out so that it can be used for non-post data
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    private function addsave($data, $redirect_array)
    {
    	$songVote = $this->SongVotes->newEntity([]);
    	$songVote = $this->SongVotes->patchEntity($songVote, $data);
    	if ($this->SongVotes->save($songVote)) {
            $this->Flash->success(__('The song vote has been saved.'));
            return $this->redirect($redirect_array);
        } else {
            $this->Flash->error(__('The song vote could not be saved. Please, try again.'));
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
     * Version of Add method that responds to ajax
     *
     * @return json object.
     */
    public function addAjax()
    {
        //as this function will only be called through Ajax, set the response type to json:
        $this->response->type('json');
        //and avoid rendering a CakePHP View:
        $this->autoRender = false;

        $request_data = $this->request->query;
        $data = [
            'song_id' => $request_data['song_id']
        ];
        $songVote = $this->SongVotes->newEntity([]);
        $songVote = $this->SongVotes->patchEntity($songVote, $data);
        if ($this->SongVotes->save($songVote)) {
            $this->response->body(json_encode([
                "success" => TRUE,
                "report" => 'The song vote has been saved.'
            ]));
        } else {
            $this->response->body(json_encode([
                "success" => FALSE,
                "report" => 'The song vote could not be saved. Please, try again.'
            ]));
        }
        return $this->response;
    }

    /**
     * Edit method
     *
     * @param string|null $id Song Vote id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $songVote = $this->SongVotes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $songVote = $this->SongVotes->patchEntity($songVote, $this->request->data);
            if ($this->SongVotes->save($songVote)) {
                $this->Flash->success(__('The song vote has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The song vote could not be saved. Please, try again.'));
            }
        }
        $songs = $this->SongVotes->Songs->find('list', ['limit' => 200]);
        $this->set(compact('songVote', 'songs'));
        $this->set('_serialize', ['songVote']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Song Vote id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $songVote = $this->SongVotes->get($id);
        if ($this->SongVotes->delete($songVote)) {
            $this->Flash->success(__('The song vote has been deleted.'));
        } else {
            $this->Flash->error(__('The song vote could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
