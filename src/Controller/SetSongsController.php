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
    public function add()
    {
        $setSong = $this->SetSongs->newEntity();
        if ($this->request->is('post')) {
            $setSong = $this->SetSongs->patchEntity($setSong, $this->request->data);
            if ($this->SetSongs->save($setSong)) {
                $this->Flash->success(__('The set song has been saved.'));
                return $this->redirect(['action' => 'index']);
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
     * Edit method
     *
     * @param string|null $id Set Song id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setSong = $this->SetSongs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setSong = $this->SetSongs->patchEntity($setSong, $this->request->data);
            if ($this->SetSongs->save($setSong)) {
                $this->Flash->success(__('The set song has been saved.'));
                return $this->redirect(['action' => 'index']);
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
     * Delete method
     *
     * @param string|null $id Set Song id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setSong = $this->SetSongs->get($id);
        if ($this->SetSongs->delete($setSong)) {
            $this->Flash->success(__('The set song has been deleted.'));
        } else {
            $this->Flash->error(__('The set song could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
