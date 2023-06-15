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
        $songTag = $this->SongTags->newEntity([]);
        if ($this->getRequest()->is('post')) {
            $songTag = $this->SongTags->patchEntity($songTag, $this->getRequest()->getData());
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
    public function matchList($ret_controller, $ret_action, $ret_id)
    {
        $redirect_array = ['controller' => $ret_controller, 'action' => $ret_action, $ret_id];

        $previous_song_tags_query = $this->SongTags->find('all')
        ->where(['song_id' => $this->getRequest()->getData()['song_id']]);
        $previous_song_tags = [];
        foreach($previous_song_tags_query as $this_previous_tag_song) {
            $previous_song_tags[] = $this_previous_tag_song;
        }
        $reused_tags = [];
        if ($this->getRequest()->is('post')) {
            foreach ($this->getRequest()->getData()['tag_id'] as $this_tag_id) {
                if (is_numeric($this_tag_id)) {
                    $reused_tags[] = $this_tag_id;
                    $data = [
                            'song_id' => $this->getRequest()->getData()['song_id'],
                            'tag_id' => $this_tag_id
                    ];
                } else {
                    $data = [
                            'song_id' => $this->getRequest()->getData()['song_id'],
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

            foreach($previous_song_tags as $this_previous_tag_song_object) {
                if (!in_array($this_previous_tag_song_object->tag_id, $reused_tags)) {
                    $this->SongTags->delete($this_previous_tag_song_object);
                }
            }

            $this->Flash->success(__('The list of song tags has been saved.'));
            return $this->redirect($redirect_array);

        } else {
            $this->Flash->error(__('The Tag could not be created - no post data '.serialize($this->getRequest())));
        }
    }

    /**
     * Based on (working) MatchList() above, but modified to accept ajax request
     * Creates a new tag entry or takes an existing tag and a links it to a specified song.
     */
    public function matchListAjax()
    {
        //as this function will only be called through Ajax, set the response type to json:
        $this->response->withType('json');
        //and avoid rendering a CakePHP View:
        $this->autoRender = false;

        $report = [];

        $request_data = $this->getRequest()->getQuery();
        if (array_key_exists('song_id', $request_data)) {
            $previous_song_tags_query = $this->SongTags->find('all');
            $previous_song_tags_query->contain('Tags');
            $previous_song_tags_query->where(['song_id' => $request_data['song_id']]);
            $previous_song_tags = [];
            foreach($previous_song_tags_query as $this_previous_tag_song) {
                $previous_song_tags[] = $this_previous_tag_song;
            }
            $reused_tags = [];
            if (array_key_exists('tag_id', $request_data) && sizeof($request_data['tag_id']) > 0) {
                foreach ((array) $request_data['tag_id'] as $this_tag_id) {
                    $data = NULL;
                    if (is_numeric($this_tag_id)) {
                        $reused_tags[] = $this_tag_id;
                        $data = [
                            'song_id' => $request_data['song_id'],
                            'tag_id' => $this_tag_id
                        ];
                    } elseif ($this_tag_id !== '') {
                        $data = [
                            'song_id' => $request_data['song_id'],
                            'tag' => [
                             'title' => $this_tag_id
                             ]
                        ];
                    }
                    if    (!is_null($data)) {
                        $songTag = $this->SongTags->newEntity($data);

                        if (!$this->SongTags->save($songTag)) {
                            $report[] = ['Not saved: ' =>  $data];
                        } else {
                            $report[] = ['Saved: ' =>  $data];
                        }
                    }
                }

                foreach($previous_song_tags as $this_previous_tag_song_object) {
                    if (!in_array($this_previous_tag_song_object->tag_id, $reused_tags)) {
                        $this->SongTags->delete($this_previous_tag_song_object);
                    }
                }
            } else {
                $this->response->withStringBody(json_encode([
                        "success" => FALSE,
                        "report" => 'No Tags created - no [tag_ids] provided.'
                ]));
                return $this->response;
            }
        } else {
            $this->response->withStringBody(json_encode([
                    "success" => FALSE,
                    "report" => 'Tags could not be created - no [song_id] provided. '
            ]));
            return $this->response;
        }

        $resulting_tags_query = $this->SongTags->find('all');
        $resulting_tags_query->contain('Tags');
        $resulting_tags_query->where(['song_id' => $request_data['song_id']]);
        $resulting_tags = [];
        foreach($resulting_tags_query as $this_tag) {
            //echo debug($this_tag);
            $resulting_tags[] = $this_tag->tag->title;
        }

        $this->response->withStringBody(json_encode([
            "success" => TRUE,
            "report" => $report,
            "tag_data" => $resulting_tags
        ]));
        return $this->response;
    }


    /**
     * Add a list of tags to each of a list of songs
     */
    public function addTagMultiAjax()
    {
        //as this function will only be called through Ajax, set the response type to json:
        $this->response->type('json');
        //and avoid rendering a CakePHP View:
        $this->autoRender = false;

        $report = [];
        $resulting_tags = '';

        $request_data = $this->getRequest()->query;

        if (array_key_exists('song_id', $request_data)) {
            $report[] = 'song ids received';
            $tag_ids = $request_data['tag_id'];
            $song_ids = $request_data['song_id'];

            if (sizeof($tag_ids) > 0) {
                foreach($song_ids as $this_song_id) {
                    foreach ((array) $tag_ids as $this_tag_id) {
                        $data = NULL;
                        if (is_numeric($this_tag_id)) {
                            $reused_tags[] = $this_tag_id;
                            $data = [
                                    'song_id' => $this_song_id,
                                    'tag_id' => $this_tag_id
                            ];
                        } elseif ($this_tag_id !== '') {
                            $data = [
                                'song_id' => $this_song_id,
                                'tag' => [
                                        'title' => $this_tag_id
                                ]
                            ];
                        }
                        if (!is_null($data)) {
                            $songTag = $this->SongTags->newEntity($data);
                            if (!$this->SongTags->save($songTag)) {
                                $report[] = ['Not saved: ' =>  $data];
                            } else {
                                $report[] = ['Saved: ' =>  $data];
                            }
                        }
                    }
                }
                $tag_names = [];
                $song_tags_query = $this->SongTags->Tags->find()->where(['id IN' => $tag_ids]);
                foreach($song_tags_query as $this_song_tag) {
                    $tag_names[] = $this_song_tag->title;
                }
                $this->response->withStringBody(json_encode([
                        "success" => TRUE,
                        "report" => $report,
                        "tag_ids" => $tag_ids,
                        "tag_names" => $tag_names,
                        "song_ids" => $song_ids
                ]));
                return $this->response;
            } else {
                $this->response->withStringBody(json_encode([
                        "success" => False,
                        "report" => 'Tags could not be created - no [tag_ids] provided. '
                ]));
                return $this->response;
            }
        } else {
            $this->response->withStringBody(json_encode([
                    "success" => FALSE,
                    "report" => 'Tags could not be created - no [song_ids] provided. '
            ]));
            return $this->response;
        }
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
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $songTag = $this->SongTags->patchEntity($songTag, $this->getRequest()->getData());
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
        $this->getRequest()->allowMethod(['post', 'delete']);
        $songTag = $this->SongTags->get($id);
        if ($this->SongTags->delete($songTag)) {
            $this->Flash->success(__('The song tag has been deleted.'));
        } else {
            $this->Flash->error(__('The song tag could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
