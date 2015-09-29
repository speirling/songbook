
<h1>Lyrics Table</h1>
<ol>

<?php 
foreach ($lyrics as $lyric) {
    echo '<li>['.$lyric['Lyric']['id'].'] '.$song['Lyric']['title'].'</li>';
//print_r($lyric['Lyric']);
}

?>
</ol>
