
<h1>Songs Database</h1>
<ol>

<?php 
foreach ($songs as $song) {
    echo '<li>['.$song['Song']['id'].'] '.$song['Song']['title'].'</li>';
//print_r($song['Song']);
}

?>
</ol>
