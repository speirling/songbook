<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Customlists Controller
 *
 * @property \App\Model\Table\CustomlistsTable $Customlists
 * @method \App\Model\Entity\Customlist[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CustomlistsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $customlists = $this->paginate($this->Customlists);

        $this->set(compact('customlists'));
    }

    /**
     * View method
     *
     * @param string|null $id Customlist id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customlist = $this->Customlists->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('customlist'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customlist = $this->Customlists->newEmptyEntity();
        if ($this->request->is('post')) {
            $customlist = $this->Customlists->patchEntity($customlist, $this->request->getData());
            if ($this->Customlists->save($customlist)) {
                $this->Flash->success(__('The customlist has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customlist could not be saved. Please, try again.'));
        }
        $this->set(compact('customlist'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Customlist id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customlist = $this->Customlists->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customlist = $this->Customlists->patchEntity($customlist, $this->request->getData());
            if ($this->Customlists->save($customlist)) {
                $this->Flash->success(__('The customlist has been saved.'));

                return $this->redirect([
                    'controller' => 'viewer', 
                    'action' => 'custom',
                    '?' => ['custom_id' => $id]
                ]);
            }
            $this->Flash->error(__('The customlist could not be saved. Please, try again.'));
        }
        $this->set(compact('customlist'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Customlist id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customlist = $this->Customlists->get($id);
        if ($this->Customlists->delete($customlist)) {
            $this->Flash->success(__('The customlist has been deleted.'));
        } else {
            $this->Flash->error(__('The customlist could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
