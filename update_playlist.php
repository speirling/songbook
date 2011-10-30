<?php
require_once 'admin/configure.inc.php';

$display = '';
$data_string = (string) $_POST['data'];
if(array_key_exists('filename', $_POST)) {
    $filename = $_POST['filename'];

    $data_string = str_replace('\"', '"', trim($data_string, '()'));
    $data = json_decode(trim($data_string, '()'));
    $playlist_XML = sbk_convert_parsedjson_to_playlistXML($data);

    $destination = str_replace('\\','/',getcwd()).'/'.PLAYLIST_DIRECTORY.'/'.$filename.'.playlist';
    if($playlist_XML->saveXML($destination)) {
         $display = '{"success": true, "destination": "'.$destination.'"}';
    }
} else {
    $display = '{"error": "no playlist filename specified"}';
}

echo $display;
?>