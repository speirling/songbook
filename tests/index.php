<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('../admin/configure.inc.php');
require_once('simpletest/autorun.php');
echo "
<style type='text/css'>
body {
	font-family:arial;
	font-size:12px;
}
pre {
	font-size: 0.9em;
}
del {
	display: inline-block;
    background-color:#ffaaaa;
    color:#990000;
}

ins {
	display: inline-block;
    background-color:#aaffaa;
    color:#009900;
}
h2.test-fail {
	font-size:1em;
	background-color:#ffaaaa;
	margin:0;
	padding:0.5em;
}
</style>
";

global $text_playlist_xml;

function sbktest_standardise_markup($string) {
    $string = str_replace(array("\r", "\n", '&#xE1;', '&#xF3;', '&#xED;', '&#xFA;', '&#xE9;','&#xA0;'), array('', '', 'á', 'ó', 'í', 'ú', 'é', '&#160;'), $string);
    $string = trim(preg_replace('/>[\s\n]*?</', '><', $string));
    return $string;
}

$text_playlist_xml = <<<XML
<?xml version="1.0" standalone="yes"?>
<songlist title="Test Playlist" act="Cafe Ceili">
  <introduction duration="0:55">Introduction to the show</introduction>
  <set label="Set 1">
    <introduction duration="0:05">Introduction to the first set</introduction>
    <song id="34" key="G" singer="Euge" capo="0" duration="1:00">
      <introduction duration="0:30">Introduction to the first song</introduction>
    </song>
    <song id="38" key="A" singer="Ali" capo="2" duration="2:00">
      <introduction duration="0:45">Introduction to the second song</introduction>
    </song>
  </set>
  <set label="Set 2">
    <introduction duration="0:05">Introduction to the second set</introduction>
    <song id="40" key="E" singer="Breandan" capo="1" duration="3:00">
      <introduction duration="0:15">Introduction to the third song</introduction>
    </song>
    <song id="20" key="D" singer="Bill" capo="0" duration="4:00">
      <introduction duration="0:25">Introduction to the fourth song</introduction>
    </song>
  </set>
</songlist>
XML;

$text_full_html = sbktest_standardise_markup('
<input type="text" class="playlist-title" value="Test Playlist" />
<input type="text" class="act" value="Cafe Ceili" />
<span class="introduction songlist">
<textarea class="introduction_text" placeholder="Introduction text">Introduction to the show</textarea>
<input type="text" class="introduction_duration" placeholder="Introduction duration" value="0:55" />
</span>
<ul>
<li class="set playlist">
  <input type="text" class="set-title" value="Set 1" />
  <span class="duration">04:15</span>
  <span class="introduction set">
    <textarea class="introduction_text" placeholder="Introduction text">Introduction to the first set</textarea>
    <input type="text" class="introduction_duration" placeholder="Introduction duration" value="0:05" />
  </span>
  <ol>
    <li class="song" id="id_34">
      <input type="text" class="singer" placeholder="singer" value="Euge" />
      <input type="text" class="key" placeholder="key" value="G" />
      <input type="text" class="capo" placeholder="capo" value="0" />
      <span class="id">34</span>
      <input type="text" class="duration" placeholder="mm:ss" value="1:00" />
      <span class="title">A Hard Day\'s Night</span>
      <span class="introduction">
        <textarea class="introduction_text" placeholder="Introduction text">Introduction to the first song</textarea>
        <input type="text" class="introduction_duration" placeholder="Introduction duration" value="0:30" />
      </span>
    </li>
    <li class="song" id="id_38">
      <input type="text" class="singer" placeholder="singer" value="Ali" />
      <input type="text" class="key" placeholder="key" value="A" />
      <input type="text" class="capo" placeholder="capo" value="2" />
      <span class="id">38</span>
      <input type="text" class="duration" placeholder="mm:ss" value="2:00" />
      <span class="title">A Pair Of Brown Eyes</span>
      <span class="introduction">
        <textarea class="introduction_text" placeholder="Introduction text">Introduction to the second song</textarea>
        <input type="text" class="introduction_duration" placeholder="Introduction duration" value="0:45" />
      </span>
    </li>
    <li class="dummy">&nbsp;</li>
  </ol>
</li>
<li class="set playlist">
  <input type="text" class="set-title" value="Set 2" />
  <span class="duration">07:40</span>
  <span class="introduction set">
    <textarea class="introduction_text" placeholder="Introduction text">Introduction to the second set</textarea>
    <input type="text" class="introduction_duration" placeholder="Introduction duration" value="0:05" />
  </span>
  <ol>
    <li class="song" id="id_40">
    <input type="text" class="singer" placeholder="singer" value="Breandan" />
    <input type="text" class="key" placeholder="key" value="E" />
      <input type="text" class="capo" placeholder="capo" value="1" />
    <span class="id">40</span>
    <input type="text" class="duration" placeholder="mm:ss" value="3:00" />
    <span class="title">A Rainy Night In Soho</span>
    <span class="introduction">
      <textarea class="introduction_text" placeholder="Introduction text">Introduction to the third song</textarea>
      <input type="text" class="introduction_duration" placeholder="Introduction duration" value="0:15" />
    </span>
  </li>
  <li class="song" id="id_20">
    <input type="text" class="singer" placeholder="singer" value="Bill" />
    <input type="text" class="key" placeholder="key" value="D" />
      <input type="text" class="capo" placeholder="capo" value="0" />
    <span class="id">20</span>
    <input type="text" class="duration" placeholder="mm:ss" value="4:00" />
    <span class="title">Stand by Me</span>
    <span class="introduction">
      <textarea class="introduction_text" placeholder="Introduction text">Introduction to the fourth song</textarea>
      <input type="text" class="introduction_duration" placeholder="Introduction duration" value="0:25" />
    </span>
  </li>
  <li class="dummy">&nbsp;</li>
  </ol>
</li>
</ul>'
);

$text_playlist_json = '{
	"title": "Test Playlist",
	"act": "Cafe Ceili",
	"introduction": {"text": "Introduction to the show", "duration": "0:55"},
	"sets": [{
		"label": "Set 1",
		"introduction": {"text": "Introduction to the first set", "duration": "0:05"},
		"songs": [
			{"id": "id_34", "key": "G", "singer": "Euge", "capo": "0", "duration": "1:00", "introduction": {"text": "Introduction to the first song", "duration": "0:30"}},
			{"id": "id_38", "key": "A", "singer": "Ali", "capo": "2", "duration": "2:00", "introduction": {"text": "Introduction to the second song", "duration": "0:45"}}
		]
	}, {
		"label": "Set 2",
		"introduction": {"text": "Introduction to the second set", "duration": "0:05"},
		"songs": [
			{"id": "id_40", "key": "E", "singer": "Breandan", "capo": "1", "duration": "3:00", "introduction": {"text": "Introduction to the third song", "duration": "0:15"}},
			{"id": "id_20", "key":"D", "singer": "Bill", "capo": "0", "duration": "4:00", "introduction": {"text": "Introduction to the fourth song", "duration": "0:25"}}
		]
	}]
}';

$array_song = array(
'id' => 697,
'title' => 'Tráth m\'Aoibhnis',
'written_by' => 'Breandán de Róiste and Eugene Peelo',
'performed_by' => '',
'base_key' => 'D',
'content' => 'Tráth m\'a[D]oibhnis, - bhíodh si[G]oc againn ar m[A/c#]aidin
Um Tráthn[D]óna, - níorbh a[G]nnamh corr-aiteall \'s m[A]úr
Thuas in ai[Bm]rde (ag) deisiú d[F#m]íonta,
i mei[G]theal easaor[D]ánach
Thíos f[Em]úm, ba l[G]iúm an chathair mh[A]ór

Anseo abh[D]us - ní lé[G]ir a bhfuil i d[A]án dom',
'original_filename' => '',
'meta_tags' => 'Irish, Gaeilge, Folk'

);

$text_song_body_html = sbktest_standardise_markup(
    str_replace('&nbsp;', '&#160;', '
    <div class="line"><span class="text">Tráth&nbsp;m\'a</span><span class="chord">D</span><span class="text">oibhnis,&nbsp;-&nbsp;bhíodh&nbsp;si</span><span class="chord">G</span><span class="text">oc&nbsp;againn&nbsp;ar&nbsp;m</span><span class="chord">A<span class="bass_note_modifier separator">/</span><span class="bass_note_modifier note">c#</span></span><span class="text">aidin</span></div>
    <div class="line"><span class="text">Um&nbsp;Tráthn</span><span class="chord">D</span><span class="text">óna,&nbsp;-&nbsp;níorbh&nbsp;a</span><span class="chord">G</span><span class="text">nnamh&nbsp;corr-aiteall&nbsp;\'s&nbsp;m</span><span class="chord">A</span><span class="text">úr</span></div>
    <div class="line"><span class="text">Thuas&nbsp;in&nbsp;ai</span><span class="chord">Bm</span><span class="text">rde&nbsp;(ag)&nbsp;deisiú&nbsp;d</span><span class="chord">F#m</span><span class="text">íonta,</span></div>
    <div class="line"><span class="text">i&nbsp;mei</span><span class="chord">G</span><span class="text">theal&nbsp;easaor</span><span class="chord">D</span><span class="text">ánach</span></div>
    <div class="line"><span class="text">Thíos&nbsp;f</span><span class="chord">Em</span><span class="text">úm,&nbsp;ba&nbsp;l</span><span class="chord">G</span><span class="text">iúm&nbsp;an&nbsp;chathair&nbsp;mh</span><span class="chord">A</span><span class="text">ór</span></div>
    <div class="line"><span class="text">&nbsp;</span></div>
    <div class="line"><span class="text">Anseo&nbsp;abh</span><span class="chord">D</span><span class="text">us&nbsp;-&nbsp;ní&nbsp;lé</span><span class="chord">G</span><span class="text">ir&nbsp;a&nbsp;bhfuil&nbsp;i&nbsp;d</span><span class="chord">A</span><span class="text">án&nbsp;dom</span></div>
'));

$text_song_body_html_transposed_G = sbktest_standardise_markup(
    str_replace('&nbsp;', '&#160;', '
    <div class="line"><span class="text">Tráth&nbsp;m\'a</span><span class="chord">G</span><span class="text">oibhnis,&nbsp;-&nbsp;bhíodh&nbsp;si</span><span class="chord">C</span><span class="text">oc&nbsp;againn&nbsp;ar&nbsp;m</span><span class="chord">D<span class="bass_note_modifier separator">/</span><span class="bass_note_modifier note">f#</span></span><span class="text">aidin</span></div>
    <div class="line"><span class="text">Um&nbsp;Tráthn</span><span class="chord">G</span><span class="text">óna,&nbsp;-&nbsp;níorbh&nbsp;a</span><span class="chord">C</span><span class="text">nnamh&nbsp;corr-aiteall&nbsp;\'s&nbsp;m</span><span class="chord">D</span><span class="text">úr</span></div>
    <div class="line"><span class="text">Thuas&nbsp;in&nbsp;ai</span><span class="chord">Em</span><span class="text">rde&nbsp;(ag)&nbsp;deisiú&nbsp;d</span><span class="chord">Bm</span><span class="text">íonta,</span></div>
    <div class="line"><span class="text">i&nbsp;mei</span><span class="chord">C</span><span class="text">theal&nbsp;easaor</span><span class="chord">G</span><span class="text">ánach</span></div>
    <div class="line"><span class="text">Thíos&nbsp;f</span><span class="chord">Am</span><span class="text">úm,&nbsp;ba&nbsp;l</span><span class="chord">C</span><span class="text">iúm&nbsp;an&nbsp;chathair&nbsp;mh</span><span class="chord">D</span><span class="text">ór</span></div>
    <div class="line"><span class="text">&nbsp;</span></div>
    <div class="line"><span class="text">Anseo&nbsp;abh</span><span class="chord">G</span><span class="text">us&nbsp;-&nbsp;ní&nbsp;lé</span><span class="chord">C</span><span class="text">ir&nbsp;a&nbsp;bhfuil&nbsp;i&nbsp;d</span><span class="chord">D</span><span class="text">án&nbsp;dom</span></div>
'));

$text_song_html = sbktest_standardise_markup('
<div class="song-page first_page" id="page_697_0">
    <div class="page_header">
        <div class="title">Tr&#xE1;th m\'Aoibhnis</div>
        <span class="songnumber">
            <span class="label">Song no. </span>
            <span class="data">697</span></span>
            <span class="pagenumber">
            <span class="label">page</span>
            <span class="data" id="page_number">1</span>
            <span class="label">of</span>
            <span class="data" id="number_of_pages">1</span>
        </span>
        <div class="written_by"><span class="data">Breand&#xE1;n de R&#xF3;iste and Eugene Peelo</span></div>
        <div class="performed_by"><span class="label">performed by: </span><span class="data"/></div>
        <div class="key">
        	<div class="target_key">
        		<span class="label">key: </span><span class="data">D</span>
        	</div>
        </div>
    </div>
    <table>
        <tr>
            <td>'.
$text_song_body_html
            .'</td>
        </tr>
    </table>
</div>
');

$text_song_html_transposed_G = sbktest_standardise_markup('
<div class="song-page first_page" id="page_697_0">
    <div class="page_header">
        <div class="title">Tr&#xE1;th m\'Aoibhnis</div>
        <span class="songnumber">
            <span class="label">Song no. </span>
            <span class="data">697</span></span>
            <span class="pagenumber">
            <span class="label">page</span>
            <span class="data" id="page_number">1</span>
            <span class="label">of</span>
            <span class="data" id="number_of_pages">1</span>
        </span>
        <div class="written_by"><span class="data">Breand&#xE1;n de R&#xF3;iste and Eugene Peelo</span></div>
        <div class="performed_by"><span class="label">performed by: </span><span class="data"/></div>
        <div class="key">
        	<div class="singer">
        		<span class="label">chords for </span>
        		<span class="data">Bill</span>
        	</div>
        	<div class="target_key">
        		<span class="label">key: </span>
        		<span class="data">G</span>
        	</div>
        </div>
    </div>
    <table>
        <tr>
            <td>'.
$text_song_body_html_transposed_G
            .'</td>
        </tr>
    </table>
</div>
');

class songbook_tests extends UnitTestCase {

   function test_sbk_song_as_li() {
       global $text_playlist_xml, $text_full_html;

      $data[] = array(
           'thisSong' => new SimpleXMLElement('<song id="164" key="G" singer="Clare" capo="1" duration="3:05"><introduction duration="1:32">Testing 1...2...3...</introduction></song>'),
           'textarea' => 'span',
           'input_start' => 'span',
           'input_middle' => '>',
           'input_end' => '</span',
           'editable' => false,
           'show_key' => TRUE,
           'show_capo' => TRUE,
           'show_singer' => TRUE,
           'show_id' => TRUE,
           'show_writtenby' => TRUE,
           'show_performedby' => TRUE,
           'show_duration' => true,
           'show_introduction' => true,
           'expected' => sbktest_standardise_markup('
           		<li class="song" id="id_164">
           			<span class="title">Dublin in The Rare Ould Times</span>
           			<span class="detail"> (<span class="written_by">Pete StJohn</span> | <span class="performed_by"></span>)</span>
           			<span class="spec">
           				<span class="key" placeholder="key">G</span>
           				<span class="singer" placeholder="singer">Clare</span>
           				<span class="capo" placeholder="capo">1</span>
           				<span class="id">164</span>
           				<span class="duration" placeholder="mm:ss">3:05</span>
           			</span>
           			<span class="introduction">
               			<span class="introduction_text" placeholder="Introduction text">Testing 1...2...3...</span>
               			<span class="introduction_duration" placeholder="Introduction duration">1:32</span>
           			</span>
           		</li>')
       );
       $data[] = array(
           'thisSong' => new SimpleXMLElement('<song id="164" key="G" singer="Clare" capo="2"  duration="3:05"><introduction duration="1:32">Testing 1...2...3...</introduction></song>'),
           'textarea' => 'textarea',
           'input_start' => 'input type="text"',
           'input_middle' => ' value="',
           'input_end' => '" /',
           'editable' => true,
           'show_key' => TRUE,
           'show_capo' => TRUE,
           'show_singer' => TRUE,
           'show_id' => TRUE,
           'show_writtenby' => TRUE,
           'show_performedby' => TRUE,
           'show_duration' => true,
           'show_introduction' => true,
           'expected' => sbktest_standardise_markup('
           		<li class="song" id="id_164">
           			<input type="text" class="singer" placeholder="singer" value="Clare" />
           			<input type="text" class="key" placeholder="key" value="G" />
           			<input type="text" class="capo" placeholder="capo" value="2" />
           			<span class="id">164</span>
           			<input type="text" class="duration" placeholder="mm:ss" value="3:05" />
           			<span class="title">Dublin in The Rare Ould Times</span>
           			<span class="introduction">
           				<textarea class="introduction_text" placeholder="Introduction text">Testing 1...2...3...</textarea>
           				<input type="text" class="introduction_duration" placeholder="Introduction duration" value="1:32" />
           			</span>
           		</li>')
       );
       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_song_as_li(
               $data[$index]['thisSong'],
               $data[$index]['textarea'],
               $data[$index]['input_start'],
               $data[$index]['input_middle'],
               $data[$index]['input_end'],
               $data[$index]['editable'],
               $data[$index]['show_key'],
               $data[$index]['show_capo'],
      	       $data[$index]['show_singer'],
      	       $data[$index]['show_id'],
      	       $data[$index]['show_writtenby'],
      	       $data[$index]['show_performedby'],
      	       $data[$index]['show_duration'],
      	       $data[$index]['show_introduction'],
               $data[$index]['expected']
           );
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result,$data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
       		   echo $result;
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }

   function test_sbk_convert_playlistXML_to_list() {
       global $text_playlist_xml, $text_full_html;

      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => true,
           'show_key' => TRUE,
           'show_capo' => TRUE,
      	   'show_singer' => TRUE,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => true,
           'expected' => $text_full_html
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => TRUE,
           'show_capo' => TRUE,
      	   'show_singer' => TRUE,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => true,
           'expected' => sbktest_standardise_markup('
           			<span class="playlist-title">Test Playlist</span>
           			<span class="act">Cafe Ceili</span>
           			<span class="introduction songlist">
           				<span class="introduction_text" placeholder="Introduction text">Introduction to the show</span>
           				<span class="introduction_duration" placeholder="Introduction duration">0:55</span>
           			</span>
           			<ul>
           				<li class="set playlist">
           					<span class="set-title">Set 1</span>
           					<span class="duration">04:15</span>
           					<span class="introduction set">
           						<span class="introduction_text" placeholder="Introduction text">Introduction to the first set</span>
           						<span class="introduction_duration" placeholder="Introduction duration">0:05</span>
           					</span>
           					<ol>
           						<li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span>
               						<span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span>
               						<span class="spec">
               							<span class="key" placeholder="key">G</span>
               							<span class="singer" placeholder="singer">Euge</span>
               							<span class="capo" placeholder="capo">0</span>
               							<span class="id">34</span>
               							<span class="duration" placeholder="mm:ss">1:00</span>
               						</span>
               						<span class="introduction">
               							<span class="introduction_text" placeholder="Introduction text">Introduction to the first song</span>
               							<span class="introduction_duration" placeholder="Introduction duration">0:30</span>
               						</span>
           						</li>
           						<li class="song" id="id_38">
           							<span class="title">A Pair Of Brown Eyes</span>
           							<span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span>
           							<span class="spec">
           								<span class="key" placeholder="key">A</span>
           								<span class="singer" placeholder="singer">Ali</span>
               							<span class="capo" placeholder="capo">2</span>
           								<span class="id">38</span>
           								<span class="duration" placeholder="mm:ss">2:00</span>
           							</span>
           							<span class="introduction">
           								<span class="introduction_text" placeholder="Introduction text">Introduction to the second song</span>
           								<span class="introduction_duration" placeholder="Introduction duration">0:45</span>
           							</span>
           						</li>
           					</ol>
           				</li>
           				<li class="set playlist">
           					<span class="set-title">Set 2</span>
           					<span class="duration">07:40</span>
           					<span class="introduction set">
           						<span class="introduction_text" placeholder="Introduction text">Introduction to the second set</span>
           						<span class="introduction_duration" placeholder="Introduction duration">0:05</span>
           					</span>
           					<ol>
           						<li class="song" id="id_40">
           							<span class="title">A Rainy Night In Soho</span>
           							<span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span>
           							<span class="spec">
           								<span class="key" placeholder="key">E</span>
           								<span class="singer" placeholder="singer">Breandan</span>
               							<span class="capo" placeholder="capo">1</span>
           								<span class="id">40</span>
           								<span class="duration" placeholder="mm:ss">3:00</span>
           							</span>
           							<span class="introduction">
           								<span class="introduction_text" placeholder="Introduction text">Introduction to the third song</span>
           								<span class="introduction_duration" placeholder="Introduction duration">0:15</span>
           							</span>
           						</li>
           						<li class="song" id="id_20">
           							<span class="title">Stand by Me</span>
           							<span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span>
           							<span class="spec">
           								<span class="key" placeholder="key">D</span>
           								<span class="singer" placeholder="singer">Bill</span>
               							<span class="capo" placeholder="capo">0</span>
           								<span class="id">20</span>
           								<span class="duration" placeholder="mm:ss">4:00</span>
           							</span>
           							<span class="introduction">
           								<span class="introduction_text" placeholder="Introduction text">Introduction to the fourth song</span>
           								<span class="introduction_duration" placeholder="Introduction duration">0:25</span>
           							</span>
           						</li>
           					</ol>
           				</li>
           			</ul>')
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => TRUE,
           'show_capo' => TRUE,
      	   'show_singer' => TRUE,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => sbktest_standardise_markup('<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol>
<li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span><span class="spec"><span class="key" placeholder="key">G</span><span class="singer" placeholder="singer">Euge</span><span class="capo" placeholder="capo">0</span><span class="id">34</span><span class="duration" placeholder="mm:ss">1:00</span></span></li>
<li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="key" placeholder="key">A</span><span class="singer" placeholder="singer">Ali</span><span class="capo" placeholder="capo">2</span><span class="id">38</span><span class="duration" placeholder="mm:ss">2:00</span></span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol>
<li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="key" placeholder="key">E</span><span class="singer" placeholder="singer">Breandan</span><span class="capo" placeholder="capo">1</span><span class="id">40</span><span class="duration" placeholder="mm:ss">3:00</span></span></li>
<li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span><span class="spec"><span class="key" placeholder="key">D</span><span class="singer" placeholder="singer">Bill</span><span class="capo" placeholder="capo">0</span><span class="id">20</span><span class="duration" placeholder="mm:ss">4:00</span></span></li></ol></li></ul>')
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
           'show_capo' => TRUE,
      	   'show_singer' => TRUE,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => sbktest_standardise_markup('<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol>
<li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span><span class="spec"><span class="singer" placeholder="singer">Euge</span><span class="capo" placeholder="capo">0</span><span class="id">34</span><span class="duration" placeholder="mm:ss">1:00</span></span></li>
<li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="singer" placeholder="singer">Ali</span><span class="capo" placeholder="capo">2</span><span class="id">38</span><span class="duration" placeholder="mm:ss">2:00</span></span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol>
<li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="singer" placeholder="singer">Breandan</span><span class="capo" placeholder="capo">1</span><span class="id">40</span><span class="duration" placeholder="mm:ss">3:00</span></span></li>
<li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span><span class="spec"><span class="singer" placeholder="singer">Bill</span><span class="capo" placeholder="capo">0</span><span class="id">20</span><span class="duration" placeholder="mm:ss">4:00</span></span></li></ol></li></ul>')
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
           'show_capo' => TRUE,
      	   'show_singer' => false,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => sbktest_standardise_markup('<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol>
<li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span><span class="spec"><span class="capo" placeholder="capo">0</span><span class="id">34</span><span class="duration" placeholder="mm:ss">1:00</span></span></li>
<li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="capo" placeholder="capo">2</span><span class="id">38</span><span class="duration" placeholder="mm:ss">2:00</span></span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol>
<li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="capo" placeholder="capo">1</span><span class="id">40</span><span class="duration" placeholder="mm:ss">3:00</span></span></li>
<li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span><span class="spec"><span class="capo" placeholder="capo">0</span><span class="id">20</span><span class="duration" placeholder="mm:ss">4:00</span></span></li></ol></li></ul>')
       );
/*
 * @todo
 * Add cases below where show_capo = true
 */

      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
           'show_capo' => false,
      	   'show_singer' => false,
      	   'show_id' => false,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => sbktest_standardise_markup('<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol>
<li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span></li>
<li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol>
<li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span></li>
<li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span></li></ol></li></ul>')
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
           'show_capo' => false,
      	   'show_singer' => false,
      	   'show_id' => false,
      	   'show_writtenby' => false,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => sbktest_standardise_markup('<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol>
<li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="performed_by">The Beatles</span>)</span></li>
<li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="performed_by">The Pogues</span>)</span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol>
<li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="performed_by">The Pogues</span>)</span></li>
<li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="performed_by">Ben E King</span>)</span></li></ol></li></ul>')
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
           'show_capo' => false,
      	   'show_singer' => false,
      	   'show_id' => false,
      	   'show_writtenby' => false,
      	   'show_performedby' => false,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => sbktest_standardise_markup('<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol>
<li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span></li>
<li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol>
<li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span></li>
<li class="song" id="id_20"><span class="title">Stand by Me</span></li></ol></li></ul>')
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
           'show_capo' => false,
      	   'show_singer' => false,
      	   'show_id' => false,
      	   'show_writtenby' => false,
      	   'show_performedby' => false,
      	   'show_duration' => false,
      	   'show_introduction' => false,
           'expected' => sbktest_standardise_markup('<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><ol>
<li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span></li>
<li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><ol>
<li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span></li>
<li class="song" id="id_20"><span class="title">Stand by Me</span></li></ol></li></ul>')
       );
       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_convert_playlistXML_to_list(
               $data[$index]['playlistContent'],
               $data[$index]['editable'],
               $data[$index]['show_key'],
               $data[$index]['show_capo'],
      	       $data[$index]['show_singer'],
      	       $data[$index]['show_id'],
      	       $data[$index]['show_writtenby'],
      	       $data[$index]['show_performedby'],
      	       $data[$index]['show_duration'],
      	       $data[$index]['show_introduction'],
               $data[$index]['expected']
           );
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result,$data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
       		   echo $result;
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }


   function test_sbk_convert_parsedjson_to_playlistXML() {
       global $text_playlist_xml, $text_playlist_json;

       $data[] = array(
           'parsed_json' => json_decode(trim($text_playlist_json)),
           'expected' => sbktest_standardise_markup($text_playlist_xml)
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_convert_parsedjson_to_playlistXML(
               $data[$index]['parsed_json']
           );
           $result = sbktest_standardise_markup($result->asXML());
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }

   function test_sbk_convert_song_content_to_HTML() {
       global $array_song, $text_song_body_html, $text_song_body_html_transposed_G;

       $data[] = array(
           'content' => $array_song['content'],
           'base_key' => null,
           'target_key' => null,
           'expected' => '<div class="content">'.$text_song_body_html.'</div>'
       );
       $data[] = array(
           'content' => $array_song['content'],
           'base_key' => 'D',
           'target_key' => 'G',
           'expected' => '<div class="content">'.$text_song_body_html_transposed_G.'</div>'
       );


       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_convert_song_content_to_HTML(
               $data[$index]['content'],
               $data[$index]['base_key'],
               $data[$index]['target_key']
           );
           $result = sbktest_standardise_markup($result);
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
               echo('<pre>'.htmlentities($result).'</pre>');
               echo('<pre>'.htmlentities($data[$index]['expected']).'</pre>');
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }

   function test_sbk_song_html() {
       global $array_song, $text_song_html, $text_song_html_transposed_G;

       $data[] = array(
           'record' => $array_song,
           'key' => null,
           'singer' => null,
           'expected' => $text_song_html
       );
       $data[] = array(
           'record' => $array_song,
           'key' => 'G',
           'singer' => 'Bill',
           'expected' => $text_song_html_transposed_G
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_song_html(
               $data[$index]['record'],
               $data[$index]['key'],
               $data[$index]['singer']
           );
           $result = sbktest_standardise_markup($result);
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
               echo('<pre>'.htmlentities($result).'</pre>');
               echo('<pre>'.htmlentities($data[$index]['expected']).'</pre>');
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }



   function test_sbk_find_note_number() {

       $data[] = array(
           'original_value' => 1,
           'adjustment' => 1,
           'expected' => 2
       );
       $data[] = array(
           'original_value' => 1,
           'adjustment' => 2,
           'expected' => 3
       );
       $data[] = array(
           'original_value' => 1,
           'adjustment' => 10,
           'expected' => 11
       );
       $data[] = array(
           'original_value' => 1,
           'adjustment' => 11,
           'expected' => 0
       );
       $data[] = array(
           'original_value' => 5,
           'adjustment' => 10,
           'expected' => 3
       );
       $data[] = array(
           'original_value' => 5,
           'adjustment' => -2,
           'expected' => 3
       );
       $data[] = array(
           'original_value' => 5,
           'adjustment' => -10,
           'expected' => 7
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_find_note_number(
               $data[$index]['original_value'],
               $data[$index]['adjustment']
           );
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }

   function test_sbk_shift_note() {

       $data[] = array(
           'original_value' => 'C#',
           'adjustment' => 1,
           'use_sharps' => null,
           'expected' => 'D'
       );
       $data[] = array(
           'original_value' => 'C',
           'adjustment' => 1,
           'use_sharps' => null,
           'expected' => 'C#'
       );
       $data[] = array(
           'original_value' => 'C',
           'adjustment' => 1,
           'use_sharps' => true,
           'expected' => 'C#'
       );
       $data[] = array(
           'original_value' => 'C',
           'adjustment' => 1,
           'use_sharps' => false,
           'expected' => 'Db'
       );
       $data[] = array(
           'original_value' => 'C',
           'adjustment' => 2,
           'use_sharps' => null,
           'expected' => 'D'
       );
       $data[] = array(
           'original_value' => 'C',
           'adjustment' => 10,
           'use_sharps' => null,
           'expected' => 'Bb'
       );
       $data[] = array(
           'original_value' => 'C',
           'adjustment' => 10,
           'use_sharps' => true,
           'expected' => 'A#'
       );
       $data[] = array(
           'original_value' => 'C',
           'adjustment' => 10,
           'use_sharps' => false,
           'expected' => 'Bb'
       );
       $data[] = array(
           'original_value' => 'C',
           'adjustment' => 11,
           'use_sharps' => null,
           'expected' => 'B'
       );
       $data[] = array(
           'original_value' => 'F',
           'adjustment' => 10,
           'use_sharps' => null,
           'expected' => 'Eb'
       );
       $data[] = array(
           'original_value' => 'F',
           'adjustment' => -2,
           'use_sharps' => null,
           'expected' => 'Eb'
       );
       $data[] = array(
           'original_value' => 'F',
           'adjustment' => -2,
           'use_sharps' => false,
           'expected' => 'Eb'
       );
       $data[] = array(
           'original_value' => 'F',
           'adjustment' => -2,
           'use_sharps' => true,
           'expected' => 'D#'
       );
       $data[] = array(
           'original_value' => 'F',
           'adjustment' => -10,
           'use_sharps' => null,
           'expected' => 'G'
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_shift_note(
               $data[$index]['original_value'],
               $data[$index]['adjustment'],
               $data[$index]['use_sharps']
           );
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }



   function test_sbk_transpose_chord() {

       $data[] = array(
           'chord' => 'D',
           'base_key' => 'D',
           'target_key' => 'G',
           'expected' => 'G'
       );
       $data[] = array(
           'chord' => 'Cm',
           'base_key' => 'D',
           'target_key' => 'G',
           'expected' => 'Fm'
       );
       $data[] = array(
           'chord' => 'Gdim7',
           'base_key' => 'D',
           'target_key' => 'G',
           'expected' => 'Cdim7'
       );
       $data[] = array(
           'chord' => 'Daug',
           'base_key' => 'C',
           'target_key' => 'F#',
           'expected' => 'G#aug'
       );
       $data[] = array(
           'chord' => 'Dmaj7',
           'base_key' => 'A',
           'target_key' => 'F#',
           'expected' => 'Bmaj7'
       );
       $data[] = array(
           'chord' => 'Em',
           'base_key' => 'G',
           'target_key' => 'D',
           'expected' => 'Bm'
       );
       $data[] = array(
           'chord' => 'Ebm',
           'base_key' => 'G#',
           'target_key' => 'D',
           'expected' => 'Am'
       );
       $data[] = array(
           'chord' => 'Em/g',
           'base_key' => 'G',
           'target_key' => 'D',
           'expected' => 'Bm/d'
       );
       $data[] = array(
           'chord' => 'C/g',
           'base_key' => 'G#',
           'target_key' => 'D',
           'expected' => 'F#/c#'
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_transpose_chord(
               $data[$index]['chord'],
               $data[$index]['base_key'],
               $data[$index]['target_key']
           );
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }

   function test_sbk_note_to_upper() {

       $data[] = array(
           'note' => 'D',
           'expected' => 'D'
       );
       $data[] = array(
           'note' => 'D#',
           'expected' => 'D#'
       );
       $data[] = array(
           'note' => 'Db',
           'expected' => 'Db'
       );
       $data[] = array(
           'note' => 'd',
           'expected' => 'D'
       );
       $data[] = array(
           'note' => 'd#',
           'expected' => 'D#'
       );
       $data[] = array(
           'note' => 'db',
           'expected' => 'Db'
       );
       $data[] = array(
           'note' => 'F',
           'expected' => 'F'
       );
       $data[] = array(
           'note' => 'F#',
           'expected' => 'F#'
       );
       $data[] = array(
           'note' => 'Fb',
           'expected' => 'Fb'
       );
       $data[] = array(
           'note' => 'f',
           'expected' => 'F'
       );
       $data[] = array(
           'note' => 'f#',
           'expected' => 'F#'
       );
       $data[] = array(
           'note' => 'fb',
           'expected' => 'Fb'
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_note_to_upper(
               $data[$index]['note']
           );
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }

   function test_sbk_note_to_lower() {

       $data[] = array(
           'note' => 'D',
           'expected' => 'd'
       );
       $data[] = array(
           'note' => 'D#',
           'expected' => 'd#'
       );
       $data[] = array(
           'note' => 'Db',
           'expected' => 'db'
       );
       $data[] = array(
           'note' => 'd',
           'expected' => 'd'
       );
       $data[] = array(
           'note' => 'd#',
           'expected' => 'd#'
       );
       $data[] = array(
           'note' => 'db',
           'expected' => 'db'
       );
       $data[] = array(
           'note' => 'F',
           'expected' => 'f'
       );
       $data[] = array(
           'note' => 'F#',
           'expected' => 'f#'
       );
       $data[] = array(
           'note' => 'Fb',
           'expected' => 'fb'
       );
       $data[] = array(
           'note' => 'f',
           'expected' => 'f'
       );
       $data[] = array(
           'note' => 'f#',
           'expected' => 'f#'
       );
       $data[] = array(
           'note' => 'fb',
           'expected' => 'fb'
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_note_to_lower(
               $data[$index]['note']
           );
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }

}

$STANDARD_JAVASCRIPTS[] = URL_TO_ACRA_SCRIPTS."/js/jquery.contextMenu/jquery.contextMenu.js";
$STANDARD_JAVASCRIPTS[] = "../index.js";
echo acradisp_standardHTMLheader("songbook tests", array('index.css'), $STANDARD_JAVASCRIPTS)
?>
<link media="screen" type="text/css" href="qunit/qunit.css" rel="stylesheet">
<script src="qunit/qunit.js" type="text/javascript"></script>

<script>
$(document).ready(function(){
module("Basic");
test("convert_playlist_to_json()", 1, function () {
     var test_playlist = jQuery('<div><?php  echo str_replace("'", "\'", $text_full_html) ?></div>'),
         result, expected;

     result = convert_playlist_to_json(test_playlist);
     expected = <?php echo $text_playlist_json; ?>;

     same (result, expected, 'converted');

});

});

</script>

 <h1 id="qunit-header">Songbook Tests</h1>
 <h2 id="qunit-banner" class="qunit-fail"></h2>
 <div id="qunit-testrunner-toolbar"></div>
 <h2 id="qunit-userAgent"></h2>
 <p id="qunit-testresult" class="result"></p>
 <ol id="qunit-tests"></ol>
 <div id="qunit-fixture"></div>


</body></html>

