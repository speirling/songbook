<?php
require_once 'admin/configure.inc.php';

$display = '';

if(array_key_exists('search_string', $_GET)) {
    $search_string = $_GET['search_string'];
} else {
    $search_string = false;
}
$display = $display.sbk_list_all_songs_in_database($search_string);

echo $display;
?>