<?php
App::uses('AppController', 'Controller');
/**
 * SetSongs Controller
 *
 * @property SetSong $SetSong
 * @property PaginatorComponent $Paginator
 */
class SetSongsController extends AppController {

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
		$this->SetSong->recursive = 0;
		$this->set('setSongs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SetSong->exists($id)) {
			throw new NotFoundException(__('Invalid set song'));
		}
		$options = array('conditions' => array('SetSong.' . $this->SetSong->primaryKey => $id));
		$this->set('setSong', $this->SetSong->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SetSong->create();
			if ($this->SetSong->save($this->request->data)) {
				$this->Session->setFlash(__('The set song has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The set song could not be saved. Please, try again.'));
			}
		}
		$songs = $this->SetSong->Song->find('list');
		$sets = $this->SetSong->Set->find('list');
		$this->set(compact('songs', 'sets'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SetSong->exists($id)) {
			throw new NotFoundException(__('Invalid set song'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SetSong->save($this->request->data)) {
				$this->Session->setFlash(__('The set song has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The set song could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SetSong.' . $this->SetSong->primaryKey => $id));
			$this->request->data = $this->SetSong->find('first', $options);
		}
		$songs = $this->SetSong->Song->find('list');
		$sets = $this->SetSong->Set->find('list');
		$this->set(compact('songs', 'sets'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SetSong->id = $id;
		if (!$this->SetSong->exists()) {
			throw new NotFoundException(__('Invalid set song'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->SetSong->delete()) {
			$this->Session->setFlash(__('The set song has been deleted.'));
		} else {
			$this->Session->setFlash(__('The set song could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
