<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * BookmarkurlsBookmarkgroups Controller
 *
 * @property \App\Model\Table\BookmarkurlsBookmarkgroupsTable $BookmarkurlsBookmarkgroups
 */
class BookmarkurlsBookmarkgroupsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Bookmarkgroups', 'Bookmarkurls']
        ];
        $this->set('bookmarkurlsBookmarkgroups', $this->paginate($this->BookmarkurlsBookmarkgroups));
        $this->set('_serialize', ['bookmarkurlsBookmarkgroups']);
    }

    /**
     * View method
     *
     * @param string|null $id Bookmarkurls Bookmarkgroup id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bookmarkurlsBookmarkgroup = $this->BookmarkurlsBookmarkgroups->get($id, [
            'contain' => ['Bookmarkgroups', 'Bookmarkurls']
        ]);
        $this->set('bookmarkurlsBookmarkgroup', $bookmarkurlsBookmarkgroup);
        $this->set('_serialize', ['bookmarkurlsBookmarkgroup']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bookmarkurlsBookmarkgroup = $this->BookmarkurlsBookmarkgroups->newEntity();
        if ($this->request->is('post')) {
            $bookmarkurlsBookmarkgroup = $this->BookmarkurlsBookmarkgroups->patchEntity($bookmarkurlsBookmarkgroup, $this->request->data);
            if ($this->BookmarkurlsBookmarkgroups->save($bookmarkurlsBookmarkgroup)) {
                $this->Flash->success(__('The bookmarkurls bookmarkgroup has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The bookmarkurls bookmarkgroup could not be saved. Please, try again.'));
            }
        }
        $bookmarkgroups = $this->BookmarkurlsBookmarkgroups->Bookmarkgroups->find('list', ['limit' => 200]);
        $bookmarkurls = $this->BookmarkurlsBookmarkgroups->Bookmarkurls->find('list', ['limit' => 200]);
        $this->set(compact('bookmarkurlsBookmarkgroup', 'bookmarkgroups', 'bookmarkurls'));
        $this->set('_serialize', ['bookmarkurlsBookmarkgroup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Bookmarkurls Bookmarkgroup id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bookmarkurlsBookmarkgroup = $this->BookmarkurlsBookmarkgroups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bookmarkurlsBookmarkgroup = $this->BookmarkurlsBookmarkgroups->patchEntity($bookmarkurlsBookmarkgroup, $this->request->data);
            if ($this->BookmarkurlsBookmarkgroups->save($bookmarkurlsBookmarkgroup)) {
                $this->Flash->success(__('The bookmarkurls bookmarkgroup has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The bookmarkurls bookmarkgroup could not be saved. Please, try again.'));
            }
        }
        $bookmarkgroups = $this->BookmarkurlsBookmarkgroups->Bookmarkgroups->find('list', ['limit' => 200]);
        $bookmarkurls = $this->BookmarkurlsBookmarkgroups->Bookmarkurls->find('list', ['limit' => 200]);
        $this->set(compact('bookmarkurlsBookmarkgroup', 'bookmarkgroups', 'bookmarkurls'));
        $this->set('_serialize', ['bookmarkurlsBookmarkgroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Bookmarkurls Bookmarkgroup id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmarkurlsBookmarkgroup = $this->BookmarkurlsBookmarkgroups->get($id);
        if ($this->BookmarkurlsBookmarkgroups->delete($bookmarkurlsBookmarkgroup)) {
            $this->Flash->success(__('The bookmarkurls bookmarkgroup has been deleted.'));
        } else {
            $this->Flash->error(__('The bookmarkurls bookmarkgroup could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
