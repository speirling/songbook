<?php
App::uses('AppController', 'Controller');
/**
 * SongTags Controller
 *
 * @property SongTag $SongTag
 * @property PaginatorComponent $Paginator
 */
class SongTagsController extends AppController {

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
		$this->SongTag->recursive = 0;
		$this->set('songTags', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SongTag->exists($id)) {
			throw new NotFoundException(__('Invalid song tag'));
		}
		$options = array('conditions' => array('SongTag.' . $this->SongTag->primaryKey => $id));
		$this->set('songTag', $this->SongTag->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SongTag->create();
			if ($this->SongTag->save($this->request->data)) {
				$this->Session->setFlash(__('The song tag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The song tag could not be saved. Please, try again.'));
			}
		}
		$tags = $this->SongTag->Tag->find('list');
		$songs = $this->SongTag->Song->find('list');
		$this->set(compact('tags', 'songs'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SongTag->exists($id)) {
			throw new NotFoundException(__('Invalid song tag'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SongTag->save($this->request->data)) {
				$this->Session->setFlash(__('The song tag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The song tag could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SongTag.' . $this->SongTag->primaryKey => $id));
			$this->request->data = $this->SongTag->find('first', $options);
		}
		$tags = $this->SongTag->Tag->find('list');
		$songs = $this->SongTag->Song->find('list');
		$this->set(compact('tags', 'songs'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SongTag->id = $id;
		if (!$this->SongTag->exists()) {
			throw new NotFoundException(__('Invalid song tag'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->SongTag->delete()) {
			$this->Session->setFlash(__('The song tag has been deleted.'));
		} else {
			$this->Session->setFlash(__('The song tag could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
