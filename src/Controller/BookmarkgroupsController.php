<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Bookmarkgroups Controller
 *
 * @property \App\Model\Table\BookmarkgroupsTable $Bookmarkgroups
 */
class BookmarkgroupsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('bookmarkgroups', $this->paginate($this->Bookmarkgroups));
        $this->set('_serialize', ['bookmarkgroups']);
    }

    /**
     * View method
     *
     * @param string|null $id Bookmarkgroup id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bookmarkgroup = $this->Bookmarkgroups->get($id, [
            'contain' => ['Bookmarkurls']
        ]);
        $this->set('bookmarkgroup', $bookmarkgroup);
        $this->set('_serialize', ['bookmarkgroup']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bookmarkgroup = $this->Bookmarkgroups->newEntity();
        if ($this->request->is('post')) {
            $bookmarkgroup = $this->Bookmarkgroups->patchEntity($bookmarkgroup, $this->request->data);
            if ($this->Bookmarkgroups->save($bookmarkgroup)) {
                $this->Flash->success(__('The bookmarkgroup has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The bookmarkgroup could not be saved. Please, try again.'));
            }
        }
        $bookmarkurls = $this->Bookmarkgroups->Bookmarkurls->find('list', ['limit' => 200]);
        $this->set(compact('bookmarkgroup', 'bookmarkurls'));
        $this->set('_serialize', ['bookmarkgroup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Bookmarkgroup id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bookmarkgroup = $this->Bookmarkgroups->get($id, [
            'contain' => ['Bookmarkurls']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bookmarkgroup = $this->Bookmarkgroups->patchEntity($bookmarkgroup, $this->request->data);
            if ($this->Bookmarkgroups->save($bookmarkgroup)) {
                $this->Flash->success(__('The bookmarkgroup has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The bookmarkgroup could not be saved. Please, try again.'));
            }
        }
        $bookmarkurls = $this->Bookmarkgroups->Bookmarkurls->find('list', ['limit' => 200]);
        $this->set(compact('bookmarkgroup', 'bookmarkurls'));
        $this->set('_serialize', ['bookmarkgroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Bookmarkgroup id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmarkgroup = $this->Bookmarkgroups->get($id);
        if ($this->Bookmarkgroups->delete($bookmarkgroup)) {
            $this->Flash->success(__('The bookmarkgroup has been deleted.'));
        } else {
            $this->Flash->error(__('The bookmarkgroup could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
