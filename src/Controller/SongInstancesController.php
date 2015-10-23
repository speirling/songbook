<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SongInstances Controller
 *
 * @property \App\Model\Table\SongInstancesTable $SongInstances
 */
class SongInstancesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Songs', 'Performers']
        ];
        $this->set('songInstances', $this->paginate($this->SongInstances));
        $this->set('_serialize', ['songInstances']);
    }

    /**
     * View method
     *
     * @param string|null $id Song Instance id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $songInstance = $this->SongInstances->get($id, [
            'contain' => ['Songs', 'Performers']
        ]);
        $this->set('songInstance', $songInstance);
        $this->set('_serialize', ['songInstance']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $songInstance = $this->SongInstances->newEntity();
        if ($this->request->is('post')) {
            $songInstance = $this->SongInstances->patchEntity($songInstance, $this->request->data);
            if ($this->SongInstances->save($songInstance)) {
                $this->Flash->success(__('The song instance has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The song instance could not be saved. Please, try again.'));
            }
        }
        $songs = $this->SongInstances->Songs->find('list', ['limit' => 200]);
        $performers = $this->SongInstances->Performers->find('list', ['limit' => 200]);
        $this->set(compact('songInstance', 'songs', 'performers'));
        $this->set('_serialize', ['songInstance']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Song Instance id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $songInstance = $this->SongInstances->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $songInstance = $this->SongInstances->patchEntity($songInstance, $this->request->data);
            if ($this->SongInstances->save($songInstance)) {
                $this->Flash->success(__('The song instance has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The song instance could not be saved. Please, try again.'));
            }
        }
        $songs = $this->SongInstances->Songs->find('list', ['limit' => 200]);
        $performers = $this->SongInstances->Performers->find('list', ['limit' => 200]);
        $this->set(compact('songInstance', 'songs', 'performers'));
        $this->set('_serialize', ['songInstance']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Song Instance id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $songInstance = $this->SongInstances->get($id);
        if ($this->SongInstances->delete($songInstance)) {
            $this->Flash->success(__('The song instance has been deleted.'));
        } else {
            $this->Flash->error(__('The song instance could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
