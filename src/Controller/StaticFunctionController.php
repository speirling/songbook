<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 *  StaticFunctions "Controller"
 *
 * @property \App\Model\Table\TagsTable $Tags
 */
class StaticFunctionController extends AppController
{
	public static $NOTE_VALUE_ARRAY = Array(
			'C'  => 0,
			'C#' => 1,
			'Db' => 1,
			'D'  => 2,
			'D#' => 3,
			'Eb' => 3,
			'E'  => 4,
			'E#' => 5,
			'Fb' => 4,
			'F'  => 5,
			'F#' => 6,
			'Gb' => 6,
			'G'  => 7,
			'G#' => 8,
			'Ab' => 8,
			'A'  => 9,
			'A#' => 10,
			'Bb' => 10,
			'B'  => 11,
			'B#' => 0,
			'Cb' => 11
	);
	
	public static $VALUE_NOTE_ARRAY_DEFAULT = Array(
			0 => 'C',
			1 => 'C#',
			2 => 'D',
			3 => 'Eb',
			4 => 'E',
			5 => 'F',
			6 => 'F#',
			7 => 'G',
			8 => 'G#',
			9 => 'A',
			10=> 'Bb',
			11=> 'B'
	);
	
	public static $VALUE_NOTE_ARRAY_SHARP = Array(
			0 => 'C',
			1 => 'C#',
			2 => 'D',
			3 => 'D#',
			4 => 'E',
			5 => 'F',
			6 => 'F#',
			7 => 'G',
			8 => 'G#',
			9 => 'A',
			10=> 'A#',
			11=> 'B'
	);
	
	public static $VALUE_NOTE_ARRAY_FLAT = Array(
			0 => 'C',
			1 => 'Db',
			2 => 'D',
			3 => 'Eb',
			4 => 'E',
			5 => 'F',
			6 => 'Gb',
			7 => 'G',
			8 => 'Ab',
			9 => 'A',
			10=> 'Bb',
			11=> 'B'
	);
	
	public static $key_transpose_parameters;
	
	public static function duration_string_to_seconds($individual_duration_string) {
		if($individual_duration_string !== '') {
			//assumes duration is mm:ss - so less than a minute would be 00:ss
			$time_bits = preg_split('/:/', $individual_duration_string);
			$duration_seconds = $time_bits[0] * 60 + $time_bits[1];
		} else {
			$duration_seconds = 0;
		}
		return $duration_seconds;
	}
	
	public static function seconds_to_duration_string($duration_seconds) {
		$exact = $duration_seconds/60;
		$hours = floor($exact);
		$minutes = ($exact - $hours) * 60;
	
		return str_pad($hours, 2, '0', STR_PAD_LEFT).':'.str_pad($minutes, 2, '0', STR_PAD_LEFT);
	}

	public static function chord_replace_callback($chord) {
		$replaced_chord = $chord[1];
		$fullsize_class = '';
		
		if(strpos($replaced_chord, '!') !== false) {
			//This is one of those chords followed by whitepace, that needs to be set to greater than 0 space.
			//I'll use a class for that
			$fullsize_class = " full-width";
			$replaced_chord = str_replace('!', '', $replaced_chord);
		}
		if(StaticFunctionController::$key_transpose_parameters['transpose']) {
		    /*
		    debug(StaticFunctionController::$key_transpose_parameters);
		    debug($replaced_chord);
		    // */
			$replaced_chord = StaticFunctionController::transpose_chord($replaced_chord, StaticFunctionController::$key_transpose_parameters['base_key'], StaticFunctionController::$key_transpose_parameters['display_key']);
			/*
			debug($replaced_chord);
			// */
		}
		//if there's a bass modifier, give it its own html
	
		if(strpos($replaced_chord, '/') !== false) {
			$parts = explode('/', $replaced_chord);
			$replaced_chord = $parts[0] . '<span class="bass_note_modifier separator">/</span><span class="bass_note_modifier note">' . $parts[1] . '</span>';
		}
	
		return '<span class="chord' . $fullsize_class . '"><span class="chord-symbol-align">'.$replaced_chord.'</span></span>';  //.chord-symbol-align required to position chord symbol relative to the .chord container, which is positioned inside the lyric word.
	}

	public static function score_replace_callback($score_params) {
		$score_path_info = pathinfo($score_params[1]);

		if(array_key_exists('display_key', StaticFunctionController::$key_transpose_parameters)) { //if no key has been chosen - and the default key is used - then this is not set.
		    if(array_key_exists('capo', StaticFunctionController::$key_transpose_parameters)) {
		        //if there's a capo, the score should if possible match the key of the chords
		        $score_filename = $score_path_info['filename'] . "_" .
		            StaticFunctionController::shift_note(
		                StaticFunctionController::$key_transpose_parameters['display_key'],
		                -1 * StaticFunctionController::$key_transpose_parameters["capo"]
		            ) .
		            "." . $score_path_info['extension'];

		    } else {
 		        $score_filename = $score_path_info['filename'] . "_" . StaticFunctionController::$key_transpose_parameters['display_key'] . "." . $score_path_info['extension'];
		    }

		    if(!file_exists(dirname(__FILE__)."/../../webroot/score/" . $score_filename)) {
				$score_filename = $score_path_info['filename'] . "." . $score_path_info['extension'];
			}
		} else {
            $score_filename = $score_path_info['filename'] . "." . $score_path_info['extension'];
		}

		return '<img src="/songbook/score/' . $score_filename . '" />';
	}
	
    public static function convert_song_content_to_HTML($content, $base_key = NULL, $display_key = NULL, $capo = NULL) {
		if (is_null($base_key)) {
			StaticFunctionController::$key_transpose_parameters = array(
				'transpose' => false
			);
		} elseif (is_null($display_key)) {
			StaticFunctionController::$key_transpose_parameters = array(
				'transpose' => false
			);
		} else {
			StaticFunctionController::$key_transpose_parameters = array(
				'transpose' => true,
			    'base_key' => $base_key,
			    'display_key' => $display_key,
			    'capo' => $capo
			);
		}
		/*
		debug(StaticFunctionController::$key_transpose_parameters);
		// */
		$contentHTML = $content;
		//if special characters have found their way into  lyrics in the database, get rid of them
		$contentHTML = preg_replace('/&nbsp;/', ' ', $contentHTML);
		$contentHTML = preg_replace('/<br>/', "
", $contentHTML);  //I can't get a new line inserted using regex.... maybe subsequent "replace"s change it back?

		//-------------
        // chords that are close together - [Am][D] etc ... even [Am]  [D].... should be separated by characters equal in width to the chord (or by margin in css?)
        // I'll mark these kinds of chords with "!" so that I can set their class in  chord_replace_callback()
		// In PHP "\h" matches a 'horizontal whitespace' character so the expression '/\](\h*?)\[/' should find relevant chords
		$contentHTML = preg_replace('/\](\h*?)\[/', '!]$1[', $contentHTML);
		// Previous regex doesn't catch chords at the end of a line, outside a word. They should also be full-width.
		$contentHTML = preg_replace('/\](\s*?)[\n\r]/', '!]$1', $contentHTML);
		//-------------
		
		//if a 'score' reference is included, insert the image referred to in it. It should be in the webroot/score/ directory
		//(has to be done before 'word' spans are inserted)
		//(also before curly brackets are ignored)
		$contentHTML = preg_replace_callback('/\{score:(.*?)\}/', 'self::score_replace_callback', $contentHTML);
                		
		//-------------
		// surround each word in a line with a span so that you can prevent them breaking at the point where there's a chord, if the line has to wrap.
		// First, replace each valid word boundry with '</span><span class="word">'. Note this will leave an extra <\/span> at the beginning of the line, and an extra <span class="word"> at the end. they'll have to be dealt with after.
		// it will also mark whitespace as a word - not sure that's a problem, but it's unintuitive and untidy so it'll have to be dealt with after also.
		
		$exceptions = array( 
		    "[", 
		    "]",
		    "/",
		    "'",
		    '"',
		    '-',
		    '.',
		    "?",
		    "!",
		    "*",
		    ":",
		    ";",
		    "#",
		    "(",
		    ")",
		    "{",
		    "}",
		    "x{0040}",   //at symbol (commat)    @
		    "x{00A9}",   //Copyright symbol      ©
		    "x{2018}",   //OpenCurlyQuote        ‘
		    "x{2019}",   //CloseCurlyQuote       ’
		    "x{201C}",   //OpenCurlyDoubleQuote  “
		    "x{201D}"    //CloseCurlyDoubleQuote ”
		);
		$exception_string = "";
		$ignore_string = "";
		foreach($exceptions as $e) {
		    $exception_string = $exception_string . "\\" . $e;
		    $ignore_string = $ignore_string . "\\" . $e . ".(*SKIP)(*FAIL)|";
        }
        
        // ignoring html and chords first, and also &#38; then the "ignore list" above
        //a problem arose in one song with "de[G]ad.[G#dim]" at the end of a line. The ".[" ended up with a word boundary between . and [ . so add an exception for characters in front of [: \.? \[.*?\][\w]?
        //debug($ignore_string);
        $contentHTML = preg_replace('/<.*?>(*SKIP)(*FAIL)|[' . $exception_string . '^\n]?\[.*?\][\w]?(*SKIP)(*FAIL)|' . $ignore_string . '\b/u', '</span><span class="word">', $contentHTML); 
        //debug($contentHTML);
        //if a chord is at the start of a line, instead of inside a word, it is missed by the regex above.
        //Similarly, an apostrophe at the start of a line, or double quotes
        // I had a problem with one song with :: <div class="line">    [A]You're</span> :: ... i.e whitespace before [ at the start of the line. So allow variable no. of whitespace before each of the non-word charaters at the start of line
        $contentHTML = preg_replace('/^\s*?([' . $exception_string . '])/mu', '</span><span class="word">$1', $contentHTML);
		$contentHTML = preg_replace('/([' . $exception_string . '])\s*?$/mu', '$1</span><span class="word">', $contentHTML);
		
		/*
		 * @todo: a song starting with a line of chords, no spaces between them - is counted as a line without chords.
		 */

		//the above regex misses apostrophe at the end of a line of a line ("...'")
		$contentHTML = preg_replace('/\'$/m', '\'</span><span class="word">', $contentHTML);
		//commas aren't caught effectively by the regex above - leaving '</span><span class="word">,' in places where it should be ',</span><span class="word">'
		$contentHTML = str_replace('</span><span class="word">,', ',</span><span class="word">', $contentHTML);
		//previous regex surrounds whitespace with word spans - remove them:
		$contentHTML = preg_replace('/<span class="word">(\s*?)<\/span>/u', '$1', $contentHTML);
		//-------------

		
		// replace end-of-line with the end of a div and the start of a line div
		$contentHTML = preg_replace('/\n/','</div><div class="line">', $contentHTML);
		// empty lines - put in a non-breaking space so that they don't collapse
		$contentHTML = preg_replace('/<div class=\"line\">[\s]*?<\/div>/', '<div class="line">&#160;</div>', $contentHTML);
		
		
		//-------------
		//anything in square brackets is taken to be a chord and should be processed to create chord html - including bass modifier
 		$contentHTML = preg_replace_callback('/\[(.*?)\]/', 'self::chord_replace_callback', $contentHTML);
		//-------------

		//&nbsp; doesn't work in XML unless it's specifically declared..... this was added when the songbook was xml based, but still works here so...
		$contentHTML = preg_replace('/&nbsp;/', '&#160;', $contentHTML); 
		//Finally, wrap the song lyric content in a lyrics-panel div
		$contentHTML = '<div class="lyrics-panel"><div class="line">'.$contentHTML.'</div></div>';

		//clean up from word wrapping regex. If you do this any earlier it misses the '</span>' at the very start
		//get rid of the <\span> at the start of the line:
		$contentHTML = preg_replace('/<div class="line">(\s*?)<\/span>/', '<div class="line">$1', $contentHTML);
		//and the <span class="word"> at the end:
		$contentHTML = preg_replace('/<span class="word">[\.\s\r\n]*<\/div>/u', '</div>', $contentHTML);
		
		//convert ampersand to xml character entity &#38; to avoid errors with the DOM command
		$contentHTML = preg_replace('/&([^#n])/', '&#38;$1', $contentHTML);
		
		return $contentHTML;
	}
	
	public static function convert_content_HTML_to_columns(
	        $contentHTML, 
	        $song_parameters, 
    	    $print_page_configuration = [
    	        "page_height" => 1000, //px
    	        "page_width" => 690, //px
    	    ],
	        $print_size = 'default'
	    ) {
        //this is called from SongsController -> printable() with $contentHTML set to the output from convert_song_content_to_HTML
        //debug($contentHTML);
        
        
        $print_size_configuration = \Cake\Core\Configure::read('Songbook.print_size');
        $size_config_values = $print_size_configuration[$print_size];
        
        $title_height = $size_config_values['font_sizes']['title'] * 1.5 
                      + $size_config_values['font_sizes']['attributions'] * 1.5 
                      + $size_config_values['content_padding'] * 2;
                      
        $p1_content_height = $print_page_configuration['page_height']
                           - $title_height
                           - $size_config_values['content_padding'] * 2;
                      
        $p2_content_height = $print_page_configuration['page_height']
                           - $size_config_values['content_padding'] * 2;
                      
        $height_of_line_without_chords = $size_config_values['font_sizes']['lyrics'] * 1.125;
        
        $height_of_line_with_chords = $size_config_values['font_sizes']['lyrics'] * 1.125 
                                    + $size_config_values['font_sizes']['chords'] * 1.125;
                                    
        $height_of_wrapped_line_without_chords = $size_config_values['font_sizes']['lyrics'] * 1.125 * 2
                                               + $size_config_values['lyric_line_top_margin'];
                                    
        $height_of_wrapped_line_with_chords = $size_config_values['font_sizes']['lyrics'] * 1.125 * 2
                                            + $size_config_values['font_sizes']['chords'] * 1.125 * 2
                                            + $size_config_values['lyric_line_top_margin'];
        
        $px_per_column_1_column = ($print_page_configuration['page_width'] / 1 - $size_config_values['content_padding'] * 2);
        $characters_per_column_1_column = $px_per_column_1_column / $size_config_values['lyric_width_per_100_characters'] * 100;
        
        $px_per_column_2_column = ($print_page_configuration['page_width'] / 2 - $size_config_values['content_padding'] * 2);
        $characters_per_column_2_column = $px_per_column_2_column / $size_config_values['lyric_width_per_100_characters'] * 100;
        
        $px_per_column_3_column = ($print_page_configuration['page_width'] / 3 - $size_config_values['content_padding'] * 2);
        $characters_per_column_3_column = $px_per_column_3_column / $size_config_values['lyric_width_per_100_characters'] * 100;
        
        $px_per_column_4_column = ($print_page_configuration['page_width'] / 4 - $size_config_values['content_padding'] * 2);
        $characters_per_column_4_column = $px_per_column_4_column / $size_config_values['lyric_width_per_100_characters'] * 100;
                                    
	    //$print_page_configuration[$page_size]
	    $page_parameters = [
	        "page_height" => $print_page_configuration['page_height'],
	        "page_width" => $print_page_configuration['page_width'],
	        "font_size_in_pixels" => $size_config_values['font_sizes']['lyrics'], //px
	        "height_of_page_1_lines" => $p1_content_height / $height_of_line_without_chords, //lines
	        "height_of_page_2_lines" => $p2_content_height / $height_of_line_without_chords, //lines;
	        "p1_content_height" => $p1_content_height,
	        "p2_content_height" => $p2_content_height,
	        "height_of_line_with_chords" => $height_of_line_with_chords, 
	        "height_of_line_without_chords" => $height_of_line_without_chords, // font_size_in_pixels * 1.8,
	        "lyric_line_top_margin" => $size_config_values['lyric_line_top_margin'],
	        "height_of_wrapped_line_without_chords" => $height_of_wrapped_line_without_chords,
	        "height_of_wrapped_line_with_chords" => $height_of_wrapped_line_with_chords,
	        "line_multiplier_wrapped" => $height_of_wrapped_line_without_chords / $height_of_line_without_chords,
	        "line_multiplier_chords" => $height_of_wrapped_line_with_chords / $height_of_wrapped_line_with_chords,
	        "column_width" => [
	            "1_column" => $characters_per_column_1_column, //characters
	            "2_column" => $characters_per_column_2_column, //characters
	            "3_column" => $characters_per_column_3_column, //characters
	            "4_column" => $characters_per_column_4_column //characters
    	    ]
	    ];
	    
	    $page_parameters["style_set_or_song"] = $song_parameters["style_set_or_song"]; //there's got to be a more elegant way of passing this from Controller to page creator
	    $current_page = 1;
				
		$doc = new \DOMDocument('1.0', 'UTF-8');
		/*
		$contentHTML = preg_replace('/<\/span>/', "</span>
", $contentHTML); 
		debug($contentHTML);
		// */
		$doc->loadHTML(mb_convert_encoding($contentHTML, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		
		$xpath = new \DOMXPath($doc);
		$lines = $xpath->query("//div[@class='line']");
		

		$line_stats = self::get_line_stats( $lines, $page_parameters);
		//debug(  $song_parameters["title"]);//debug($line_stats);
		
		$current_column = 1;
		

		list($pages[$current_page], $tbody, $row, $td) = self::create_printable_page($doc, $current_page, $page_parameters, $song_parameters["id"]);
		
		
		//At the top of the first page put the song heading
		//==========================================================
		$row_header = $doc->createElement('tr');
		$tbody->insertBefore($row_header, $row);
		
		$td_header = $doc->createElement('td');
		$row_header->appendChild($td_header);
		
		$title_heading = $doc->createDocumentFragment();

		$title_heading_html = "";
		$title_heading_html = $title_heading_html . "<table class=\"vertical-table attribution song-header\">"                             . "\n" ;
		$title_heading_html = $title_heading_html . "<tr>"                             . "\n" ;
		$title_heading_html = $title_heading_html . "<td class= \"title-table\">"                              . "\n" ;
		$title_heading_html = $title_heading_html . "<h3>" . htmlspecialchars($song_parameters["title"]) .                                       "</h3>"   . "\n" ;
		
		$title_heading_html = $title_heading_html . "<table class=\"vertical-table attribution\">"                             . "\n" ;
		$title_heading_html = $title_heading_html .     "<tr class=\"written-by performed-by\">"                                         . "\n" ;
		if(trim($song_parameters["written_by"]) !== "") {
		    $title_heading_html = $title_heading_html .         "<th class=\"written-by\">" . 'Written By' .                                             "</th>"  . "\n" ;
		    $title_heading_html = $title_heading_html .         "<td class=\"written-by\">" . htmlspecialchars($song_parameters["written_by"]) .                           "</td>"  . "\n" ;
		}
		if(trim($song_parameters["performed_by"]) !== "") {
		    $title_heading_html = $title_heading_html .   		"<th class=\"performed-by\">" . 'Performed By' .                                           "</th>"  . "\n" ;
		    $title_heading_html = $title_heading_html .   		"<td class=\"performed-by\">" . htmlspecialchars($song_parameters["performed_by"]) .                         "</td>"  . "\n" ;
		}
		
		$title_heading_html = $title_heading_html .    "</tr>" . "\n" ;
		$title_heading_html = $title_heading_html . "</table>" . "\n" ;
		
		$title_heading_html = $title_heading_html .    "</td>"  . "\n" ;
		if(trim($song_parameters["current_key"]) !== "") {
		    
		    if(trim($song_parameters["capo"]) !== "") {
        		$title_heading_html = $title_heading_html . "<td class= \"key-capo capo-shown\">"  . "\n" ;
        		$title_heading_html = $title_heading_html .   		"<span class=\"capo-transpose-border\">" . "\n" ;
		        
		        $title_heading_html = $title_heading_html .   		"<span class=\"capo-transpose-layout-holder layout-holder\">" . "\n" ;
		        
		        $title_heading_html = $title_heading_html .   		"<span class=\"capo layout-holder\">" . "\n" ;
		        $title_heading_html = $title_heading_html .   		"<span class=\"heading\">" . 'capo' . "</span>"  . "\n" ;
		        $title_heading_html = $title_heading_html .   		"<span class=\"value\">" . htmlspecialchars($song_parameters["capo"]) . "</span>"  . "\n" ;
		        $title_heading_html = $title_heading_html .   		"</span>" . "\n" ;
		        
		        $title_heading_html = $title_heading_html .   		"<span class=\"transpose layout-holder\">" . "\n" ;
		        $title_heading_html = $title_heading_html .   		"<span class=\"heading\">" . "chords shown in " . "</span>"  . "\n" ;
		        $title_heading_html = $title_heading_html .   		"<span class=\"value\">" . StaticFunctionController::shift_note($song_parameters["current_key"], -1*$song_parameters["capo"]) .         "</span>"  . "\n" ;
		        $title_heading_html = $title_heading_html .   		"</span>" . "\n" ;
		        
		        $title_heading_html = $title_heading_html .   		"</span>" . "\n" ;
		    } else {
        		$title_heading_html = $title_heading_html . "<td class= \"key-capo\">"  . "\n" ;
        		$title_heading_html = $title_heading_html .   		"<span class=\"capo-transpose-border\">" . "\n" ;
		        
		        
		    }
		    
		    $title_heading_html = $title_heading_html .   		"<span class=\"key layout-holder key-layout-holder\">" . "\n" ;
		    $title_heading_html = $title_heading_html .   		"<span class=\"heading\">" . 'Key' .  "</span>"  . "\n" ;
		    $title_heading_html = $title_heading_html .   		"<span class=\"value\">" . htmlspecialchars($song_parameters["current_key"]) . "</span>"  . "\n" ;
		    $title_heading_html = $title_heading_html .   		"</span>" . "\n" ;
		    
		    $title_heading_html = $title_heading_html .   		"</span>" . "\n" ;
		    $title_heading_html = $title_heading_html .    "</td>"  . "\n" ;
		} else {
    		
		}


		$title_heading_html = $title_heading_html .    "</tr>" . "\n" ;
		$title_heading_html = $title_heading_html . "</table>";

		$title_heading->appendXML($title_heading_html);
		$row_header->setAttribute("class", "title-block");
		$td_header->setAttribute("class", "song-title");
		$td_header->setAttribute("colspan", $line_stats['no_of_columns']);
		$td_header->appendChild($title_heading);
		
		
		
		//==========================================================
		
		//Set the lyrics table on the first page to take account of the header
		$page_height = $page_parameters["p1_content_height"];
		$content_height_px = 0; 
		$line_no = 0;
		if ($line_stats['no_of_columns'] === 1) {
		    $column_width = $page_parameters["column_width"]["1_column"];
		    $px_per_column = $px_per_column_1_column;
		} else {
		    $column_width = $page_parameters["column_width"]["2_column"];
		    $px_per_column = $px_per_column_2_column;
		}
		//debug($page_height);
		//debug($column_width);
		foreach($lines as $line) {
		    $line_no = $line_no + 1;
			//set the font size
		    $line->setAttribute("style", "font-size: " . $page_parameters["font_size_in_pixels"] . "px;"); //maybe done now by css staement generated in HTML HEAD?
		    
            $line_contains_chords     = $xpath->query(".//span[contains(@class, 'chord')]"      , $line)->length;
            $line_with_chords_removed = $xpath->query("span[not (@class='chord')]", $line);
			
			//does this line have an image?
			$image = $xpath->query(".//img/@src", $line);
			if($image->length) {
			    $imagesize = getimagesize(str_replace('/songbook/score/', WWW_ROOT . 'score/', $image[0]->textContent));
			    $line_height_px = ($px_per_column/$imagesize[0]) * $imagesize[1];
			} else {
			    if ($line_contains_chords > 0){
			        $line_height_px = $page_parameters["height_of_line_with_chords"];
			    } else {
			        $line_height_px = $page_parameters["height_of_line_without_chords"];
			    }
			}

			$this_line_length = 0;
			foreach($line_with_chords_removed as $this_node) {
			    $this_line_length = $this_line_length + strlen($this_node->textContent) + 1; //the spans found are word spans, the spaces in between them are omitted. Add 1 to allow for one space per span.
			}

			if($this_line_length === 0) {
			    $wrap = 1;
			} else {
			    $wrap = ceil($this_line_length / $column_width);
			}
			
			$content_height_px = $content_height_px + $line_height_px * $wrap + $page_parameters["lyric_line_top_margin"];
			
			if ($content_height_px > $page_height) {
				$current_column = $current_column + 1;
				if($current_column > $line_stats['no_of_columns']) {
					//new page
					$current_page = $current_page + 1;
					$current_column = 1;
					$content_height_px = $line_height_px * $wrap + $page_parameters["lyric_line_top_margin"];
					$line_no = 1;
					$page_height = $page_parameters["p2_content_height"];
					list($pages[$current_page], $tbody, $row, $td) = self::create_printable_page($doc, $current_page, $page_parameters, $song_parameters["id"]);
				} else {
				   //new column
				    $content_height_px = $line_height_px * $wrap + $page_parameters["lyric_line_top_margin"];
				    $line_no = 1;
					$td = $doc->createElement('td');
					$row->appendChild($td);
				}
			}
			/*
			debug(
			    "Page " .               $current_page . 
			    " column " .            $current_column . 
			    " line " .              $line_no .
			    " line length " .       $this_line_length .
			    " has chords? " .       $line_contains_chords . 
			    " wrap : " .            $wrap . 
			    " content_height_px " . $content_height_px . 
			    " page_height " .       $page_height . 
			    " line: \"" .           $line->textContent . "\""
			);
			// */
			
			$td->appendChild($line);
		}
		
		
		$lines_with_chords = $xpath->query("//div[span[@class='chord']]");
		
		$original_content_panel = $xpath->query("//div[@class='lyrics-panel']");
		foreach($original_content_panel as $panel) {
			$panel->parentNode->removeChild($panel);
		}
		
		//$doc->formatOutput = true;
		$return = str_replace("<?xml version=\"1.0\" standalone=\"yes\"?>", "", $doc->saveXML());

		return $return;
	}
	
	private static function get_line_stats(
    	    $array_of_html_lyric_lines,
    	    $page_parameters = array ()
	    ) {
	        
        $total_height_1_column_lines = 0;
        $total_height_2_column_lines = 0;
        $total_height_1_column_px = 0;
        $total_height_2_column_px = 0;
	    
	    $non_zero_length_lines_count = 0;
	    $non_zero_length_lines_total = 0;
	    
	    $number_of_lines = 0;
	    $number_of_wrapped_lines_2_column = 0;
	    
	    $max_length = 0;

	    foreach ($array_of_html_lyric_lines as $this_line) {
	        $number_of_lines = $number_of_lines + 1;
	        //debug($this_line);
	        $xpath = new \DOMXPath($this_line->ownerDocument);
	        $line_with_chords_removed = $xpath->query("span[not (@class='chord')]", $this_line);
	        //Does this line have chords?
	        $line_contains_chords = $xpath->query("span[@class='chord']", $this_line)->length;
	        
	        if ($line_contains_chords > 0){
	            $line_height_px = $page_parameters["height_of_line_with_chords"];
	            $line_height_lines = $page_parameters["line_multiplier_chords"];
	            
	        } else {
	            $line_height_px = $page_parameters["height_of_line_without_chords"];
	            $line_height_lines = 1;
	        }
	        $this_line_length = 0;
	        foreach($line_with_chords_removed as $this_node) {
	            //debug($this_node->textContent);
	            $this_line_length = $this_line_length + strlen($this_node->textContent);
	        }
	        
	        if ($this_line_length > $max_length) {
	            $max_length = $this_line_length;
	        }
	        if($this_line_length > 0) {
	            $non_zero_length_lines_count = $non_zero_length_lines_count + 1;
	            $non_zero_length_lines_total = $non_zero_length_lines_total + $this_line_length;
	        }
	        
	        $additional_line_1_column = ceil($this_line_length / $page_parameters["column_width"]["1_column"]) - 1; 
	        $additional_line_2_column = ceil($this_line_length / $page_parameters["column_width"]["2_column"]) - 1;
	        
	        $total_height_1_column_lines = $total_height_1_column_lines + (1 + $additional_line_1_column * ($page_parameters["line_multiplier_wrapped"] - 1)) * $line_height_lines; //number of lines
	        $total_height_2_column_lines = $total_height_2_column_lines + (1 + $additional_line_2_column * ($page_parameters["line_multiplier_wrapped"] - 1)) * $line_height_lines; //number of lines
	        
	        $total_height_1_column_px = $total_height_1_column_px + $line_height_px * ceil($this_line_length / $page_parameters["column_width"]["1_column"]) + $page_parameters["lyric_line_top_margin"];
	        $total_height_2_column_px = $total_height_2_column_px + $line_height_px * ceil($this_line_length / $page_parameters["column_width"]["2_column"]) + $page_parameters["lyric_line_top_margin"];
	        
	        if($this_line_length > $page_parameters["column_width"]["2_column"]) {
	            $number_of_wrapped_lines_2_column = $number_of_wrapped_lines_2_column + 1;
	        }
	    }
	    
	    if ($total_height_1_column_px < $page_parameters["p1_content_height"]) {
	        // 1 column on one page
	        $no_of_columns = 1;
	        $no_of_pages = 1;
	    } elseif ($total_height_2_column_px < ($page_parameters["p1_content_height"] * 2)) {
	        // 2 columns on one page
	        $no_of_columns = 2;
	        $no_of_pages = 1;
	    } elseif ($total_height_1_column_px < ($page_parameters["p1_content_height"] + $page_parameters["p2_content_height"])) {
	        // 1 column on 2 pages
	        $no_of_columns = 1;
	        $no_of_pages = 2;
	    } else {
	        // 2 columns, multiple pages
	        $no_of_columns = 2;
	        $no_of_pages = 1 + ceil(($total_height_1_column_px - $page_parameters["p1_content_height"]) / $page_parameters["p2_content_height"]);
	    }
	    
	    
	    if($non_zero_length_lines_count ===0) {
	        $average_line_length = 0;
	    } else {
	       $average_line_length = ceil($non_zero_length_lines_total / $non_zero_length_lines_count);
	    }
	    
	    return array(
	        'maximum_line_length' => $max_length,
	        'total_height_1_column_lines' => $total_height_1_column_lines,
	        'total_height_2_column_lines' => $total_height_2_column_lines,
	        'total_height_1_column_px' => $total_height_1_column_px,
	        'total_height_2_column_px' => $total_height_2_column_px, 
	        'average_line_length' => $average_line_length,
	        'number_of_wrapped_lines_2_column' => $number_of_wrapped_lines_2_column,
	        'total_number_of_lines' => $number_of_lines,
	        'non_zero_length_lines_count' => $non_zero_length_lines_count,
	        'no_of_columns' => $no_of_columns,
	        'no_of_pages' => $no_of_pages
	        
	    );
	}
	
	public static function create_printable_page ($container, $page_no, $page_parameters, $song_id = 0) {
	    $doc = $container->ownerDocument;
	    if(is_null($doc)) {
	        $doc = $container;
	    }
		$page = $doc->createElement('table');
		$page->setAttribute('class', 'printable lyrics-display page song-' . $song_id . ' page-' . $page_no . ' ' . $page_parameters["style_set_or_song"] );
		$page->setAttribute('style', 'width: ' . $page_parameters["page_width"] . 'px; height: ' . $page_parameters["page_height"] . 'px; font-size:' . $page_parameters["font_size_in_pixels"] . "px;");
		
		$tbody = $doc->createElement('tbody');
		
		$row = $doc->createElement('tr');
		$tbody->appendChild($row);
		
		$td = $doc->createElement('td');
		$row->appendChild($td);
		
		$page->appendChild($tbody);
		
		$container->appendChild($page);
		
		return [$page, $tbody, $row, $td];
	}
	
	public static function output_pdf($display, $title = '', $orientation = 'portrait') {
		$pdf = new WKPDF();
		$pdf->set_orientation($orientation);
		$display = '<html><head><title>'.$title.'</title><link href="../index.css" rel="stylesheet" type="text/css" /></head><body class="pdf">'.$display.'</body></html>';
		$display = preg_replace('/&nbsp;/', '&#160;', $display); //&nbsp; doesn't work in XML unless it's specifically declared.
		$pdf->set_html($display);
		$pdf->render();
		$pdf->output(WKPDF::$PDF_DOWNLOAD, $title.".pdf");
	}
	
	public static function find_note_number($original_value, $adjustment) {
		$new_value = $original_value + $adjustment;
		if($new_value < 0) {
			$new_value = 12 + $new_value;
		}
		if($new_value > 11) {
			$new_value = $new_value - 12;
		}
		return $new_value;
	}
	
	public static function note_to_upper($note) {
		$base_note = substr($note, 0, 1);
		$upper_note = strtoupper($base_note);
		if(strlen($note) == 2) {
			$upper_note = $upper_note.substr($note, 1, 1);
		}
		return $upper_note;
	}
	
	public static function note_to_lower($note) {
		$base_note = substr($note, 0, 1);
		$lower_note = strtolower($base_note);
		if(strlen($note) == 2) {
			$lower_note = $lower_note.substr($note, 1, 1);
		}
		return $lower_note;
	}
	
	public static function shift_note($note, $adjustment, $use_sharps = null) {
	
		if($note == '') { return ''; }
		$lowercase = false;
		if(self::note_to_lower($note) === $note) {
			$lowercase = true;
		}
		$note_upper = self::note_to_upper($note);
        if (array_key_exists($note_upper, self::$NOTE_VALUE_ARRAY)) {
            $new_note_number = self::find_note_number(self::$NOTE_VALUE_ARRAY[$note_upper], $adjustment);
            if(is_null($use_sharps)) {
            	$new_note = self::$VALUE_NOTE_ARRAY_DEFAULT[$new_note_number];
            } elseif($use_sharps === true) {
            	$new_note = self::$VALUE_NOTE_ARRAY_SHARP[$new_note_number];
            } else {
            	$new_note = self::$VALUE_NOTE_ARRAY_FLAT[$new_note_number];
            }
            if($lowercase) {
            	$new_note = self::note_to_lower($new_note);
            }
        } else {
            $new_note = $note;
        }

        return $new_note;
	}
	
	public static function transpose_chord($chord, $base_key, $target_key, $capo = NULL) {
		/*
		debug([ 'chord' => $chord, 'base_key' => $base_key, 'target_key' => $target_key, 'capo' => $capo ]);
		//*/
		$chord_note = substr($chord, 0, 1);
		$second_char = substr($chord, 1, 1);
		$modifier_start = 1;
		if($second_char === '#' || $second_char == 'b') {
			$chord_note = $chord_note.$second_char;
			$modifier_start = 2;
		} elseif ($chord_note === "/") {
			$modifier_start = 0;
			$chord_note = '';
		}
		$chord_modifier = substr($chord, $modifier_start);
		// If a key is given as minor it isn't in the key array! Remove the m, replace it later
		if(substr($target_key, -1) == 'm') {
		    $target_key_mode = 'm';
		    $target_key = str_replace('m', '', $target_key);
		} else {
		    $target_key_mode = '';
		}
		if(substr($base_key, -1) == 'm') {
		    $base_key_mode = 'm';
		    $base_key = str_replace('m', '', $base_key);
		} else {
		    $base_key_mode = '';
		}
		/*
		debug(['target_key_note_value' => self::$NOTE_VALUE_ARRAY[$target_key],'base_key_note_value' => self::$NOTE_VALUE_ARRAY[$base_key]]);
		//*/
		
        if(array_key_exists($target_key, self::$NOTE_VALUE_ARRAY) && array_key_exists($base_key, self::$NOTE_VALUE_ARRAY)) {
            $target_key_note_value = self::$NOTE_VALUE_ARRAY[$target_key];
            $base_key_note_value = self::$NOTE_VALUE_ARRAY[$base_key];
            $key_conversion_value = $target_key_note_value - $base_key_note_value;			
        } else {
            $key_conversion_value = 0;
            $target_key_note_value = null;
            $base_key_note_value = null;
        }
		if(self::$key_transpose_parameters['capo']) {
			$key_conversion_value = $key_conversion_value - self::$key_transpose_parameters['capo'];
		}
		$new_chord = self::shift_note($chord_note, $key_conversion_value);
		/*
		debug([
		    'base_key' => $base_key, 'target_key' => $target_key, 'capo' => $capo,
		    'base_key_note_value' => $base_key_note_value, 'target_key_note_value' => $target_key_note_value,
		    'key_conversion_value' => $key_conversion_value,
		    'chord' => $chord,
		    'chord_note' => $chord_note,
		    'new_chord' => $new_chord
		]);
		//*/
		$bass_key = '';
		$slash_position = strpos($chord_modifier, '/');
		if($slash_position !== false) {
			$new_chord_modifier = substr($chord_modifier, 0, $slash_position);
			$old_bass_key = substr($chord_modifier, $slash_position + 1);
			$new_bass_key = self::shift_note($old_bass_key, $key_conversion_value);
			$bass_key = '/'.$new_bass_key;
		} else {
			$new_chord_modifier = $chord_modifier;
		}
	
		$new_chord = $new_chord.$new_chord_modifier.$bass_key;
	
		return $new_chord;
	}
}
