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
    public function add()
    {
        $songVote = $this->SongVotes->newEntity();
        if ($this->request->is('post')) {
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
