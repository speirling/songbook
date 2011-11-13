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
    return trim(preg_replace('/>[\s\n]*?</', '><', $string));
}

$text_playlist_xml = <<<XML
<?xml version="1.0" standalone="yes"?>
<songlist title="Test Playlist" act="Cafe Ceili">
  <introduction duration="0:55">Introduction to the show</introduction>
  <set label="Set 1">
    <introduction duration="0:05">Introduction to the first set</introduction>
    <song id="34" key="G" singer="Euge" duration="1:00">
      <introduction duration="0:30">Introduction to the first song</introduction>
    </song>
    <song id="38" key="A" singer="Ali" duration="2:00">
      <introduction duration="0:45">Introduction to the second song</introduction>
    </song>
  </set>
  <set label="Set 2">
    <introduction duration="0:05">Introduction to the second set</introduction>
    <song id="40" key="E" singer="Breandan" duration="3:00">
      <introduction duration="0:15">Introduction to the third song</introduction>
    </song>
    <song id="20" key="D" singer="Bill" duration="4:00">
      <introduction duration="0:25">Introduction to the fourth song</introduction>
    </song>
  </set>
</songlist>
XML;

$text_full_html = sbktest_standardise_markup('
<input type="text" class="playlist-title" value="Test Playlist" />
<input type="text" class="act" value="Cafe Ceili" />
<span class="introduction songlist">
<textarea class="introduction_text">Introduction to the show</textarea>
<input type="text" class="introduction_duration" value="0:55" />
</span>
<ul>
<li class="set playlist">
  <input type="text" class="set-title" value="Set 1" />
  <span class="duration">04:15</span>
  <span class="introduction set">
    <textarea class="introduction_text">Introduction to the first set</textarea>
    <input type="text" class="introduction_duration" value="0:05" />
  </span>
  <ol>
    <li class="song" id="id_34">
      <input type="text" class="singer" value="Euge" />
      <input type="text" class="key" value="G" />
      <span class="id">34</span>
      <input type="text" class="duration" value="1:00" />
      <span class="title">A Hard Day\'s Night</span>
      <span class="introduction">
        <textarea class="introduction_text">Introduction to the first song</textarea>
        <input type="text" class="introduction_duration" value="0:30" />
      </span>
    </li>
    <li class="song" id="id_38">
      <input type="text" class="singer" value="Ali" />
      <input type="text" class="key" value="A" />
      <span class="id">38</span>
      <input type="text" class="duration" value="2:00" />
      <span class="title">A Pair Of Brown Eyes</span>
      <span class="introduction">
        <textarea class="introduction_text">Introduction to the second song</textarea>
        <input type="text" class="introduction_duration" value="0:45" />
      </span>
    </li>
    <li class="dummy">&nbsp;</li>
  </ol>
</li>
<li class="set playlist">
  <input type="text" class="set-title" value="Set 2" />
  <span class="duration">07:40</span>
  <span class="introduction set">
    <textarea class="introduction_text">Introduction to the second set</textarea>
    <input type="text" class="introduction_duration" value="0:05" />
  </span>
  <ol>
    <li class="song" id="id_40">
    <input type="text" class="singer" value="Breandan" />
    <input type="text" class="key" value="E" />
    <span class="id">40</span>
    <input type="text" class="duration" value="3:00" />
    <span class="title">A Rainy Night In Soho</span>
    <span class="introduction">
      <textarea class="introduction_text">Introduction to the third song</textarea>
      <input type="text" class="introduction_duration" value="0:15" />
    </span>
  </li>
  <li class="song" id="id_20">
    <input type="text" class="singer" value="Bill" />
    <input type="text" class="key" value="D" />
    <span class="id">20</span>
    <input type="text" class="duration" value="4:00" />
    <span class="title">Stand by Me</span>
    <span class="introduction">
      <textarea class="introduction_text">Introduction to the fourth song</textarea>
      <input type="text" class="introduction_duration" value="0:25" />
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
			{"id": "id_34", "key": "G", "singer": "Euge", "duration": "1:00", "introduction": {"text": "Introduction to the first song", "duration": "0:30"}},
			{"id": "id_38", "key": "A", "singer": "Ali", "duration": "2:00", "introduction": {"text": "Introduction to the second song", "duration": "0:45"}}
		]
	}, {
		"label": "Set 2",
		"introduction": {"text": "Introduction to the second set", "duration": "0:05"},
		"songs": [
			{"id": "id_40", "key": "E", "singer": "Breandan", "duration": "3:00", "introduction": {"text": "Introduction to the third song", "duration": "0:15"}},
			{"id": "id_20", "key":"D", "singer": "Bill", "duration": "4:00", "introduction": {"text": "Introduction to the fourth song", "duration": "0:25"}}
		]
	}]
}';

class songbook_tests extends UnitTestCase {

   function test_sbk_convert_playlistXML_to_list() {
       global $text_playlist_xml, $text_full_html;

      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => true,
           'show_key' => TRUE,
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
      	   'show_singer' => TRUE,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => true,
           'expected' => '<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><span class="introduction songlist"><span class="introduction_text">Introduction to the show</span><span class="introduction_duration">0:55</span></span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><span class="introduction set"><span class="introduction_text">Introduction to the first set</span><span class="introduction_duration">0:05</span></span><ol><li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span><span class="spec"><span class="key">G</span><span class="singer">Euge</span><span class="id">34</span><span class="duration">1:00</span></span><span class="introduction"><span class="introduction_text">Introduction to the first song</span><span class="introduction_duration">0:30</span></span></li><li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="key">A</span><span class="singer">Ali</span><span class="id">38</span><span class="duration">2:00</span></span><span class="introduction"><span class="introduction_text">Introduction to the second song</span><span class="introduction_duration">0:45</span></span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><span class="introduction set"><span class="introduction_text">Introduction to the second set</span><span class="introduction_duration">0:05</span></span><ol><li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="key">E</span><span class="singer">Breandan</span><span class="id">40</span><span class="duration">3:00</span></span><span class="introduction"><span class="introduction_text">Introduction to the third song</span><span class="introduction_duration">0:15</span></span></li><li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span><span class="spec"><span class="key">D</span><span class="singer">Bill</span><span class="id">20</span><span class="duration">4:00</span></span><span class="introduction"><span class="introduction_text">Introduction to the fourth song</span><span class="introduction_duration">0:25</span></span></li></ol></li></ul>'
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => TRUE,
      	   'show_singer' => TRUE,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => '<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol><li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span><span class="spec"><span class="key">G</span><span class="singer">Euge</span><span class="id">34</span><span class="duration">1:00</span></span></li><li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="key">A</span><span class="singer">Ali</span><span class="id">38</span><span class="duration">2:00</span></span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol><li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="key">E</span><span class="singer">Breandan</span><span class="id">40</span><span class="duration">3:00</span></span></li><li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span><span class="spec"><span class="key">D</span><span class="singer">Bill</span><span class="id">20</span><span class="duration">4:00</span></span></li></ol></li></ul>'
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
      	   'show_singer' => TRUE,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => '<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol><li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span><span class="spec"><span class="singer">Euge</span><span class="id">34</span><span class="duration">1:00</span></span></li><li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="singer">Ali</span><span class="id">38</span><span class="duration">2:00</span></span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol><li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="singer">Breandan</span><span class="id">40</span><span class="duration">3:00</span></span></li><li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span><span class="spec"><span class="singer">Bill</span><span class="id">20</span><span class="duration">4:00</span></span></li></ol></li></ul>'
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
      	   'show_singer' => false,
      	   'show_id' => TRUE,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => '<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol><li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span><span class="spec"><span class="id">34</span><span class="duration">1:00</span></span></li><li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="id">38</span><span class="duration">2:00</span></span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol><li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span><span class="spec"><span class="id">40</span><span class="duration">3:00</span></span></li><li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span><span class="spec"><span class="id">20</span><span class="duration">4:00</span></span></li></ol></li></ul>'
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
      	   'show_singer' => false,
      	   'show_id' => false,
      	   'show_writtenby' => TRUE,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => '<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol><li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="written_by">Lennon/McCartney</span> | <span class="performed_by">The Beatles</span>)</span></li><li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol><li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="written_by">Shane McGowan</span> | <span class="performed_by">The Pogues</span>)</span></li><li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="written_by">Ben E King</span> | <span class="performed_by">Ben E King</span>)</span></li></ol></li></ul>'
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
      	   'show_singer' => false,
      	   'show_id' => false,
      	   'show_writtenby' => false,
      	   'show_performedby' => TRUE,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => '<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol><li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span><span class="detail"> (<span class="performed_by">The Beatles</span>)</span></li><li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span><span class="detail"> (<span class="performed_by">The Pogues</span>)</span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol><li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span><span class="detail"> (<span class="performed_by">The Pogues</span>)</span></li><li class="song" id="id_20"><span class="title">Stand by Me</span><span class="detail"> (<span class="performed_by">Ben E King</span>)</span></li></ol></li></ul>'
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
      	   'show_singer' => false,
      	   'show_id' => false,
      	   'show_writtenby' => false,
      	   'show_performedby' => false,
      	   'show_duration' => true,
      	   'show_introduction' => false,
           'expected' => '<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><span class="duration">04:15</span><ol><li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span></li><li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><span class="duration">07:40</span><ol><li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span></li><li class="song" id="id_20"><span class="title">Stand by Me</span></li></ol></li></ul>'
       );
      $data[] = array(
           'playlistContent' => new SimpleXMLElement($text_playlist_xml),
           'editable' => false,
           'show_key' => false,
      	   'show_singer' => false,
      	   'show_id' => false,
      	   'show_writtenby' => false,
      	   'show_performedby' => false,
      	   'show_duration' => false,
      	   'show_introduction' => false,
           'expected' => '<span class="playlist-title">Test Playlist</span><span class="act">Cafe Ceili</span><ul><li class="set playlist"><span class="set-title">Set 1</span><ol><li class="song" id="id_34"><span class="title">A Hard Day\'s Night</span></li><li class="song" id="id_38"><span class="title">A Pair Of Brown Eyes</span></li></ol></li><li class="set playlist"><span class="set-title">Set 2</span><ol><li class="song" id="id_40"><span class="title">A Rainy Night In Soho</span></li><li class="song" id="id_20"><span class="title">Stand by Me</span></li></ol></li></ul>'
       );
       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = sbk_convert_playlistXML_to_list(
               $data[$index]['playlistContent'],
               $data[$index]['editable'],
               $data[$index]['show_key'],
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

