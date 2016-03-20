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
	private $NOTE_VALUE_ARRAY = Array(
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
	
	private $VALUE_NOTE_ARRAY_DEFAULT = Array(
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
	
	private $VALUE_NOTE_ARRAY_SHARP = Array(
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
	
	private $VALUE_NOTE_ARRAY_FLAT = Array(
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
	
	public static function chord_replace_callback($chord, $base_key = null, $target_key = null) {
		$replaced_chord = $chord[1];
		if(StaticFunctionController::$key_transpose_parameters['transpose']) {
			$replaced_chord = StaticFunctionController::transpose_chord($replaced_chord, StaticFunctionController::$key_transpose_parameters['base_key'], StaticFunctionController::$key_transpose_parameters['target_key']);
		}
		return '</span><span class="chord">'.$replaced_chord.'</span><span class="text">';
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
		$contentHTML = preg_replace('/&([^#n])/', '&#38;$1', $contentHTML);
		$contentHTML = str_replace(' ', '&#160;', $contentHTML);
		$contentHTML = preg_replace('/\n/','</span></div><div class="line"><span class="text">', $contentHTML);
		$contentHTML = preg_replace('/<div class=\"line\"><span class=\"text\">[\s]*?<\/span><\/div>/', '<div class="line"><span class="text">&nbsp;</span></div>', $contentHTML);
		$contentHTML = preg_replace_callback('/\[(.*?)\]/', 'self::chord_replace_callback', $contentHTML);
		$contentHTML = preg_replace('#<span class="chord">([^<]*?)/([^<]*?)</span>#','<span class="chord">$1<span class="bass_note_modifier separator">/</span><span class="bass_note_modifier note">$2</span></span>', $contentHTML);
		$contentHTML = preg_replace('/&nbsp;/', '&#160;', $contentHTML); //&nbsp; doesn't work in XML unless it's specifically declared.
		$contentHTML = '<div class="lyrics-panel"><div class="line"><span class="text">'.$contentHTML.'</span></div></div>';
	
		return $contentHTML;
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
		global $NOTE_VALUE_ARRAY, $VALUE_NOTE_ARRAY_DEFAULT, $VALUE_NOTE_ARRAY_SHARP, $VALUE_NOTE_ARRAY_FLAT;
	
		$lowercase = false;
		if(sbk_note_to_lower($note) === $note) {
			$lowercase = true;
		}
		$new_note_number = sbk_find_note_number($NOTE_VALUE_ARRAY[sbk_note_to_upper($note)], $adjustment);
		if(is_null($use_sharps)) {
			$new_note = $VALUE_NOTE_ARRAY_DEFAULT[$new_note_number];
		} elseif($use_sharps === true) {
			$new_note = $VALUE_NOTE_ARRAY_SHARP[$new_note_number];
		} else {
			$new_note = $VALUE_NOTE_ARRAY_FLAT[$new_note_number];
		}
		if($lowercase) {
			$new_note = sbk_note_to_lower($new_note);
		}
		return $new_note;
	}
	
	public static function transpose_chord($chord, $base_key, $target_key, $capo = NULL) {
		global $NOTE_VALUE_ARRAY, $VALUE_NOTE_ARRAY_DEFAULT, $VALUE_NOTE_ARRAY_SHARP, $VALUE_NOTE_ARRAY_FLAT;
	debug($chord, $base_key, $target_key, $capo);
		$chord_note = substr($chord, 0, 1);
		$second_char = substr($chord, 1, 1);
		$modifier_start = 1;
		if($second_char === '#' || $second_char == 'b') {
			$chord_note = $chord_note.$second_char;
			$modifier_start = 2;
		}
		$chord_modifier = substr($chord, $modifier_start);
	
		$key_conversion_value = $NOTE_VALUE_ARRAY[$target_key] - $NOTE_VALUE_ARRAY[$base_key];
		$new_chord = sbk_shift_note($chord_note, $key_conversion_value);
	
		$bass_key = '';
		$slash_position = strpos($chord_modifier, '/');
		if($slash_position !== false) {
			$new_chord_modifier = substr($chord_modifier, 0, $slash_position);
			$old_bass_key = substr($chord_modifier, $slash_position + 1);
			$new_bass_key = sbk_shift_note($old_bass_key, $key_conversion_value);
			$bass_key = '/'.$new_bass_key;
		} else {
			$new_chord_modifier = $chord_modifier;
		}
	
		$new_chord = $new_chord.$new_chord_modifier.$bass_key;
	
		return $new_chord;
	}
}
