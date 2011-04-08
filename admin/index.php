<?php
/* *************************************************
* This script reads a single database dataset,
* defined in _datasets.inc.php,
* and creates a complete management interface for it
*
* If no dataset is specified, it will display a list
* of available datasets.
*
*	Eugene Peelo March 2007 - 2011
* (original single-table version)Eugene Peelo July 2006
*
*/

$outputHTML = "";
$pageBodyHTML = "";
$localJavascriptStatements = "";
$modalDialogHTML = "";


//+++++++++++++++++++++++++++++++++++++++++++++++++++
//parameters for this script

  require("configure.inc.php");

//+++++++++++++++++++++++++++++++++++++++++++++++++++

p($current_dataset->datasetCaption);


  //set the dataViews that will be EXCLUDED from view in this page:
  //(if this array is empty, then ALL dataviews will be displayed)
  $hiddenDataviews = array(
  	"pivotAct",   //pivot table
	"pivotEvent",	//pivot table
  	"events"		//identical content to event_ads, but event_ads is better formatted.
  );

  $visibleDataviews= array();
  foreach($dataViewsToUse as $thisView) {
  	if(!in_array($thisView,$hiddenDataviews)) {
  		$visibleDataviews[] = $thisView;
  	}
  }




//Define all possible menu items for this 'environment' (environment = set of datasets)
	$menuDefinition['index'] 				= array('destination' => "index.php",															'caption' => "Index"				);
	$menuDefinition['add']					= array('destination' => "?datasetHandle=".CURRENT_DATASET_HANDLE."&display=editSingleForm",	'caption' => "Add ".$menuRecordCaption	);
	$menuDefinition['exportDisplay']		= array('destination' => "create_listPage.php?datasetHandle=".CURRENT_DATASET_HANDLE,			'caption' => "export display"			);
	$menuDefinition['exportEntry']			= array('destination' => "create_entryForm.php?datasetHandle=".CURRENT_DATASET_HANDLE,			'caption' => "export entry"				);
	$menuDefinition['exportUpdateAction']	= array('destination' => "create_updateAction.php?datasetHandle=".CURRENT_DATASET_HANDLE,		'caption' => "export updater"			);
	$menuDefinition['grid']					= array('destination' => "?datasetHandle=".CURRENT_DATASET_HANDLE."&display=sortGrid",			'caption' => "Sortable Grid"			);
	$menuDefinition['list']					= array('destination' => "?datasetHandle=".CURRENT_DATASET_HANDLE."&display=seqList",			'caption' => "Sequential List"			);
	$menuDefinition['showStylesheet']		= array('destination' => "?datasetHandle=".CURRENT_DATASET_HANDLE."&display=showStylesheet",	'caption' => "Show Stylesheet"		);
	$menuDefinition['saveStylesheet']		= array('destination' => "?datasetHandle=".CURRENT_DATASET_HANDLE."&display=saveStylesheet",	'caption' => "Save Stylesheet"		);
//	$menuDefinition['exportStylesheet']		= array('destination' => "?datasetHandle=".CURRENT_DATASET_HANDLE."&display=exportStylesheet",	'caption' => "Export Stylesheet"		);
	$menuDefinition['backupAllTables']		= array('destination' => "?datasetHandle=".CURRENT_DATASET_HANDLE."&display=backupAllTables",	'caption' => "Backup all datasets"		);
	$menuDefinition['backupCurrentDataset']	= array('destination' => "?datasetHandle=".CURRENT_DATASET_HANDLE."&display=backupCurrentDataset",	'caption' => "Save Backup"		);
	$menuDefinition['help']					= array('destination' => ACRA_DOC_URL."/index.html",							'caption' => "Help"		);




 /*
$pageBodyHTML .=  "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
$pageBodyHTML .=  "<html>\n";
$pageBodyHTML .=  "<head>\n";
$pageBodyHTML .=  "	<title>ACRA_db interface index</title>\n";
$pageBodyHTML .=  "</head>\n";

$pageBodyHTML .=  "<body>\n";
*/
$local_stylesheets = array();
$pageHeaderJavascripts = $STANDARD_JAVASCRIPTS;
$pageHeaderStylesheets = array_merge($STANDARD_STYLESHEETS,$local_stylesheets);

if(!array_key_exists("display",$_GET))
{
	//default page - let user select dataset and display mode
	$pageBodyHTML .=  "<link href=\"".URL_TO_DATASET_STYLESHEETS."/admin.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	$pageBodyHTML .=  "<link href=\"".URL_TO_DATASET_STYLESHEETS."/default.css\" rel=\"stylesheet\" type=\"text/css\">\n";

	$pageBodyHTML .=  "<h1>\n";
	$pageBodyHTML .=  "ACRA DB Index Page\n";
	$pageBodyHTML .=  "</h1>\n";


	$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','backupAllTables','help'));


	$pageBodyHTML .=  "<p>The following datasets can be managed from this system:</p>\n";
	$pageBodyHTML .=  "<table id=\"dataViewList\">\n";
	foreach ( $visibleDataviews as $title)
	{
		$datasetObject = $dataset[$title];
		$pageBodyHTML .=  "<tr>\n";
		$pageBodyHTML .=  "<td>\n";
		$pageBodyHTML .=  "<dt>\n";
		$pageBodyHTML .=  "<a href=\"\n";
		$pageBodyHTML .=  "?display=seqlist&datasetHandle=\n";
		$pageBodyHTML .=  $title;
		$pageBodyHTML .=  "\" " ;
		//$pageBodyHTML .=  "class=\"button\" \n";
		$pageBodyHTML .=  ">\n";
		$pageBodyHTML .=  $title;
		$pageBodyHTML .=  "</a>\n";
		$pageBodyHTML .=  "</dt>\n";
			$pageBodyHTML .=  "<dd>";
			$pageBodyHTML .=  $datasetObject->description;
			$pageBodyHTML .=  "</dd>";
		$pageBodyHTML .=  "</td>";
		$pageBodyHTML .=  "</tr>\n";
	}
	$pageBodyHTML .=  "</table>\n";
}

else
{
	//$current_dataset->path_to_dataset_definition = $path_to_dataset_definition;
	$current_dataset->use_admin_stylesheet = true;
	$current_dataset->use_default_stylesheet = true;

		/* //debug
		$pageBodyHTML .=  "<pre>\n";
		$pageBodyHTML .=  "[".basename(__FILE__)." : line ".__LINE__."] \n";
		$pageBodyHTML .=  "A display mode has been selected : ".$_GET['display']."\n\n";
		$pageBodyHTML .=  "The dataset is : ".$_GET['datasetHandle']."\n\n";
		$pageBodyHTML .=  "</pre>\n";
		//-*/

	switch(strtolower($_GET['display']))
	{

		//----------------------------------------------------------------------------------------------
		case "seqlist":      //Sequential List display
			$pageBodyHTML .=  "<h1>";
			$pageBodyHTML .=  $current_dataset->datasetCaption;
			$pageBodyHTML .=  " List page\n";
			$pageBodyHTML .=  "</h1>\n\n";
			$pageBodyHTML .=  "<p>";
			$pageBodyHTML .=  $current_dataset->description;
			$pageBodyHTML .=  "</p>";

			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));

			$current_dataset->addButton(
				$arrayOfViewsInWhichThisButtonWillBeVisisble =array("sequentialList","sortGrid"),	//can be "sequentialList","editRecord","viewSingleRecord" "deleteSingleConfirm" "sortGrid"
				$caption="Edit this ".$current_dataset->recordCaption,
				$destinationIfTheButtonIsAredirect = "?display=editsingleform&datasetHandle=".$current_dataset->datasetHandle,
				$onclickIfThisButtonIsNotAredirect = null,
				$fieldsToBeAttachedToEditFormAction = array(
															array(
																	"destinationDatasetHandle"=>$current_dataset->datasetHandle,
																	"destinationFieldLabel"=>$current_dataset->primaryKeyField["label"],
																	"valueSourceFieldLabel"=>$current_dataset->primaryKeyField["label"]
																	)
							)
				);

			$current_dataset->addButton(
				$arrayOfViewsInWhichThisButtonWillBeVisisble =array("sequentialList","sortGrid"),	//can be "sequentialList","editRecord","viewSingleRecord" "deleteSingleConfirm" "sortGrid"
				$caption="Delete this ".$current_dataset->recordCaption,
				$hrefIfTheButtonIsAHyperlink = "?display=deletesingleconfirm&datasetHandle=".$current_dataset->datasetHandle,
				$onclickIfThisButtonIsNotAHyperlink = null,		//alternative to href
				$fieldsToBeAttachedToEditFormAction = array(
															array(
																	"destinationDatasetHandle"=>$current_dataset->datasetHandle,
																	"destinationFieldLabel"=>$current_dataset->primaryKeyField["label"],
																	"valueSourceFieldLabel"=>$current_dataset->primaryKeyField["label"]
																	)
							)
				);

			$pageBodyHTML .=  $current_dataset->displaySequentialList();

							//p(print_r($current_dataset,1));
		break;





		//----------------------------------------------------------------------------------------------
		case "sortgrid":     //Sortable Grid display

			$pageBodyHTML .=  "<h1>\n";
			$pageBodyHTML .=  $current_dataset->datasetCaption;
			$pageBodyHTML .=  " Sortable Grid page\n";
			$pageBodyHTML .=  "</h1>\n\n";

			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));

			$pageBodyHTML .=  $current_dataset->displaySortableGrid();
		break;




		//----------------------------------------------------------------------------------------------
		case "editsingleform":       //edit Page
						//acradisp_print("editing ".$current_dataset->datasetHandle);

			$pageBodyHTML .=  "<h1>\n";
			$pageBodyHTML .=  $current_dataset->datasetCaption;
			if ($keyFieldValue)
			{
				$pageBodyHTML .=  " :: Editing a single record (".$current_dataset->recordCaption." no. ".$keyFieldValue.")\n";
			}
			else
			{
				$pageBodyHTML .=  " :: Adding a new ".$current_dataset->recordCaption."\n";
			}
			$pageBodyHTML .=  "</h1>\n\n";

			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));

			if(array_key_exists("d",$_GET))
			{
				$keyFieldValue = $_GET['d'][0]['v'];
			}
			else
			{
				$keyFieldValue = null;
			}
			$pageBodyHTML .=  $current_dataset->displayEditForm($keyFieldValue,"?display=updatesingle&datasetHandle=".$current_dataset->datasetHandle);
		break;

		//----------------------------------------------------------------------------------------------
		case "viewsingle":       //view Page

				$pageBodyHTML .=  "<h1>\n";
				$pageBodyHTML .=  $current_dataset->datasetCaption." :: ".$current_dataset->recordCaption." no. ".$keyFieldValue;
				$pageBodyHTML .=  "</h1>\n\n";
				$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));

			$pageBodyHTML .=  $current_dataset->displaySingleRecord($_GET['d'][0]['v']);
		break;



		//----------------------------------------------------------------------------------------------
		case "updatesingle" :      //Update if edit page is submitted

			$pageBodyHTML .=  "updating....\n";

							//p(print_r($_POST,1));
			if($current_dataset->updateFromEditForm_POST($keyFieldValue))
			{
				$report = "The selected database entry was successfully modified. <br>Your browser should automatically return to the main database page, <br>if not, click on the link below.";
				$pageBodyHTML .=  acradisp_javascriptRedirectTo("?display=seqlist&datasetHandle=".$_GET['datasetHandle']);
			}
			else
			{
				$redirect_header = "";
				echo "The database WASN'T updated. Please tell the database administrator.  <br>\n";
				die;
			}


		break;


		//----------------------------------------------------------------------------------------------
		case "deletesingleconfirm":     //Confirm Deletion!

			$pageBodyHTML .=  "<h1>\n";
			$pageBodyHTML .=  "Deleting ".$current_dataset->recordCaption." :: \n";
			$pageBodyHTML .=  $current_dataset->primaryKeyField["fieldname"]."=".$_GET['d']['0']['v'];
			$pageBodyHTML .=  "</h1>\n\n";
			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));
			$pageBodyHTML .=  $current_dataset->deleteSingleConfirm($_GET['d']['0']['v'],"?display=deletesingle","?display=seqList");
		break;





		//----------------------------------------------------------------------------------------------
		case "deletesingle":
			$pageBodyHTML .=  $current_dataset->deleteSingle($_GET['d'][0]['v'],$_GET['returnDestination']."&datasetHandle=".$_GET['d'][0]['dh']."&d[0][dh]=".$_GET['d'][0]['dh']."&d[0][df]=".$_GET['d'][0]['df']."&d[0][v]=".$_GET['d'][0]['v']);
		break;






		//----------------------------------------------------------------------------------------------
		case"showstylesheet":

			/*
			$pageBodyHTML .=  "<h1>\n";
			$pageBodyHTML .=  "Styles used by this dataset\n";
			$pageBodyHTML .=  "</h1>\n\n";
			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));
			*/


			/* //To export stylesheet as a file, put a single slash "/" just before the "/*" on this line. To view the stylesheet on-screen, delete that slash.
			echo $current_dataset->generateCSSstylesheet($saveAsFile=true);
			die();
			//-*/

			$pageBodyHTML .=  "<pre>\n";
			$pageBodyHTML .=  $current_dataset->generateCSSstylesheet($saveAsFile=false);
			$pageBodyHTML .=  "</pre>\n";

		break;




		//----------------------------------------------------------------------------------------------
		case"savestylesheet":

			/*
			$pageBodyHTML .=  "<h1>\n";
			$pageBodyHTML .=  "Styles used by this dataset\n";
			$pageBodyHTML .=  "</h1>\n\n";
			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));
			*/


			//* //To export stylesheet as a file, put a single slash "/" just before the "/*" on this line. To view the stylesheet on-screen, delete that slash.
			echo $current_dataset->generateCSSstylesheet($saveAsFile=true);
			die();
			//-*/

			$pageBodyHTML .=  "<pre>\n";
			$pageBodyHTML .=  $current_dataset->generateCSSstylesheet($saveAsFile=false);
			$pageBodyHTML .=  "</pre>\n";

		break;








		//----------------------------------------------------------------------------------------------
		case"exportstylesheet":

			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));
				$pageBodyHTML .=  "<link href=\"".URL_TO_DATASET_STYLESHEETS."/admin.css\" rel=\"stylesheet\" type=\"text/css\">\n";
				$pageBodyHTML .=  "<link href=\"".URL_TO_DATASET_STYLESHEETS."/default.css\" rel=\"stylesheet\" type=\"text/css\">\n";
			if ($current_dataset->writeCSSstylesheet())
			{
				$pageBodyHTML .=  "The stylesheet for the current dataset has been written to : ".$current_dataset->path_to_dataset_definition.$current_dataset->datasetHandle.".css\n";
			}
			else
			{
				$pageBodyHTML .=  "Sorry, I'm afraid I couldn't write the stylesheet to disk.\n";
			}
		break;





		//----------------------------------------------------------------------------------------------
		case "backupalltables":

			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));
				$pageBodyHTML .=  "<link href=\"".URL_TO_DATASET_STYLESHEETS."/admin.css\" rel=\"stylesheet\" type=\"text/css\">\n";
				$pageBodyHTML .=  "<link href=\"".URL_TO_DATASET_STYLESHEETS."/default.css\" rel=\"stylesheet\" type=\"text/css\">\n";
			if ($backupSuccessful=acradb_backup_all_tables($dataset))
			{
				//backup successful - no output needed, the function itself will create the download file.
				$pageBodyHTML .=  "You should see a download dialog to save the actual sql file. Save it somewhere safe on your local harddisk\n";

			}
			else
			{
				$pageBodyHTML .=  "Backup Failed!!!!\n";
			}
		break;



		//----------------------------------------------------------------------------------------------
		case "backupcurrentdataset":

			$pageBodyHTML .=  acradisp_menu($menuDefinition,array('index','list','grid','add','showStylesheet','saveStylesheet','backupCurrentDataset'));
				$pageBodyHTML .=  "<link href=\"".URL_TO_DATASET_STYLESHEETS."/admin.css\" rel=\"stylesheet\" type=\"text/css\">\n";
				$pageBodyHTML .=  "<link href=\"".URL_TO_DATASET_STYLESHEETS."/default.css\" rel=\"stylesheet\" type=\"text/css\">\n";
			if ($backupSuccessful=$current_dataset->backup())
			{
				//backup successful - no output needed, the function itself will create the download file.
				$pageBodyHTML .=  "You should see a download dialog to save the actual sql file. Save it somewhere safe on your local harddisk\n";

			}
			else
			{
				$pageBodyHTML .=  "Backup Failed!!!!\n";
			}
		break;



		//----------------------------------------------------------------------------------------------
		default:
			$pageBodyHTML .=  "No recognised display mode was selected\n";

		break;




	}












}

/*
$pageBodyHTML .=  $copyright_statement;


$pageBodyHTML .=  "</body>\n\n";
$pageBodyHTML .=  "</html>\n\n";
*/
$outputHTML .= acradisp_standardHTMLheader("Songbook admin",$pageHeaderStylesheets,$pageHeaderJavascripts,$localJavascriptStatements);

$outputHTML .= $pageBodyHTML;

$outputHTML .= $modalDialogHTML;

$outputHTML .= acradisp_standardHTMLfooter();


echo $outputHTML;

?>