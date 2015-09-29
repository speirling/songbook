<?php
App::uses('AppController', 'Controller');
/**
 * Playlists Controller
 *
 * @property Playlist $Playlist
 * @property PaginatorComponent $Paginator
 */
class PlaylistsController extends AppController {

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
		$this->Playlist->recursive = 0;
		$this->set('playlists', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Playlist->exists($id)) {
			throw new NotFoundException(__('Invalid playlist'));
		}
		$options = array('conditions' => array('Playlist.' . $this->Playlist->primaryKey => $id));
		$this->set('playlist', $this->Playlist->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Playlist->create();
			if ($this->Playlist->save($this->request->data)) {
				$this->Session->setFlash(__('The playlist has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The playlist could not be saved. Please, try again.'));
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
		if (!$this->Playlist->exists($id)) {
			throw new NotFoundException(__('Invalid playlist'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Playlist->save($this->request->data)) {
				$this->Session->setFlash(__('The playlist has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The playlist could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Playlist.' . $this->Playlist->primaryKey => $id));
			$this->request->data = $this->Playlist->find('first', $options);
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
		$this->Playlist->id = $id;
		if (!$this->Playlist->exists()) {
			throw new NotFoundException(__('Invalid playlist'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Playlist->delete()) {
			$this->Session->setFlash(__('The playlist has been deleted.'));
		} else {
			$this->Session->setFlash(__('The playlist could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
