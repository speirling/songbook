<?php
App::uses('AppController', 'Controller');
/**
 * PlaylistSets Controller
 *
 * @property PlaylistSet $PlaylistSet
 * @property PaginatorComponent $Paginator
 */
class PlaylistSetsController extends AppController {

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
		$this->PlaylistSet->recursive = 0;
		$this->set('playlistSets', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->PlaylistSet->exists($id)) {
			throw new NotFoundException(__('Invalid playlist set'));
		}
		$options = array('conditions' => array('PlaylistSet.' . $this->PlaylistSet->primaryKey => $id));
		$this->set('playlistSet', $this->PlaylistSet->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->PlaylistSet->create();
			if ($this->PlaylistSet->save($this->request->data)) {
				$this->Session->setFlash(__('The playlist set has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The playlist set could not be saved. Please, try again.'));
			}
		}
		$sets = $this->PlaylistSet->Set->find('list');
		$playlists = $this->PlaylistSet->Playlist->find('list');
		$this->set(compact('sets', 'playlists'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->PlaylistSet->exists($id)) {
			throw new NotFoundException(__('Invalid playlist set'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlaylistSet->save($this->request->data)) {
				$this->Session->setFlash(__('The playlist set has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The playlist set could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('PlaylistSet.' . $this->PlaylistSet->primaryKey => $id));
			$this->request->data = $this->PlaylistSet->find('first', $options);
		}
		$sets = $this->PlaylistSet->Set->find('list');
		$playlists = $this->PlaylistSet->Playlist->find('list');
		$this->set(compact('sets', 'playlists'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->PlaylistSet->id = $id;
		if (!$this->PlaylistSet->exists()) {
			throw new NotFoundException(__('Invalid playlist set'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->PlaylistSet->delete()) {
			$this->Session->setFlash(__('The playlist set has been deleted.'));
		} else {
			$this->Session->setFlash(__('The playlist set could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
