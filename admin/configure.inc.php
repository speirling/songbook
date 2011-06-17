<?php
/*---------------------------------------------------------
*
* Configuration parameters for the acradb system
*
----------------------------------------------------------*/
		//p("GET variables: ",print_r($_GET,1),"POST variables: ".print_r($_POST,1),"FILES variables: ".print_r($_FILES,1),"max upload filesize :".ini_get("upload_max_filesize"),"Max total upload size: ".ini_get("post_max_size"));

//+++++++++++++++++++++++++++++++++++++++++++++++++++
//Identify the system for logging etc.
define("SYSTEM_ID","Epeelo Songbook");
//+++++++++++++++++++++++++++++++++++++++++++++++++++

//---------------------------------------------------------------------------------------------------------------------------------------
//*Local XAMPP Paths
  define("URL_TO_DATASET_DEFINITIONS","songbook");
  define("ABSOLUTE_PATH_TO_DATASET_DEFINITIONS", $_SERVER['DOCUMENT_ROOT']."/".URL_TO_DATASET_DEFINITIONS);

  //The path to the acra_i scripts must be ABSOLUTE... so that the scripts can be held out of the scope of the current site
  //NOTE: If acra_i is outside the scope of the current site, then you can't use the standard javascript libraries... or else they will have to be held separate from acra_i.... or else you might have symbolic links or mapping in the httpd.conf file
  define("URL_TO_ACRA_SCRIPTS","/acra_i");
  define("ABSOLUTE_PATH_TO_ACRA_SCRIPTS",$_SERVER['DOCUMENT_ROOT'].URL_TO_ACRA_SCRIPTS);

  define("ACRA_DOC_URL","/acra_i/doc");
  define("URL_TO_DATASET_STYLESHEETS","/songbook");

  //it would be a security risk to leave this one ON (true)
  //if this is true, AND if there's a GET parameter debug (any value)
  //Then detailed debug information will be displayed.
  //This could give far too much useful detail to hackers, so only leave it on for very short periods of time (when debugging)
  define("ALLOW_DEBUG_DISPLAY", true);

  	define("DATABASE_USERNAME", "root");
	define("DATABASE_PASSWORD", "chancery");
	define("DATABASE_SERVERHOST", "localhost");

	define("ENABLE_DB_TRANSACTION_LOGGING", true);
	define("LOG_DIRECTORY", "/songbook/acra_logs");
	define("LOG_FILENAME", "goodgigsAC_log");
	define("LOG_MAX_FILESIZE", "65536"); //File size in bytes

	define("EMAIL_ON_UPDATE", false); // XAMPP doesn't have mailserver - s EMAIL_ON_UPDATE must be false to avoid errors
	//define("EMAIL_ON_UPDATE_ADDRESS","good_gigs@eircom.net;epeelo@indigo.ie");
	define("EMAIL_ON_UPDATE_ADDRESS", "");// XAMPP doesn't have mailserver - but EMAIL_ON_UPDATE_ADDRESS must be defined for acra_db to work.
	//define("TRANSACTION_REPORT_EMAIL_ADDRESS","good_gigs@eircom.net;epeelo@indigo.ie"); //if this is not defined, then contactGoodGigs_reQuote wont' try emailing.

//to facilitate testing on one server and uploading to another, it might be an idea to specify the main database here.
//As things currently stand (EP20080904) all datasets in a system MUST use the same database
	define("PRIMARY_DATABASE", "music_admin");

	define("AJAXSEQUENTIALLIST_URL", "/songbook/admin/ajaxSequentialList.php");

//-*/ //end of Local XAMPP
//---------------------------------------------------------------------------------------------------------------------------------------


    //Parameters specific to this installation (Songbook)
	define("DATASET_DEFINITION_FILENAME","admin/dataset.php");
	//define("CUSTOMFIELD_DEFINITION_FILENAME","_customFields.inc.php");

//+++++++++++++++++++++++++++++++++++++++++++++++++++
//Registered User:
	//If you want to manage users through the database, define these constants
	define("USER_DATABASE", PRIMARY_DATABASE);
	define("USER_TABLE", "users");
	define("USER_FIELD", "primary_email");
	define("PASSWORD_FIELD", "password");
	define("ACRA_LOGINPAGE", "/acra_googgigsAC/ggac.php");
//+++++++++++++++++++++++++++++++++++++++++++++++++++

//+++++++++++++++++++++++++++++++++++++++++++++++++++
//PASSWORDS
//Passwords can be 'salted' by inserting a string before and/or after the actual password before calculating the hash
//define here the actual salts to be used
define("SALT_PRE", "");
define("SALT_POST", "fl6&c");
//+++++++++++++++++++++++++++++++++++++++++++++++++++

//define("EDITFORM_PRESENTATION","modalDialog")	;
//define("EDITFORM_PRESENTATION","inlineExpand")	;
define("EDITFORM_PRESENTATION", "inlineFixed")	;
//define("DEFAULT_NUMBER_OF_ITEMS_PER_PAGE_IN_SEQUENTIAL_LIST",5);

//+++++++++++++++++++++++++++++++++++++++++++++++++++
//Include Dataset definitions,  acra_i library, and any custom fields
	define("CONFIGURATION_FILE", str_replace($_SERVER['DOCUMENT_ROOT'],"",str_replace("\\", "/", __FILE__)) );
	if(file_exists($_SERVER['DOCUMENT_ROOT'].URL_TO_ACRA_SCRIPTS."/complete.php")) {require($_SERVER['DOCUMENT_ROOT'].URL_TO_ACRA_SCRIPTS."/complete.php");} else {die( __FILE__." line ".__LINE__." :: \n<br />The acra_i library cannot be accessed (at ".$_SERVER['DOCUMENT_ROOT'].URL_TO_ACRA_SCRIPTS.").\n<br />This script cannot continue. Sorry.");	}
//+++++++++++++++++++++++++++++++++++++++++++++++++++

?>