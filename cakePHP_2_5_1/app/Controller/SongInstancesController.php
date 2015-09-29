<?php
App::uses('AppController', 'Controller');
/**
 * SongInstances Controller
 *
 * @property SongInstance $SongInstance
 * @property PaginatorComponent $Paginator
 */
class SongInstancesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->SongInstance->recursive = 0;
		$this->set('songInstances', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SongInstance->exists($id)) {
			throw new NotFoundException(__('Invalid song instance'));
		}
		$options = array('conditions' => array('SongInstance.' . $this->SongInstance->primaryKey => $id));
		$this->set('songInstance', $this->SongInstance->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SongInstance->create();
			if ($this->SongInstance->save($this->request->data)) {
				$this->Session->setFlash(__('The song instance has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The song instance could not be saved. Please, try again.'));
			}
		}
		$songs = $this->SongInstance->Song->find('list');
		$performers = $this->SongInstance->Performer->find('list');
		$this->set(compact('songs', 'performers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SongInstance->exists($id)) {
			throw new NotFoundException(__('Invalid song instance'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SongInstance->save($this->request->data)) {
				$this->Session->setFlash(__('The song instance has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The song instance could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SongInstance.' . $this->SongInstance->primaryKey => $id));
			$this->request->data = $this->SongInstance->find('first', $options);
		}
		$songs = $this->SongInstance->Song->find('list');
		$performers = $this->SongInstance->Performer->find('list');
		$this->set(compact('songs', 'performers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SongInstance->id = $id;
		if (!$this->SongInstance->exists()) {
			throw new NotFoundException(__('Invalid song instance'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->SongInstance->delete()) {
			$this->Session->setFlash(__('The song instance has been deleted.'));
		} else {
			$this->Session->setFlash(__('The song instance could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
