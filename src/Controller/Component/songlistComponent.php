<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Controller\StaticFunctionController;
use App\Model\Entity\Song;
use App\Model\Entity\SongTag;
use App\Model\Entity\Tag;
use App\Model\Entity\SetSong;
use App\Model\Entity\Performer;

class songlistComponent extends Component {
    public $filtered_list = null;
    private $event = null;
    private $paginate = true;
    private $blank_sort_definition = [
        'param' => '',
        'direction' => '',
        'search_string' => '',
        'performer'  => '',
        'tag_array' => [],
        'exclude_all' => false,
        'exclude_tag_array' => [],
        'selected_venue' => '',
    ];
    
    function setEvent($event) {
        $this->event = $event;
    }
    
    function setPagination($on_or_off) {
        if($on_or_off == 'on') {
            $this->paginate = true;
        } else {
            $this->paginate = false;
        }
    }
    
    function setSortBy($param = 'title', $direction = 'ASC') {
        $this->sort_definition = [
            'param' => $param, 
            'direction' => $direction
        ];
    }
    
    function filter_on ($sort_definition) {
        return sort_definition == $this->blank_sort_definition;
    }
    
    public function get_filters_from_queryparams() {
        $controller = $this->_registry->getController();
        $sort_definition = $this->blank_sort_definition;

        $filter_on = false;
        if ($controller->getRequest()->is(array('post', 'put', 'get'))) {
            if ($controller->getRequest()->is(array('get'))) {
                $query_parameters = $controller->getRequest()->getQuery();
            } else {
                $query_parameters = $controller->getRequest()->getData();
            }
            
            // Title: Song Title text search
            if (array_key_exists('text_search', $query_parameters) && $query_parameters['text_search']) {
                $filter_on = true;
                $sort_definition['search_string'] = $query_parameters['text_search'];
            }
            
            // Tags: Limit the result to songs that are associated with any of the passed array of tags
            if (
                array_key_exists('filter_tag_id', $query_parameters)
                && $query_parameters['filter_tag_id']
                && $query_parameters['filter_tag_id'] != 'Tag...'
            ) {
                $filter_on = true;
                $sort_definition['tag_array'] = $query_parameters['filter_tag_id']; 
            }
            
            // Performer: Limit the result to songs associated with a specific performer
            if (array_key_exists('performer_id', $query_parameters) && $query_parameters['performer_id']) {
                $filter_on = true;
                $sort_definition['performer'] = $query_parameters['performer_id'];
            }
            
            
            //Exclude tags: $sort_definition['performer']do NOT contain ALL of the selected tags here will be displayed
            $sort_definition['exclude_all'] = false; // there's no interface to set this yet, and it seems that it would be more effective to exclude all songs that contain any of the selected tage (smaller list)
            if (
                array_key_exists('exclude_tag_id', $query_parameters)
                && $query_parameters['exclude_tag_id']
                && $query_parameters['exclude_tag_id'] != 'Tag...'
            ) {
                $filter_on = true;
                $sort_definition['exclude_tag_array'] = $query_parameters['exclude_tag_id'];
            }
            
            // Venue :  limit the result to songs that were placed withtin an event at the specified venue
            if (array_key_exists('venue', $query_parameters) && $query_parameters['venue']) {
                $filter_on = true;
                $sort_definition['selected_venue'] = $query_parameters['venue'];
            }
        } else {
            throw ('No Query paramters available');
        }
        if(is_array($this->sort_definition)) {
            $sort_definition['param'] = $this->sort_definition['param'];
            $sort_definition['direction'] = $this->sort_definition['direction'];
        } else if(array_key_exists('sort', $query_parameters) && $query_parameters['sort'] === 'title') {
            $sort_definition['param'] = 'title';
            $sort_definition['direction'] = 'ASC';
        }
        
        $paginate = true;
        if($filter_on == true) {
            $sort_definition['paginate'] = false;
        } elseif(array_key_exists("unpaginated", $query_parameters)) {
            $sort_definition['paginate'] = false;
        } elseif ($this->paginate == false) {
            $sort_definition['paginate'] = false;
        }
        
        return $sort_definition;
    }
    
    public function filterAllSongs($sort_definition) {

		$controller = $this->_registry->getController();
		
		$controller->loadModel('Songs');
		$controller->loadModel('Events');
		$controller->loadModel('SongPerformances');
		$controller->loadModel('SongVotes');
	
		//basic query
		$filtered_list_query = $controller->Songs->find();
		
		//Specify which fields to include in the Query, and join to tags and performers
		$filtered_list_query->select(['id', 'title', 'written_by', 'performed_by', 'base_key', 'content'], false);
		$filtered_list_query->contain(['SongTags'=>['Tags']]);
		$filtered_list_query->contain(['SetSongs.Performers']);
	
		// start song performances -------------------
		//If an event has been specified, restrict the filtered list to songs played during that event.
		//If an event is not specified, don't restrict the list - but mark recently played songs.
            		if (is_null($this->event)) {
            		    //if no specific event has been identified,
            			//find all of the songs played in the last 3 hours (supposed to be current event) and add a parameter that identifies them as played
            			$event_start = strtotime('-3 hours');
            			$event_end = strtotime('-0 hours');
            		} else {
            		    //if a specific event has been identified,
            			//find all of the songs played during the event - assume event time +/- event duration - and restrict the final list to just those songs
            		    if(isset($this->event->duration_hours) && $this->event->duration_hours > 0) {
            		        $event_duration_seconds = $this->event->duration_hours * 60 * 60;
            			} else {
            				$event_duration_seconds = 3 * 60 * 60;
            			}
            			$event_start = strtotime($this->event->timestamp) - $event_duration_seconds;
            			$event_end = strtotime($this->event->timestamp) + $event_duration_seconds;
            		}
            
            		//identify a list of song ids representing songs that were part of the perfomance identified by the specified event
            		$performance_conditions = [];
            		$performance_query = $controller->SongPerformances->find();
            		$performance_query->Where("`SongPerformances`.`timestamp` BETWEEN \"" . date("Y-m-d H:i:s", $event_start)."\" AND \"".date("Y-m-d H:i:s", $event_end)."\"");
            		$performance_query->distinct('song_id');
            		$performance_list = $performance_query->all()->extract('song_id');
            		
            		$song_id_list = [];
            		foreach($performance_list as $id => $song_id) {
            			array_push($song_id_list, $song_id);
            		}
            
            		if (sizeof($song_id_list) > 0) {
            			$song_id_string = str_replace(['[', ']'], ['(', ')'], json_encode($song_id_list));
            			if(is_null($this->event)) {
            				$filtered_list_query->select(['played' => '(`Songs`.`id` IN ' . $song_id_string . ')']); //mark matching songs as having been "played" during the event 
            			} else {
            				$filtered_list_query->andWhere(['`Songs`.`id` IN' => $song_id_list]); //restrict the results to only songs played during the event
            			}
            		}
		// end song performances -------------------
	
		// start song votes -------------------
		//similary, find all of the songs voted in the last 3 hours (supposed to be current event) and add a parameter that identifies them as voted
            		$event_start = strtotime('-3 hours');
            		$event_end = strtotime('-0 hours');
            		$vote_conditions = [];
            		$vote_query = $controller->SongVotes->find();
            		$vote_query->Where("`SongVotes`.`timestamp` BETWEEN \"" . date("Y-m-d H:i:s", $event_start)."\" AND \"".date("Y-m-d H:i:s", $event_end)."\"");
            		$vote_query->distinct('song_id');
            		$vote_list = $vote_query->all()->extract('song_id');
            	
            		$song_id_list = [];
            		foreach($vote_list as $id => $song_id) {
            			array_push($song_id_list, $song_id);
            		}
            	
            		if(sizeof($song_id_list) > 0) {
            			$song_id_string = str_replace(['[', ']'], ['(', ')'], json_encode($song_id_list));
            			$filtered_list_query->select(['voted' => '(`Songs`.`id` IN ' . $song_id_string . ')']); //mark matching songs as having been "voted" during the event 
            		}
		// end song votes -------------------

		$filter_on = false;
		// enable filtering by: title, tags, performer------------------------------------

			/*
			 * allow sorting by
			 *              songbook/dashboard/print-lyric-sheets?unpaginated&sort=title
			 *              songbook/dashboard?sort=title
			 * etc.
			 */ 
		if(is_array($sort_definition['param'] !== '' && $sort_definition['direction'] !== '')) {
		    $filtered_list_query->order([$sort_definition['param'] => $sort_definition['direction']]);
		}
		    
		// Title: Song Title text search
		if ($sort_definition['search_string'] !== '') {
			$filter_on = true;
			$filtered_list_query->where(['Songs.title LIKE' => '%'.$sort_definition['search_string'].'%']);
		}

		// Tags: Limit the result to songs that are associated with any of the passed array of tags
		/*
		 * This has to be done as a subquery, because a HAVING COUNT() must be used to ensure that only songs that are associated with _all_ of the specified tags will be displayed.
		 * That statement interferes with larger more complex queries - e.g. filtered byt Tag _and_ Performer, and can filter out any records that have multiple entries in the final query.
		 *                 $filtered_list_query->having(['COUNT(Songs.id) = ' => sizeof($sort_definition['tag_array'])]);
		 * Keeping the HAVING COUNT() inside a subquery seems to avoid that problem
		 */
		if (sizeof($sort_definition['tag_array']) > 0) {
			$subquery_SongWithAllTags = $controller->Songs->find();
			$selected_tag_array = $sort_definition['tag_array'];
			$subquery_SongWithAllTags->matching(
			    'SongTags.Tags', function ($q) use ($selected_tag_array)  {
			    $q->where(['Tags.id IN' => $selected_tag_array]);
				return $q;
				}
			);
			$subquery_SongWithAllTags->group('Songs.id');
			$subquery_SongWithAllTags->having(['COUNT(Songs.id) = ' => sizeof($sort_definition['tag_array'])]);
			$filtered_list_query->Join([
				'table' => $subquery_SongWithAllTags,
				'alias' => 'subquery_SongWithAllTags',
				'type' => 'INNER',
				'conditions' => '`subquery_SongWithAllTags`.`Songs__id` = `Songs`.`id`'
			]);
		} 
		
		// Performer: Limit the result to songs associated with a specific performer
		if ($sort_definition['performer'] !== '') {
		    $selected_performer = $sort_definition['performer'];
			$filtered_list_query->matching(
			    'SetSongs.Performers', function ($q) use($selected_performer)  {
			    return $q->where(['Performers.id' => $selected_performer]);
				}
			);
		}



        // Venue :  limit the result to songs that were placed withtin an event at the specified venue
		if ($sort_definition['selected_venue'] !== '') {

			//find all of the events that were at this venue
			$venue_query = $controller->Events->findAllByVenue($sort_definition['selected_venue']);
			$event_times = $venue_query->toArray();

			//find all of the songs played during the times of the events at that venue
			$performance_conditions = [];
			$performance_query = $controller->SongPerformances->find();
			foreach($venue_query->toArray() as $key => $event) {
				$performance_query->orWhere("`SongPerformances`.`timestamp` BETWEEN \"".date("Y-m-d H:i:s", strtotime($event->timestamp) - $event->duration_hours * 60 * 60)."\" AND \"".date("Y-m-d H:i:s", strtotime($event->timestamp) + $event->duration_hours * 60 * 60)."\"");
			}
			$performance_query->distinct('song_id');
			$performance_list = $performance_query->extract('song_id');
			$song_id_list = [];
			foreach ($performance_list as $id => $song_id) {
				array_push($song_id_list, $song_id);
			}

			$filtered_list_query->andWhere(['`Songs`.`id` IN' => $song_id_list]);

		}
		 

		//end of [title, tags, performer] filtering -------------------------
		
		
		// Avoid multiples of a specific song
		$filtered_list_query->distinct(['Songs.id']);
        if ($filter_on) {
            $filtered_list_query->order(['Songs.title' =>'ASC']);
        } else {
            $filtered_list_query->order(['Songs.id' =>'DESC']);
        }
        //echo debug($filtered_list_query); die();
        
        
        //------------------------------------------------------------
        //Pass data to the View, separate from the list
        

		//pass the list of all tags to the view
		$controller->loadModel('Tags');
		$all_tags = $controller->Tags->find('list');
		$controller->set('all_tags', $all_tags);
	
		//pass the list of all Performers to the view
		$controller->loadModel('Performers');
		$performers = $controller->Performers->find('list', [
			'keyField' => 'id',
			'valueField' => 'nickname'
		]);
		$controller->set('performers', $performers);
	
		//pass the list of all known venues to the view
		$controller->loadModel('Events');
		$venues = $controller->Events->find('list', [
				'keyField' => 'venue',
				'valueField' => 'venue'
		])->distinct(['venue']);
		$controller->set('venues', $venues);
	
		//a setSong object is required in order to set up the key form on each song row
		$setSong = new SetSong();
		$controller->set('setSong', $setSong);

		
		$controller->set('filter_tag_id', $sort_definition['tag_array']);
		$controller->set('performer_id', $sort_definition['performer']);
		
		$controller->set('search_string', $sort_definition['search_string']);
		$controller->set('selected_performer', $sort_definition['performer']);
		$controller->set('selected_tags', $sort_definition['tag_array']);
		
		
		//for the print title
		$controller->set('exclude_all', $sort_definition['exclude_all']);
		$controller->set('selected_exclude_tags', $sort_definition['exclude_tag_array']);
		
		//for the print title
		$controller->set('print_title', $this->print_title($sort_definition['search_string'], $sort_definition['performer'], $sort_definition['selected_venue'], $sort_definition['tag_array'], $sort_definition['exclude_tag_array'], $performers, $venues, $all_tags));
		$controller->page_title = $this->page_title($sort_definition['search_string'], $sort_definition['performer'], $sort_definition['selected_venue'], $sort_definition['tag_array'], $sort_definition['exclude_tag_array'], $performers, $venues, $all_tags);
		
		//End of passing data to the view ------------------------------------------------------------------
		
		//Should the list be paginated?
		if($sort_definition['paginate'] == false) {
		    $controller->set('filtered_list', $filtered_list_query);
		    $controller->set('filter_on', TRUE); //prevents the footer haveing 'next' & 'previous' buttons
		} else {
		    $controller->set('filtered_list', $controller->paginate($filtered_list_query));
		    $controller->set('filter_on', FALSE);
		}
		
		$this->filtered_list = $filtered_list_query; //so that the calling class itself can access this list, not just the view template
		//maybe jus should return rather than setting $this->  ?
		return $filtered_list_query;
	}

	private function print_title($search_string = '', $selected_performer = '', $selected_venue = '', $selected_tag_array = [], $selected_exclude_tag_array = '', $performers = [], $venues = [], $all_tags = []) {
		$print_title = '';
		if ($search_string) {
			$print_title = $print_title . $search_string;
			$print_title = $print_title . " | ";
		}
		foreach($performers as $performer_id => $performer_title) {
			if($performer_id == $selected_performer && $performer_id != 0) {
				$print_title = $print_title . $performer_title;
				$print_title = $print_title . " | ";
			}
		}
		foreach($venues as $venue_id => $venue_title) {
			if($venue_id == $selected_venue) {
				$print_title = $print_title . $venue_title;
				$print_title = $print_title . " | ";
			}
		}
		foreach($all_tags as $tag_id => $tag_title) {
			if(in_array($tag_id, $selected_tag_array)) {
				$print_title = $print_title . $tag_title;
				$print_title = $print_title . ", ";
			}
		}

		return substr($print_title, 0, -2);
	}

	private function page_title($search_string = '', $selected_performer = '', $selected_venue = '', $selected_tag_array = [], $selected_exclude_tag_array = '', $performers = [], $venues = [], $all_tags = []) {
		$page_title = '';
		foreach($performers as $performer_id => $performer_title) {
			if($performer_id == $selected_performer && $performer_id != 0) {
				$page_title = $page_title . substr($performer_title, 0, 1);
			}
		}
		foreach($venues as $venue_id => $venue_title) {
			if($venue_id == $selected_venue) {
				$page_title = $page_title . substr($venue_title, 0, 1);
			}
		}
		foreach($all_tags as $tag_id => $tag_title) {
			if(in_array($tag_id, $selected_tag_array)) {
				$page_title = $page_title . substr($tag_title, 0, 1);
			}
		}
		if ($search_string) {
			$page_title = $page_title . substr($search_string, 0, 1);
		}
		if($page_title == '') {
			$page_title = 'Home';
		}

		return $page_title;
	}
}
?>