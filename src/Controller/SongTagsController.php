<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SongTags Controller
 *
 * @property \App\Model\Table\SongTagsTable $SongTags
 */
class SongTagsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Tags', 'Songs']
        ];
        $this->set('songTags', $this->paginate($this->SongTags));
        $this->set('_serialize', ['songTags']);
    }

    /**
     * View method
     *
     * @param string|null $id Song Tag id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $songTag = $this->SongTags->get($id, [
            'contain' => ['Tags', 'Songs']
        ]);
        $this->set('songTag', $songTag);
        $this->set('_serialize', ['songTag']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $songTag = $this->SongTags->newEntity();
        if ($this->request->is('post')) {
            $songTag = $this->SongTags->patchEntity($songTag, $this->request->data);
            if ($this->SongTags->save($songTag)) {
                $this->Flash->success(__('The song tag has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The song tag could not be saved. Please, try again.'));
            }
        }
        $tags = $this->SongTags->Tags->find('list', ['limit' => 200]);
        $songs = $this->SongTags->Songs->find('list', ['limit' => 200]);
        $this->set(compact('songTag', 'tags', 'songs'));
        $this->set('_serialize', ['songTag']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Song Tag id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $songTag = $this->SongTags->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $songTag = $this->SongTags->patchEntity($songTag, $this->request->data);
            if ($this->SongTags->save($songTag)) {
                $this->Flash->success(__('The song tag has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The song tag could not be saved. Please, try again.'));
            }
        }
        $tags = $this->SongTags->Tags->find('list', ['limit' => 200]);
        $songs = $this->SongTags->Songs->find('list', ['limit' => 200]);
        $this->set(compact('songTag', 'tags', 'songs'));
        $this->set('_serialize', ['songTag']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Song Tag id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $songTag = $this->SongTags->get($id);
        if ($this->SongTags->delete($songTag)) {
            $this->Flash->success(__('The song tag has been deleted.'));
        } else {
            $this->Flash->error(__('The song tag could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
