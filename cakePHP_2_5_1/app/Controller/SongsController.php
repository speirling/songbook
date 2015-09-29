<?php
App::uses('AppController', 'Controller');
/**
 * Songs Controller
 *
 * @property Song $Song
 * @property PaginatorComponent $Paginator
 */
class SongsController extends AppController {

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
		$this->Song->recursive = 0;
		$this->set('songs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Song->exists($id)) {
			throw new NotFoundException(__('Invalid song'));
		}
		$options = array('conditions' => array('Song.' . $this->Song->primaryKey => $id));
		$this->set('song', $this->Song->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Song->create();
			if ($this->Song->save($this->request->data)) {
				$this->Session->setFlash(__('The song has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The song could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Song->exists($id)) {
			throw new NotFoundException(__('Invalid song'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Song->save($this->request->data)) {
				$this->Session->setFlash(__('The song has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The song could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Song.' . $this->Song->primaryKey => $id));
			$this->request->data = $this->Song->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Song->id = $id;
		if (!$this->Song->exists()) {
			throw new NotFoundException(__('Invalid song'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Song->delete()) {
			$this->Session->setFlash(__('The song has been deleted.'));
		} else {
			$this->Session->setFlash(__('The song could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
