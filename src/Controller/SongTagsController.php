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
            'contain' => ['Songs', 'Tags']
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
            'contain' => ['Songs', 'Tags']
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
        $songs = $this->SongTags->Songs->find('list', ['limit' => 200]);
        $tags = $this->SongTags->Tags->find('list', ['limit' => 200]);
        $this->set(compact('songTag', 'songs', 'tags'));
        $this->set('_serialize', ['songTag']);
    }

    /**
     * Creates a new tag entry or takes an existing tag and a links it to a specified song.
     * Then return to the song
     *
     * @param string $ret_controller
     * @param string $ret_action
     * @param integer $ret_id
     */
    public function MatchList($ret_controller, $ret_action, $ret_id)
    {
        $redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];

        $previous_tag_songs_query = $this->SongTags->find('all')
        ->where(['song_id' => $this->request->data['song_id']]);
        $previous_tag_songs = [];
        foreach($previous_tag_songs_query as $this_previous_tag_song) {
            $previous_tag_songs[] = $this_previous_tag_song;
        }
        $reused_tags = [];
        if ($this->request->is('post')) {
            foreach ($this->request->data['tag_id'] as $this_tag_id) {
                if (is_numeric($this_tag_id)) {
                    $reused_tags[] = $this_tag_id;
                    $data = [
                            'song_id' => $this->request->data['song_id'],
                            'tag_id' => $this_tag_id
                    ];
                } else {
                    $data = [
                            'song_id' => $this->request->data['song_id'],
                            'tag' => [
                                    'title' => $this_tag_id
                            ]
                    ];
                }

                $songTag = $this->SongTags->newEntity($data);
                //debug($songTag);
                if (!$this->SongTags->save($songTag)) {
                    $this->Flash->error(__('The Song/Tag could not be saved. Please, try again.'));
                }
            }

            foreach($previous_tag_songs as $this_previous_tag_song_object) {
                if (!in_array($this_previous_tag_song_object->tag_id, $reused_tags)) {
                    $this->SongTags->delete($this_previous_tag_song_object);
                }
            }

            $this->Flash->success(__('The list of song tags has been saved.'));
            return $this->redirect($redirect_array);

        } else {
            $this->Flash->error(__('The Tag could not be created - no post data '.serialize($this->request)));
        }
    }
    /**
     * Based on (working) MatchList() above, but maodified to accet ajax request
     * Creates a new tag entry or takes an existing tag and a links it to a specified song.
     */
    public function MatchListAjax()
    {
        $this->autoRender = false;
        $report = '';

        if ($this->request->is('ajax')) {
            $request_data = $this->request->query;
            if (array_key_exists('song_id', $request_data)) {
                $previous_tag_songs_query = $this->SongTags->find('all');
                $previous_tag_songs_query->where(['song_id' => $request_data['song_id']]);
                $previous_tag_songs = [];
                foreach($previous_tag_songs_query as $this_previous_tag_song) {
                    $previous_tag_songs[] = $this_previous_tag_song;
                }
                $reused_tags = [];
                if (array_key_exists('tag_id', $request_data) && sizeof($request_data['tag_id']) > 0) {
                    $report = $report . 'number of passed tags: '. sizeof($request_data['tag_id']) . ' array key exists? ' . array_key_exists('tag_id', $request_data);
                    foreach ((array) $request_data['tag_id'] as $this_tag_id) {
                        if (is_numeric($this_tag_id)) {
                            $reused_tags[] = $this_tag_id;
                            $data = [
                                'song_id' => $request_data['song_id'],
                                'tag_id' => $this_tag_id
                            ];
                        } else {
                            $data = [
                                'song_id' => $request_data['song_id'],
                                'tag' => [
                                     'title' => $this_tag_id
                                 ]
                            ];
                        }

                        $songTag = $this->SongTags->newEntity($data);

                        if (!$this->SongTags->save($songTag)) {
                            $report = $report . __('The Song-Tag could not be saved. Please, try again.');
                            $this->Flash->error($report);
                            $success = false;
                        } else {
                            $report = $report . __('Tags saved');
                            $success = true;
                        }
                    }

                    foreach($previous_tag_songs as $this_previous_tag_song_object) {
                        if (!in_array($this_previous_tag_song_object->tag_id, $reused_tags)) {
                            $this->SongTags->delete($this_previous_tag_song_object);
                        }
                    }
                    $success = true;
                    $report = $report . __('The list of song tags has been saved.');
                    $this->Flash->success($report);
                } else {
                    $report = $report . __('No Tags created - no [tag_ids] provided. '.serialize($this->request));
                    $this->Flash->error($report);
                    $success = false;
                }
            } else {
                $report = $report . __('Tags could not be created - no [song_id] provided. '.serialize($this->request));
                $this->Flash->error($report);
                $success = false;
            }

        } else {
            $report = __('Tags could not be created - no ajax data. '.serialize($this->request));
            $this->Flash->error($report);
            $success = false;
        }
        $this->response->body('{"success": ' . $success . ', "report": "' . $report . '"}');
        return $this->response;
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
        $songs = $this->SongTags->Songs->find('list', ['limit' => 200]);
        $tags = $this->SongTags->Tags->find('list', ['limit' => 200]);
        $this->set(compact('songTag', 'songs', 'tags'));
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
