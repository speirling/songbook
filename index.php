<?php
require("acra_i/acra_disp.php");
require("wordHTML.php");

$dir = "Restaurant Songbook";
$title = "Restaurant Songbook";

$songList = scandir($dir);
$songBookContent = "";


$sectionNumber = 2;

foreach($songList as $filename) {
    if(!is_dir($filename)) {
   $sectionNumber = $sectionNumber + 1;
     $lyrics_as_string = "<div class=Section".$sectionNumber.">";
    $lineCount = 0;
      $lyrics_as_array = file($dir."/".$filename);
        foreach($lyrics_as_array as $line) {
            $lineCount = $lineCount + 1;
            if($lineCount == 1) {
                $lyrics_as_string .= "<h1>".$line."</h1>\n";
            } elseif($lineCount == 2) {
                $lyrics_as_string .= "<h2>".$line."</h2>\n<p class='lyrics'>";
            } else {
                $lyrics_as_string .= $line."<br />\n";
            }
        }
        $songBookContent .= $lyrics_as_string."</p>\n";
        $songBookContent .= "\n</div>".$pageBreak."\n";
        $PageDefinitions = $PageDefinitions."\n@page Section".$sectionNumber."\n{";
        $PageDefinitions = $PageDefinitions.$pageDefinitionSingleColumn;
        if($lineCount > 50) {
            $PageDefinitions = $PageDefinitions.$pageDefinitionDoubleColumn;
        }
        $PageDefinitions = $PageDefinitions."}\n";
        $PageDefinitions = $PageDefinitions."div.Section".$sectionNumber."\n{page:Section".$sectionNumber.";}\n";
    }
}


$songBook = $fileHeaderBeforeTitle.
                $title.
                $fileHeaderFromTitleToPageDefinitions.
                $PageDefinitions.
                $fileHeaderAfterPageDefinitions.
                $titlePage.
                $contentsPage.
                $songBookContent.
                $footer;

$fp = fopen($title.'.htm', 'w');
if(fwrite($fp, $songBook)) {
    echo "File ".$title.".htm written";
} else {
    echo "ERROR : There was a problem writing file".$title.".htm";
}
fclose($fp);

?>