<?php
/**
If you don't keep everything under $_SERVER['DOCUMENT_ROOT']
- if you're using aliases in the apache config -
then this file must be copied to the specific application, and the $_SERVER['DOCUMENT_ROOT'].$_GET['configFile'] replaced with hardcoded path
and the standard acra header changed
*/
require('configure.inc.php');
$output = "
	var URL_TO_ACRA_SCRIPTS = \"";
	if(defined("URL_TO_ACRA_SCRIPTS")) {
		$output .= URL_TO_ACRA_SCRIPTS;
	} else {
		$output .= "UNDEFINED";
	}
$output .= "\";
";

echo $output;
?>