<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\StaticFunctionController;
use App\Controller\CustomlistsController;
use App\Model\Entity\Song;
use App\Model\Entity\SongTag;
use App\Model\Entity\Tag;
use App\Model\Entity\SetSong;
use App\Model\Entity\Performer;

/**
 * Viewer Controller
 *
 * @property \App\Model\Table\SongsTable $Songs
 */
class ViewerController extends AppController
{
    /*
    
    [1, // Piano],
    [2, // Irish],
    [3, // Waltz],
    [5, // Rock&Roll],
    [6, // 60s],
    [13, // Lively (Fast)],
    [14, // 70s],
    [15, // AllMixedUp],
    [16, // 80s],
    [17, // Country],
    [18, // Rock],
    [19, // Soul],
    [20, // Slow],
    [21, // Christmas],
    [22, // Original],
    [24, // Swing],
    [25, // Ballad],
    [26, // Humour],
    [27, // Folk],
    [28, // Harmony],
    [29, // Chorus],
    [30, // Singalong],
    [31, // Gaeilge],
    [34, // Learn],
    [35, // Shanty],
    [36, // 10s],
    [37, // 50s],
    [38, // Australian],
    [39, // Blues],
    [40, // War],
    [41, // Children],
    [42, // Nostalgia],
    [43, // 90s],
    [44, // Solo],
    [45, // Latin],
    [46, // Positive],
    [47, // Show],
    [48, // Scottish],
    [49, // French],
    [50, // Guitar],
    [51, // Guitar],
    [52, // Jazz],
    [53, // Party],
    [54, // Protest],
    [55, // Harvest],
    [59, // 00s],
    [60, // Musicals],
    [61, // 40s],
    [62, // 40s],
    [63, // Movies],
    [64, // Speciality],
    [65, // AMU],
    
    
    */
    private $filter_definition_sets = [
        'Palette E-AMU' => [
            [
                'tags' =>  [
                    68, // Banker
                    15, // AllMixedUp
                ],
                'performers' => [
                    1,  //Euge
                    -3,  //midge
                ],
            ],[
                'tags' =>  [
                    13, // Lively (Fast)
                    -68, // Banker
                    15, // AllMixedUp
                ],
                'performers' => [
                    1,  //Euge
                    -3,  //midge
                ],
            ],[
                'tags' =>  [
                    30, // Singalong
                    15, // AllMixedUp
                ],
                'performers' => [
                    1,  //Euge
                    -3,  //midge
                ],
            ], [
                'tags' =>  [
                    20, // Slow
                    -30, // Singalong
                    15, // AllMixedUp
                ],
                'performers' => [
                    1,  //Euge
                    -3,  //midge
                ],
            ], [
                'tags' =>  [
                    25, // Ballad
                    -30, // Singalong
                    15, // AllMixedUp
                ],
                'performers' => [
                    1,  //Euge
                    -3,  //midge
                ],
            ], [
                'tags' =>  [
                    29, // Chorus
                    -30, // Singalong
                    15, // AllMixedUp
                ],
                'performers' => [
                    1,  //Euge
                    -3,  //midge
                ],
            ], [
                'tags' =>  [
                    44, // Solo
                    15, // AllMixedUp
                ],
                'performers' => [
                    1,  //Euge
                    -3,  //midge
                ],
            ], [
                'tags' =>  [ //anything that fell through the cracks of the above....
                    -13, // Lively (Fast)
                    -20, // Slow
                    -25, // Ballad
                    -29, // Chorus
                    -30, // Singalong
                    -44, // Solo
                    -21, // Christmas,
                    15, // AllMixedUp
                ],
                'performers' => [
                    1,  //Euge
                    -3,  //midge
                ],
            ], 
        ],
        'Paette E-Session' => [
            ['tags' =>  [ 29, /* Chorus */                          ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 30, /* Singalong */   -29, /* Chorus */     ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 27, /* Folk */        -30, /* Singalong */ -29, /* Chorus */        ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [  2, /* Irish */                              ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 35, /* Shanty */      -30, /* Singalong */ -29, /* Chorus */      ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 13, /* Lively (Fast) */ -35, /* Shanty */     -30, /* Singalong */ -29, /* Chorus */  ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 25, /* Ballad */    -13, /* Lively (Fast) */ -35, /* Shanty */     -30, /* Singalong */ -29, /* Chorus */     ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 44, /* Solo */          ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 38, /* Australian */    ], 'performers' => [ 1,  /* Euge */ ]],
            //  ['tags' =>  [ 53, /* Party */      -13, /* Lively (Fast) */ -35, /* Shanty */     -30, /* Singalong */ -29, /* Chorus */     ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 46, /* Positive */      ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 31, /* Gaeilge */       ], 'performers' => [ 1,  /* Euge */ ]],
            ['tags' =>  [ 34, /* Learn */         ], 'performers' => [ 1,  /* Euge */ ]],
        ],
        'Palette M-AMU' => [
            ['tags' =>  [ 15, /* AllMixedUp */    ], 'performers' => [ 3,  /* Midge */]]
        ],
    ];

	public $paginate = [
			'limit' => 35
	];
	
	//Each of the methods below has a corresponding Template, for which the method sets the required variables
	
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index() {
		$this->loadComponent('songlist');
		$this->songlist->setPagination('off');
		$this->songlist->setSortBy('title','ASC');
		$filters_from_queryparams = $this->songlist->get_filters_from_queryparams();
		$this->set('filtered_list', 
		     $this->songlist->filtered_songlist_html(
		         $filters_from_queryparams
		     )
		);
		//now $filtered_list is available in the view.
		$this->set('title', $this->page_title);
		$this->set('filter_definition_sets', $this->filter_definition_sets);
		//pass cl_data to the sidebar in a way that won't clear the data sent to the edit pane, when the custom() method calls the index() method 
		$this->set('cl_sidebar_data', $filters_from_queryparams['cl_data']);

		//make customlists available for selection
		$customlistscontroller = new CustomlistsController;
		$customlists = $this->paginate($customlistscontroller->Customlists);

		$this->set(compact('customlists'));
	}
	
	public function palette() {
	    $this->loadComponent('songlist');
	    
	    if ($this->getRequest()->is(array('post', 'put', 'get'))) {
	        if ($this->getRequest()->is(array('get'))) {
	            $query_parameters = $this->getRequest()->getQuery();
	        } else {
	            $query_parameters = $this->getRequest()->getData();
	        }
	    }

	    if(array_key_exists('filter_set', $query_parameters)) {
	        parse_str($query_parameters['filter_set'], $filter_set);
	    } else {
	        debug("Filter_set not defined. Halting script"); die();
	        if(array_key_exists('palette_set', $query_parameters)) {
	            $filter_set = $sort_definition_sets[$query_parameters['palette_set']];
	        } else {
	            $filter_set = $sort_definition_sets['Euge AMU'];
	        }
	    }

	    $filtered_data = [];
	    foreach ($filter_set as $filter_definition) {
	        $filtered_lists[] = $this->songlist->filtered_songlist_html($filter_definition);
	    }
	    //debug($filtered_data);
	    $this->set('filtered_lists', $filtered_lists);
	    $this->set('filter_definition_sets', $this->filter_definition_sets);
	}
	
	
	//For selecting songs to define a custom list
	public function custom() {	    
	    $this->loadComponent('songlist');
	    $filters_from_queryparams = $this->songlist->get_filters_from_queryparams();
	    
	    //generate the custom list, with all the attributes of the songs in the edit (right-hand) pane
	    //first load all required models, set up the basic query, then later extend the query to include only the relevant songs
	    $this->loadModel('Songs');
	    $this->loadModel('Events');
	    $this->loadModel('SongPerformances');
	    $this->loadModel('SongVotes');
	    
	    //basic query
	    $custom_list_songs_query = $this->Songs->find();
        //if the cl_data includes an id (which it almost certainly will) get the latest stored version of that custom list and relace cl_data with it.
            //an ID is specified, so that custom list has already been saved - edit the latest version regardless what songs were passed in the url. It may be a saved link with outdated data.
            //just the selected custom list

	    if($filters_from_queryparams['cl_data']['action'] == 'edit') {
	        $custom_list_songs_query->Where(['`Songs`.`id` IN' =>  $filters_from_queryparams['cl_data']['data']]);
	        $custom_list_songs_query->order(['title' => 'ASC']);
	    }
	    //Note an ID won't be passed if it's a blank custom builder!!! $filters_from_queryparams['cl_data']['action'] == 'add' In which cases you want no songs in $custom_list_songs_query
	    if($filters_from_queryparams['cl_data']['action'] == 'add') {
	        $custom_list_songs_query = null;
	    }
	    //send these songs to the custom.php view
	    $this->set('custom_list_songs', $custom_list_songs_query);
	    //also send the cl_data to the view
	    $this->set('cl_data', $filters_from_queryparams['cl_data']);
	    
	    
	    //set up the left-hand side index - set title and filter_definition_sets variables
	    $this->index();
	    
	}
}
