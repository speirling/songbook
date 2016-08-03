<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Sets Controller
 *
 * @property \App\Model\Table\SetsTable $Sets
 */
class SetsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Performers']
        ];
        $this->set('sets', $this->paginate($this->Sets));
        $this->set('_serialize', ['sets']);
    }

    /**
     * View method
     *
     * @param string|null $id Set id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $set = $this->Sets->get($id, [
            'contain' => ['Performers', 'SetSongs']
        ]);
        $this->set('set', $set);
        $this->set('_serialize', ['set']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $set = $this->Sets->newEntity();
        if ($this->request->is('post')) {
            $set = $this->Sets->patchEntity($set, $this->request->data);
            if ($this->Sets->save($set)) {
                $this->Flash->success(__('The set has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The set could not be saved. Please, try again.'));
            }
        }
        $performers = $this->Sets->Performers->find('list', ['limit' => 200]);
        $this->set(compact('set', 'performers'));
        $this->set('_serialize', ['set']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Set id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $set = $this->Sets->get($id, [
            'contain' => ['SetSongs' => ['Songs']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $set = $this->Sets->patchEntity($set, $this->request->data);
            if ($this->Sets->save($set)) {
                $this->Flash->success(__('The set has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The set could not be saved. Please, try again.'));
            }
        }
        $performers = $this->Sets->Performers->find('list', ['limit' => 200]);
        $this->set(compact('set', 'performers'));
        $this->set('_serialize', ['set']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Set id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $set = $this->Sets->get($id);
        if ($this->Sets->delete($set)) {
            $this->Flash->success(__('The set has been deleted.'));
        } else {
            $this->Flash->error(__('The set could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
