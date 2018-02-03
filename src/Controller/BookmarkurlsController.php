<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Bookmarkurls Controller
 *
 * @property \App\Model\Table\BookmarkurlsTable $Bookmarkurls
 */
class BookmarkurlsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('bookmarkurls', $this->paginate($this->Bookmarkurls));
        $this->set('_serialize', ['bookmarkurls']);
    }

    /**
     * View method
     *
     * @param string|null $id Bookmarkurl id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bookmarkurl = $this->Bookmarkurls->get($id, [
            'contain' => ['Bookmarkgroups']
        ]);
        $this->set('bookmarkurl', $bookmarkurl);
        $this->set('_serialize', ['bookmarkurl']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bookmarkurl = $this->Bookmarkurls->newEntity();
        if ($this->request->is('post')) {
            $bookmarkurl = $this->Bookmarkurls->patchEntity($bookmarkurl, $this->request->data);
            if ($this->Bookmarkurls->save($bookmarkurl)) {
                $this->Flash->success(__('The bookmarkurl has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The bookmarkurl could not be saved. Please, try again.'));
            }
        }
        $bookmarkgroups = $this->Bookmarkurls->Bookmarkgroups->find('list', ['limit' => 200]);
        $this->set(compact('bookmarkurl', 'bookmarkgroups'));
        $this->set('_serialize', ['bookmarkurl']);
    }
    
    public function addAjax()
    {
        //as this function will only be called through Ajax, set the response type to json:
        $this->response->type('json');
        //and avoid rendering a CakePHP View:
        $this->autoRender = false;
        $request_data = $this->request->query;
        
        $bookmarkurl = $this->Bookmarkurls->newEntity();
        
        $bookmarkurl = $this->Bookmarkurls->patchEntity($bookmarkurl, $request_data);
        
        if ($this->Bookmarkurls->save($bookmarkurl)) {
            $this->response->body(json_encode([
                "success" => TRUE,
                "report" => 'The Bookmark-URL has been saved.'
            ]));
        } else {
            $this->response->body(json_encode([
                "success" => FALSE,
                "report" => debug($bookmarkurl, false)
            ]));
        }
        return $this->response;
    }

    /**
     * Edit method
     *
     * @param string|null $id Bookmarkurl id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bookmarkurl = $this->Bookmarkurls->get($id, [
            'contain' => ['Bookmarkgroups']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bookmarkurl = $this->Bookmarkurls->patchEntity($bookmarkurl, $this->request->data);
            if ($this->Bookmarkurls->save($bookmarkurl)) {
                $this->Flash->success(__('The bookmarkurl has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The bookmarkurl could not be saved. Please, try again.'));
            }
        }
        $bookmarkgroups = $this->Bookmarkurls->Bookmarkgroups->find('list', ['limit' => 200]);
        $this->set(compact('bookmarkurl', 'bookmarkgroups'));
        $this->set('_serialize', ['bookmarkurl']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Bookmarkurl id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmarkurl = $this->Bookmarkurls->get($id);
        if ($this->Bookmarkurls->delete($bookmarkurl)) {
            $this->Flash->success(__('The bookmarkurl has been deleted.'));
        } else {
            $this->Flash->error(__('The bookmarkurl could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
