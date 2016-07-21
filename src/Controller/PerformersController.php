<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Performers Controller
 *
 * @property \App\Model\Table\PerformersTable $Performers
 */
class PerformersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('performers', $this->paginate($this->Performers));
        $this->set('_serialize', ['performers']);
    }

    /**
     * View method
     *
     * @param string|null $id Performer id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $performer = $this->Performers->get($id, [
            'contain' => ['SongInstances']
        ]);
        $this->set('performer', $performer);
        $this->set('_serialize', ['performer']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $performer = $this->Performers->newEntity();
        if ($this->request->is('post')) {
            $performer = $this->Performers->patchEntity($performer, $this->request->data);
            if ($this->Performers->save($performer)) {
                $this->Flash->success(__('The performer has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The performer could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('performer'));
        $this->set('_serialize', ['performer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Performer id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $performer = $this->Performers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $performer = $this->Performers->patchEntity($performer, $this->request->data);
            if ($this->Performers->save($performer)) {
                $this->Flash->success(__('The performer has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The performer could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('performer'));
        $this->set('_serialize', ['performer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Performer id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $performer = $this->Performers->get($id);
        if ($this->Performers->delete($performer)) {
            $this->Flash->success(__('The performer has been deleted.'));
        } else {
            $this->Flash->error(__('The performer could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
