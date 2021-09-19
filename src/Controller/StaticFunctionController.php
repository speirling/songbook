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
	    if(strpos($chord[1], "span") !== false) {
	    debug ($chord);
	    }
		$replaced_chord = $chord[1];
		$fullsize_class = '';
		
		if(strpos($replaced_chord, '!') !== false) {
			//This is one of those chords followed by whitepace, that needs to be set to greater than 0 space.
			//I'll use a class for that
			$fullsize_class = " full-width";
			$replaced_chord = str_replace('!', '', $replaced_chord);
		}
		if(StaticFunctionController::$key_transpose_parameters['transpose']) {
			$replaced_chord = StaticFunctionController::transpose_chord($replaced_chord, StaticFunctionController::$key_transpose_parameters['base_key'], StaticFunctionController::$key_transpose_parameters['display_key']);
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
			$score_filename = $score_path_info['filename'] . "_" . StaticFunctionController::$key_transpose_parameters['display_key'] . "." . $score_path_info['extension'];
	
			if(!file_exists("/fileserver/data/songbook_images/" . $score_filename)) {
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
	
		$contentHTML = $content;
		//if special characters have found their way into  lyrics in the database, get rid of them
		$contentHTML = preg_replace('/&nbsp;/', ' ', $contentHTML);  

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
	        $page_size = 'A4'
	    ) {
	    
	    $print_page_configuration = \Cake\Core\Configure::read('print_page');
	    $page_parameters = $print_page_configuration[$page_size];
	    $page_parameters["style_set_or_song"] = $song_parameters["style_set_or_song"]; //there's got to be a more elegant way of passing this from Controller to page creator
	    $current_page = 1;
		//this is called from SongsController -> printable() with $contentHTML set to the output from convert_song_content_to_HTML
		/*
		 * if css sets font size to 16 px, then this works on an A4 page in Firefox:
		 * 		$height_of_line_with_chords = 35.133; //2.1958 x font size in px
		 * 		$height_of_line_without_chords = 18; //1.125 x font size in px
		 * 
		 * This worked for Night Sky in Firefox at print 100%:
		 * $page_height = 750, $page_width = 690, $number_of_columns_per_page = 2, $font_size_in_pixels = 16
		 * Didn't work for Spéirling! first page became too big, and pushed main content to second page. Maybe has more chords?
		 * This is what worked for Spéirling:
		 * $page_height = 600, $page_width = 690, $number_of_columns_per_page = 2, $font_size_in_pixels = 16
		*/
		//////////
	    //debug($contentHTML);
		
				
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
		//debug(  $song_parameters["title"]);debug($line_stats);
		
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
		if(trim($song_parameters["current_key"]) !== "") {
		    $title_heading_html = $title_heading_html .   		"<th class=\"key\">" . '&#160;&#160;&#160;&#160;|&#160;&#160;&#160;&#160; Key' .  "</th>"  . "\n" ;
		    $title_heading_html = $title_heading_html .   		"<td class=\"key\">" . htmlspecialchars($song_parameters["current_key"]) .                          "</td>"  . "\n" ;
		}
		if(trim($song_parameters["capo"]) !== "") {
		    $title_heading_html = $title_heading_html .   		"<th class=\"capo\">" . 'Capo' .                                                   "</th>"  . "\n" ;
		    $title_heading_html = $title_heading_html .   		"<td class=\"capo\">" . htmlspecialchars($song_parameters["capo"]) .                                 "</td>"  . "\n" ;
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
		$page_height = $page_parameters["height_of_page_1_lines"];
		$content_height = 0; //content height here has to be number of lines, because that's the units used in $page_parameters["height_of_page_1_lines"]
		if ($line_stats['no_of_columns'] === 1) {
		    $column_width = $page_parameters["column_width"]["1_column"];
		} else {
		    $column_width = $page_parameters["column_width"]["2_column"];
		}

		foreach($lines as $line) {
			//set the font size
		    $line->setAttribute("style", "font-size: " . $page_parameters["font_size_in_pixels"] . "px;");
			$line_contains_chords = $xpath->query("span[@class='chord']", $line)->length;
			$line_with_chords_removed = $xpath->query("span[not (@class='chord')]", $line);
			$this_line_length = 0;
			foreach($line_with_chords_removed as $this_node) {
			    //debug($this_node->textContent);
			    $this_line_length = $this_line_length + strlen($this_node->textContent);
			}

			if ($line_contains_chords > 0){
				$line_height = $page_parameters["line_multiplier_chords"];
			} else {
				$line_height = 1;
			}
			$additional_line = ceil($this_line_length / $column_width) - 1;
			$content_height = $content_height + (1 + $additional_line * ($page_parameters["line_multiplier_wrapped"] - 1)) * $line_height; // if the line wraps, it doesn't take up the same space as 2 full lines
			if ($content_height > $page_height) {
				$current_column = $current_column + 1;
				if($current_column > $line_stats['no_of_columns']) {
					//new page
					$current_page = $current_page + 1;
					$current_column = 1;
					$content_height = 0;
					$page_height = $page_parameters["height_of_page_2_lines"];
					list($pages[$current_page], $tbody, $row, $td) = self::create_printable_page($doc, $current_page, $page_parameters, $song_parameters["id"]);
				} else {
				   //new column
					$content_height = 0;
					$td = $doc->createElement('td');
					$row->appendChild($td);
				}
			}
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
	        //debug(" additional_line_1_column " . $additional_line_1_column . " additional_line_2_column " . $additional_line_2_column);
	        
	        $total_height_1_column_lines = $total_height_1_column_lines + (1 + $additional_line_1_column * ($page_parameters["line_multiplier_wrapped"] - 1)) * $line_height_lines; //number of lines
	        $total_height_2_column_lines = $total_height_2_column_lines + (1 + $additional_line_2_column * ($page_parameters["line_multiplier_wrapped"] - 1)) * $line_height_lines; //number of lines
	        
	        $total_height_1_column_px = $total_height_1_column_px + (1 + $additional_line_1_column * ($page_parameters["line_multiplier_wrapped"] - 1)) * $line_height_px; //pixels
	        $total_height_2_column_px = $total_height_2_column_px + (1 + $additional_line_2_column * ($page_parameters["line_multiplier_wrapped"] - 1)) * $line_height_px; //pixels
	        
	        
	        if($this_line_length > $page_parameters["column_width"]["2_column"]) {
	            $number_of_wrapped_lines_2_column = $number_of_wrapped_lines_2_column + 1;
	        }
	        
	        //self::debug_DOMNodeList($this_line);
	        //$line_with_chords_removed = preg_replace('/<span class=\"chord\">[\s]*?<\/span>/',"",$this_line);
	        //debug($line_with_chords_removed);
	    }
	    
	    /*
	     * How many characters would fit in a column without wrapping? If 2 columns, empirically 45 characters doesn't wrap.
	     Assume if only one column 90 characters might wrap. Or 100.
	     How many lines on a page? If only one page, empirically it looks like 25 lines
	     Second (and subsequent)  page wouldn't have the title bar so maybe 30 lines.
	     So if more than 25 lines, and lines are less than 45 characters, then 2 columns will work fine.
	     Otherwise  ... ?
	     
	     $number_of_columns_if_only_one_page = $height_of_all_lyric_lines / $page_height;
	     
	     Max columns = page_width / max_line_width
	     if no_of_lines / Max_columns < 25 then go for columns
	     if you have to go onto a second page anyway, test if single column will do?
	     
	     Assume that a wrapped line takes up 2 line spaces.
	     
	     assume max 2 columns
	     calculate height of all lines 1 column
	     calculate height 2 column where more lines would wrap.
	     
	     If total_height_1_column_lines < 25 then no brainer, 1 column
	     If total_height_1_column_lines > 25 and total_height_2_column_lines < 50 then go for 2 columns, it'll fit on one page
	     If total_height_2_column_lines > 50 then you're looking at 2 pages, so if total_height_1_column_lines < 55 then go for one column to avoid wrapping, otherwise 2 columns
	     
	     you could weight it that if the vast majority of lines are wrapped then go for 1 column anyway?
	     */
	    
	    if ($total_height_1_column_lines < $page_parameters["height_of_page_1_lines"]) {
	        // 1 column on one page
	        $no_of_columns = 1;
	        $no_of_pages = 1;
	    } elseif ($total_height_2_column_lines < $page_parameters["height_of_page_1_lines"] * 2) {
	        // 2 columns on one page
	        $no_of_columns = 2;
	        $no_of_pages = 1;
	    } elseif ($total_height_1_column_lines < ($page_parameters["height_of_page_1_lines"] + $page_parameters["height_of_page_2_lines"])) {
	        // 1 column on 2 pages
	        $no_of_columns = 1;
	        $no_of_pages = 2;
	    } else {
	        // 2 columns, multiple pages
	        $no_of_columns = 2;
	        $no_of_pages = 1 + ceil(($total_height_1_column_lines - $page_parameters["height_of_page_1_lines"]) / $page_parameters["height_of_page_2_lines"]);
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
        if(array_key_exists($target_key, self::$NOTE_VALUE_ARRAY) && array_key_exists($base_key, self::$NOTE_VALUE_ARRAY)) {
            $key_conversion_value = self::$NOTE_VALUE_ARRAY[$target_key] - self::$NOTE_VALUE_ARRAY[$base_key];			
        } else {
            $key_conversion_value = 0;
        }
		if(self::$key_transpose_parameters['capo']) {
			$key_conversion_value = $key_conversion_value - self::$key_transpose_parameters['capo'];
		}
		$new_chord = self::shift_note($chord_note, $key_conversion_value);

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
