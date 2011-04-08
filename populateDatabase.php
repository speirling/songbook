<?php
require_once 'admin/configure.inc.php';

p("test");

$dir = "/fileserver/data/www/covers/";

$songList = scandir($dir);

$blank_table_query = '
DROP TABLE IF EXISTS `lyrics`;
CREATE TABLE IF NOT EXISTS `lyrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `written_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `performed_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `original_filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_tags` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
';
//echo "Blanking table query result: ".acradb_get_query_result($blank_table_query, 'music_admin').mysql_error();"<br><hr><br>";
//echo "<table>";
$songCount = 0;
foreach($songList as $filename) {
    $original_filename = $lyrics_as_array = $title = $written_by = $content = $line =Null;
    $lineCount = 0;
    $songCount = $songCount + 1;

if(trim($filename) !== '.' && trim($filename) !== '..') {
    if(!is_dir($filename)) {
      $original_filename  = $filename;
      $lyrics_as_array = file($dir."/".$filename);
        foreach($lyrics_as_array as $line) {
            $lineCount = $lineCount + 1;
            if($lineCount == 1) {
                $title = $line;
            } elseif($lineCount == 2) {
                $written_by = $line;
            } else {
                $content = $content."\n".$line;
            }
        }
    }


    	//echo "<tr><td>$songCount</td><td>$original_filename</td><td>$title</td><td>$written_by</td><td>$content</td></tr>";

        $query = 'INSERT INTO `music_admin`.`lyrics`
        (`title`, `written_by`, `content`, `original_filename`)
        VALUES
        ("'.addslashes($title).'", "'.addslashes($written_by).'", "'.addslashes($content).'", "'.addslashes($original_filename).'");';

//echo "query result: ".acradb_get_query_result($query, 'music_admin').mysql_error();"<br><hr><br>";
}
}
echo "<br>Complete - ".$songCount." songs<br>";
//echo "</table>";


?>