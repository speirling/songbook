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
    
	public function filterAllSongs($event = Null) {
	
		$controller = $this->_registry->getController();
	
		$controller->loadModel('Songs');
		$controller->loadModel('Events');
		$controller->loadModel('SongPerformances');
		$controller->loadModel('SongVotes');
	
		$filtered_list_query = $controller->Songs->find();
		
		$filtered_list_query->select(['id', 'title', 'written_by', 'performed_by', 'base_key', 'content'], false);
		$filtered_list_query->contain(['SongTags'=>['Tags']]);
		$filtered_list_query->contain(['SetSongs.Performers']);
	
		// start song performances -------------------
		if (is_null($event)) {
			//find all of the songs played in the last 3 hours (supposed to be current event) and add a parameter that identifies them as played
			$event_start = strtotime('-3 hours');
			$event_end = strtotime('-0 hours');
		} else {
			//find all of the songs played during the event - assume event time +/- event duration - and restrict the final list to just those songs
			if(isset($event->duration_hours) && $event->duration_hours > 0) {
				$event_duration_seconds = $event->duration_hours * 60 * 60;
			} else {
				$event_duration_seconds = 3 * 60 * 60;
			}
			$event_start = strtotime($event->timestamp) - $event_duration_seconds;
			$event_end = strtotime($event->timestamp) + $event_duration_seconds;
		}

		$performance_conditions = [];
		$performance_query = $controller->SongPerformances->find();
		$performance_query->Where("`SongPerformances`.`timestamp` BETWEEN \"" . date("Y-m-d H:i:s", $event_start)."\" AND \"".date("Y-m-d H:i:s", $event_end)."\"");
		$performance_query->distinct('song_id');
		$performance_list = $performance_query->extract('song_id');
		
		$song_id_list = [];
		foreach($performance_list as $id => $song_id) {
			array_push($song_id_list, $song_id);
		}

		if (sizeof($song_id_list) > 0) {
			$song_id_string = str_replace(['[', ']'], ['(', ')'], json_encode($song_id_list));
			if(is_null($event)) {
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
		$vote_list = $vote_query->extract('song_id');
	
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
		if ($controller->request->is(array('post', 'put', 'get'))) {
			if ($controller->request->is(array('get'))) {
				$query_parameters = $controller->request->query;
			} else {
				$query_parameters = $controller->request->data;
			}
			
			/*
			 * allow sorting by
			 *              songbook/dashboard/print-lyric-sheets?unpaginated&sort=title
			 *              songbook/dashboard?sort=title
			 * etc.
			 */ 
			if($query_parameters['sort'] === 'title') {
			    $filtered_list_query->order(['title' => 'ASC']);
			}
			    
			// Title: Song Title text search
			if (array_key_exists('text_search', $query_parameters) && $query_parameters['text_search']) {
				$filter_on = true;
				$search_string = $query_parameters['text_search'];
				$filtered_list_query->where(['Songs.title LIKE' => '%'.$search_string.'%']);
			} else {
				$search_string = '';
			}
	
			// Tags: Limit the result to songs that are associated with any of the passed array of tags
			/*
			 * This has to be done as a subquery, because a HAVING COUNT() must be used to ensure that only songs that are associated with _all_ of the specified tags will be displayed.
			 * That statement interferes with larger more complex queries - e.g. filtered byt Tag _and_ Performer, and can filter out any records that have multiple entries in the final query.
			 *                 $filtered_list_query->having(['COUNT(Songs.id) = ' => sizeof($selected_tag_array)]);
			 * Keeping the HAVING COUNT() inside a subquery seems to avoid that problem
			 */
			if (
					array_key_exists('filter_tag_id', $query_parameters)
					&& $query_parameters['filter_tag_id']
					&& $query_parameters['filter_tag_id'] != 'Tag...'
				) {
				$filter_on = true;
				$selected_tag_array = $query_parameters['filter_tag_id'];
				$subquery_SongWithAllTags = $controller->Songs->find();
				$subquery_SongWithAllTags->matching(
					'SongTags.Tags', function ($q) use ($selected_tag_array)  {
					$q->where(['Tags.id IN' => $selected_tag_array]);
					return $q;
					}
				);
				$subquery_SongWithAllTags->group('Songs.id');
				$subquery_SongWithAllTags->having(['COUNT(Songs.id) = ' => sizeof($selected_tag_array)]);
				$filtered_list_query->Join([
					'table' => $subquery_SongWithAllTags,
					'alias' => 'subquery_SongWithAllTags',
					'type' => 'INNER',
					'conditions' => '`subquery_SongWithAllTags`.`Songs__id` = `Songs`.`id`'
				]);
			} else {
				$selected_tag_array = [];
			}
			
			$controller->set('filter_tag_id', $selected_tag_array);
			
			// Performer: Limit the result to songs associated with a specific performer
			if (array_key_exists('performer_id', $query_parameters) && $query_parameters['performer_id']) {
				$filter_on = true;
				$selected_performer = $query_parameters['performer_id'];
				$filtered_list_query->matching(
					'SetSongs.Performers', function ($q) use($selected_performer)  {
						return $q->where(['Performers.id' => $selected_performer]);
					}
				);
			} else {
				$selected_performer = '';
			}
			
			$controller->set('performer_id', $selected_performer);

            //Exclude tags:  - only songs that do NOT contain ALL of the selected tags here will be displayed
            $exclude_all = false; // there's no interface to set this yet, and it seems that it would be more effective to exclude all songs that contain any of the selected tage (smaller list)
            if (
                    array_key_exists('exclude_tag_id', $query_parameters)
                    && $query_parameters['exclude_tag_id']
                    && $query_parameters['exclude_tag_id'] != 'Tag...'
            ) {
                $filter_on = true; //Can lead to Maximum execution time fatal error! .... but pagination doesn't pass filter queries so is not useful for filtered lists, so fatal error may be preferable!
                ini_set('max_execution_time', 100); //to compensate for previous line
                $selected_exclude_tag_array = $query_parameters['exclude_tag_id'];
            } else {
                $selected_exclude_tag_array = [];
            }

            // Venue :  limit the result to songs that were placed withtin an event at the specified venue
			if (array_key_exists('venue', $query_parameters) && $query_parameters['venue']) {
				$filter_on = true;
				$selected_venue = $query_parameters['venue'];
				//find all of the events that were at this venue
				$venue_query = $controller->Events->findAllByVenue($selected_venue);
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
	
			} else {
				$selected_venue = '';
			}
		} else {
			//In this case, no search parameters have been passed to the song list
			$search_string = '';
			$selected_performer  = '';
			$selected_tag_array = [];
			$exclude_all = false;
			$selected_exclude_tag_array = [];
			$selected_venue = '';
		}

		// Avoid multiples of a specific song
		$filtered_list_query->distinct(['Songs.id']);
        if ($filter_on) {
            $filtered_list_query->order(['Songs.title' =>'ASC']);
        } else {
            $filtered_list_query->order(['Songs.id' =>'DESC']);
        }
        //echo debug($filtered_list_query); die();

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

		$controller->set('search_string', $search_string);
		$controller->set('selected_performer', $selected_performer);
		$controller->set('selected_tags', $selected_tag_array);
		$this->filtered_list = $filtered_list_query; //so that the calling class can access this list, not just the ctp template
		if($filter_on) {
			$controller->set('filtered_list', $filtered_list_query);
			$controller->set('filter_on', TRUE);
		} elseif(array_key_exists("unpaginated", $query_parameters)) {
		    $controller->set('filtered_list', $filtered_list_query);
		    $controller->set('filter_on', TRUE); //prevents the footer haveing 'next' & 'previous' buttons
		} else {
		    $controller->set('filtered_list', $controller->paginate($filtered_list_query));
		    $controller->set('filter_on', FALSE);
		}

		//for the print title
		$controller->set('exclude_all', $exclude_all);
		$controller->set('selected_exclude_tags', $selected_exclude_tag_array);

		//for the print title
		$controller->set('print_title', $this->print_title($search_string, $selected_performer, $selected_venue, $selected_tag_array, $selected_exclude_tag_array, $performers, $venues, $all_tags));
		$controller->page_title = $this->page_title($search_string, $selected_performer, $selected_venue, $selected_tag_array, $selected_exclude_tag_array, $performers, $venues, $all_tags);
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