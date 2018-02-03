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
			$replaced_chord = StaticFunctionController::transpose_chord($replaced_chord, StaticFunctionController::$key_transpose_parameters['base_key'], StaticFunctionController::$key_transpose_parameters['display_key']);
		}
		return '</span><span class="chord' . $fullsize_class . '">'.$replaced_chord.'</span><span class="text">';
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
		//convert ampersand to xml character entity &#38;
		$contentHTML = preg_replace('/&([^#n])/', '&#38;$1', $contentHTML);  
        // chords that are close together - [Am][D] etc ... even [Am]  [D].... should be separated by characters equal in width to the chord (or by margin in css?)
        //I'll mark these kinds of chords with "!" so that I can set their class in  chord_replace_callback()
        // In PHP "\h" matches a 'horizontal whitespace' character so the expression '/\](\h*?)\[/' should find relevant chords
		$contentHTML = preg_replace('/\](\h*?)\[/', '!]$1[', $contentHTML);
		//replace spaces with non-breaking spaces
		$contentHTML = str_replace(' ', '&#160;', $contentHTML);
		//replace end-of-line with the end of a div and the start of a line div
		$contentHTML = preg_replace('/\n/','</span></div><div class="line"><span class="text">', $contentHTML);
		//empty lines - put in a non-breaking space so that they don't collapse?
		$contentHTML = preg_replace('/<div class=\"line\"><span class=\"text\">[\s]*?<\/span><\/div>/', '<div class="line"><span class="text">&#160;</span></div>', $contentHTML);
		//anything in square brackets is taken to be a chord and should be processed to create chord html
		$contentHTML = preg_replace_callback('/\[(.*?)\]/', 'self::chord_replace_callback', $contentHTML);
		//if there's a bass modifier, give it its own html
		$contentHTML = preg_replace('#<span class="chord">([^<]*?)/([^<]*?)</span>#','<span class="chord">$1<span class="bass_note_modifier separator">/</span><span class="bass_note_modifier note">$2</span></span>', $contentHTML);
		//&nbsp; doesn't work in XML unless it's specifically declared..... this was added when the songbook was xml based, but still works here so...
		$contentHTML = preg_replace('/&nbsp;/', '&#160;', $contentHTML); 
		//if a 'score' reference is included, insert the image referred to in it. It should be in the webroot/score/ directory
		$contentHTML = preg_replace('/\{score:(.*?)\}/', '<img src="/songbook/score/$1" />', $contentHTML);
		//Finally, wrap the song lyric content in a lyrics-panel div
		$contentHTML = '<div class="lyrics-panel"><div class="line"><span class="text">'.$contentHTML.'</span></div></div>';
	
		return $contentHTML;
	}
	
	public static function format_html_for_print($contentHTML) {
		//css sets font size to 16 px, then this works on an A4 page in Firefox:
		$height_of_line_with_chords = 29.65;
		$height_of_line_without_chords = 18;
		$page_height = 560;
		//////////
		$number_of_columns_per_page = 2;
		
		$doc = new \DOMDocument();
		$doc->loadHTML($contentHTML);
		$xpath = new \DOMXPath($doc);
		$lines = $xpath->query("//div[@class='line']");
		$lines_with_chords = $xpath->query("//div[span[@class='chord']]");
		
		$total_Lines = $lines->length;
		$no_lines_with_chords = $lines_with_chords->length;
		$total_height = ($no_lines_with_chords * $height_of_line_with_chords) + ($total_Lines - $no_lines_with_chords) * $height_of_line_without_chords;
		$number_of_columns = $total_height / $page_height;
		
		$content_height = 0;
		$current_page = 1;
		$current_column = 1;
		$pages = [];
		
		list($pages[$current_page], $row, $td) = StaticFunctionController::create_printable_page($doc, $current_page);
		
		foreach($lines as $line) {
			$line_contains_chords = $xpath->query("span[@class='chord']", $line)->length;
			/*
				debug($doc->saveHTML($line));
				//<div class="line"><span class="text">a real go</span><span class="chord">Bm</span><span class="text">od time I feel al</span><span class="chord">Em</span><span class="text">i-i-i-i</span><span class="chord">A</span><span class="text"> ve</span></div>
				debug($line_contains_chords);
				//3
			*/
			if($line_contains_chords > 0){
				$line_height = $height_of_line_with_chords;
			} else {
				$line_height = $height_of_line_without_chords;
			}
			$content_height = $content_height + $line_height;
			if ($content_height > $page_height) {
				$current_column = $current_column + 1;
				if($current_column > $number_of_columns_per_page) {
					//new page
					$current_page = $current_page + 1;
					$current_column = 1;
					$content_height = 0;
					list($pages[$current_page], $row, $td) = StaticFunctionController::create_printable_page($doc, $current_page);
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
		
		$return = $doc->saveHTML();
		
		
		return $return;
	}
	
	public static function create_printable_page ($doc, $page_no) {
		$page = $doc->createElement('table');
		$page->setAttribute('class', 'printable lyrics-display page ' . $page_no);
		$tbody = $doc->createElement('tbody');
		$row = $doc->createElement('row');
		$td = $doc->createElement('td');
		$row->appendChild($td);
		$tbody->appendChild($row);
		$page->appendChild($tbody);
		$doc->appendChild($page);
		
		return [$page, $row, $td];
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
		if(StaticFunctionController::note_to_lower($note) === $note) {
			$lowercase = true;
		}
		$note_upper = StaticFunctionController::note_to_upper($note);
        if (array_key_exists($note_upper, StaticFunctionController::$NOTE_VALUE_ARRAY)) {
            $new_note_number = StaticFunctionController::find_note_number(StaticFunctionController::$NOTE_VALUE_ARRAY[$note_upper], $adjustment);
            if(is_null($use_sharps)) {
            	$new_note = StaticFunctionController::$VALUE_NOTE_ARRAY_DEFAULT[$new_note_number];
            } elseif($use_sharps === true) {
            	$new_note = StaticFunctionController::$VALUE_NOTE_ARRAY_SHARP[$new_note_number];
            } else {
            	$new_note = StaticFunctionController::$VALUE_NOTE_ARRAY_FLAT[$new_note_number];
            }
            if($lowercase) {
            	$new_note = StaticFunctionController::note_to_lower($new_note);
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
        if(array_key_exists($target_key, StaticFunctionController::$NOTE_VALUE_ARRAY) && array_key_exists($base_key, StaticFunctionController::$NOTE_VALUE_ARRAY)) {
            $key_conversion_value = StaticFunctionController::$NOTE_VALUE_ARRAY[$target_key] - StaticFunctionController::$NOTE_VALUE_ARRAY[$base_key];			
        } else {
            $key_conversion_value = 0;
        }
		if(StaticFunctionController::$key_transpose_parameters['capo']) {
			$key_conversion_value = $key_conversion_value - StaticFunctionController::$key_transpose_parameters['capo'];
		}
		$new_chord = StaticFunctionController::shift_note($chord_note, $key_conversion_value);

		$bass_key = '';
		$slash_position = strpos($chord_modifier, '/');
		if($slash_position !== false) {
			$new_chord_modifier = substr($chord_modifier, 0, $slash_position);
			$old_bass_key = substr($chord_modifier, $slash_position + 1);
			$new_bass_key = StaticFunctionController::shift_note($old_bass_key, $key_conversion_value);
			$bass_key = '/'.$new_bass_key;
		} else {
			$new_chord_modifier = $chord_modifier;
		}
	
		$new_chord = $new_chord.$new_chord_modifier.$bass_key;
	
		return $new_chord;
	}
}
