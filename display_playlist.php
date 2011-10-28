<?php
require_once 'admin/configure.inc.php';

$display = '';

if(array_key_exists('playlist', $_GET)) {
    $playlist = $_GET['playlist'];
} else {
    $playlist = false;
}
$display = $display.sbk_playlist_as_editable_html($playlist);
//$display = $display.sbk_convert_playlistXML_to_orderedlist($playlistContent, $show_key = FALSE, $show_singer = FALSE, $show_id = FALSE, $show_writtenby = FALSE, $show_performedby = FALSE)

echo $display;
?>