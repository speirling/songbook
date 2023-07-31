<?php
/* reuires the following variable to be available:
 * $query : CCake\ORM\Query (returned by filterAllSongs($f)
 * $selected_performers = $f['performers'] : the part of the filter definition for $query above that relates to performer(s)
 */
$html = '';
$performer_filter_on = sizeof($selected_performers) > 0 ?  true: false;

//debug($filter_on);

$html = $html . '<div class="filtered-songlist">';
if($this->print_title !== '') {
    $html = $html . '<span class="filter-title">';
    $html = $html . $this->print_title;
    $html = $html . '</span>';
}
$html = $html . '<ul id="songlist">';
$primary_key = "";
$primary_capo = "";
foreach ($query as $song) {
    $performers_html='';
    $existing_performer_keys = [];
    foreach ($song['set_songs'] as $set_song) {
        $performer_key = $set_song['performer']['nickname'].$set_song['key'];
        if (!in_array($performer_key, $existing_performer_keys)) {
            array_push($existing_performer_keys, $performer_key);
            if($performer_filter_on == false || in_array($set_song['performer']['id'], $selected_performers) == true) {
                $performers_html = $performers_html . '<span class="performer short-form">';
                $performers_html = $performers_html . '<span class="nickname">' . strtolower(substr($set_song['performer']['nickname'], 0, 1)) . '</span>';
                $performers_html = $performers_html . '<span class="key">' . $set_song['key'] . '</span>';
                $performers_html = $performers_html . '</span>';
                
                $primary_key = $set_song['key'];
                $primary_capo = $set_song['capo'];
            }
        }
    }
    $html = $html . '<li data-id="' . $song['id'] . '" data-key="' . $primary_key . '" data-capo="' . $primary_capo . '">';
    $html = $html . '<span class="song-title">' . $song['title'] . "</span>";
    $html = $html . $performers_html;
    $html = $html . '</li>';
}
$html = $html . '</ul>';
$html = $html . '</div>';

echo $html;
?>