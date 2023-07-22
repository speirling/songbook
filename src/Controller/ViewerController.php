<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\StaticFunctionController;
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

	public $paginate = [
			'limit' => 35
	];
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->loadComponent('songlist');
		$this->songlist->setPagination('off');
		$this->songlist->setSortBy('title','ASC');
		$this->set('filtered_list', 
		    $this->songlist->filterAllSongs(
    		    $this->songlist->get_filters_from_queryparams()
    		)
		);
		//now $filtered_list is available in the view.
		$this->set('title', $this->page_title);
	}
	
	public function palette() {
	    
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
	    
	    
	    $this->loadComponent('songlist');
	    $tag_sets = [
	        'Euge AMU' => [
	               [
	                13, // Lively (Fast)
	                15, // AllMixedUp
	            ], [
	                20, // Slow
	                15, // AllMixedUp
	            ], [
	                 2, // Irish
	                15, // AllMixedUp
	                -13, // Lively (Fast)
	                -20, // Slow
	            ], [
	                25, // Ballad
	                15, // AllMixedUp
	            ], [
	                29, // Chorus
	                15, // AllMixedUp
	            ], [
	                 5, // Rock&Roll
	                15, // AllMixedUp
	            ], [
	                30, // Singalong
	                15, // AllMixedUp
	            ], [
	                44, // Solo
	                15, // AllMixedUp
	            ], [
	                59, // 00s
	                15, // AllMixedUp
	            ], [
	                43, // 90s
	                15, // AllMixedUp
	            ], [
	                16, // 80s
	                15, // AllMixedUp
	            ], [
	                14, // 70s
	                15, // AllMixedUp
	                -20, // Slow
	            ], [
	                 6, // 60s
	                15, // AllMixedUp
	            ], [
	                27, // Folk
	                15, // AllMixedUp
	            ],
	        ],
	        'Euge Session' => [
	            [
	                29, // Chorus
	            ], [
	                30, // Singalong
                ], [
                    27, // Folk
                ], [
                    2, // Irish
                ], [
                    35, // Shanty
                ], [
                    13, // Lively (Fast)
                ], [
                    25, // Ballad
                ], [
                    44, // Solo
                ], [
                    38, // Australian
                ], [
                    53, // Party
                ], [
                    54, // Protest
                ], [
                    46, // Positive
                ], [
                    31, // Gaeilge
                ], [
                    34, // Learn
                ],
	                                                                    
	        ],
	    ];
	    if ($this->getRequest()->is(array('post', 'put', 'get'))) {
	        if ($this->getRequest()->is(array('get'))) {
	            $query_parameters = $this->getRequest()->getQuery();
	        } else {
	            $query_parameters = $this->getRequest()->getData();
	        }
	    }
	    if(array_key_exists('palette_set', $query_parameters)) {
	       $selected_tag_set = $query_parameters['palette_set'];
	    } else {
	        $selected_tag_set = 'Euge AMU';
	    }
	    
	    $sort_definition_set = [];
	    foreach($tag_sets[$selected_tag_set] as $this_tag_set) {
    	    $sort_definition_set[] = [
    	            'param' => '',
    	            'direction' => '',
    	            'search_string' => '',
    	            'performer_id'  => '1', //Euge
    	            'tag_array' => array_filter( $this_tag_set, function( $val ) { return   (0<$val); } ),
    	            'exclude_all' => false,
    	            'exclude_tag_array' => array_map(
    	                function($n) { return ($n * -1); },
    	                array_filter( 
    	                    $this_tag_set, 
    	                    function( $val ) { return   (0>$val); } 
            	        )
            	    ),
    	            'selected_venue' => '',
    	            'paginate' => false,
    	    ];
	    }

	    $filtered_data = [];
	    foreach ($sort_definition_set as $this_sort_definition) {
	        $filtered_data[] = [
	            'query' => $this->songlist->filterAllSongs($this_sort_definition),
	            'print_title' =>$this->songlist->print_title, 
	            'page_title' => $this->songlist->page_title,
	            'selected_performer' => $this->songlist->selected_performer,
	            'filter_on' => $this->songlist->filter_on
	        ];
	    }
	    //debug($filtered_data);
	    $this->set('filtered_data', $filtered_data);
	}
	
}
