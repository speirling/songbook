<?php
App::uses('AppController', 'Controller');
/**
 * Performers Controller
 *
 * @property Performer $Performer
 * @property PaginatorComponent $Paginator
 */
class PerformersController extends AppController {

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
		$this->Performer->recursive = 0;
		$this->set('performers', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Performer->exists($id)) {
			throw new NotFoundException(__('Invalid performer'));
		}
		$options = array('conditions' => array('Performer.' . $this->Performer->primaryKey => $id));
		$this->set('performer', $this->Performer->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Performer->create();
			if ($this->Performer->save($this->request->data)) {
				$this->Session->setFlash(__('The performer has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The performer could not be saved. Please, try again.'));
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
		if (!$this->Performer->exists($id)) {
			throw new NotFoundException(__('Invalid performer'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Performer->save($this->request->data)) {
				$this->Session->setFlash(__('The performer has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The performer could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Performer.' . $this->Performer->primaryKey => $id));
			$this->request->data = $this->Performer->find('first', $options);
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
		$this->Performer->id = $id;
		if (!$this->Performer->exists()) {
			throw new NotFoundException(__('Invalid performer'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Performer->delete()) {
			$this->Session->setFlash(__('The performer has been deleted.'));
		} else {
			$this->Session->setFlash(__('The performer could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
