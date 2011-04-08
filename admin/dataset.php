<?php
$dataset["song_lyrics"] = new acradb_tableRelationshipDefinition;
//..........................................................

	$dataset["song_lyrics"]->datasetHandle = "Song Lyrics"; //this is used for file outputs, such as dataset-specific CSS stylesheet, backupSQL etc.
	$dataset["song_lyrics"]->description = "All AvailableSong Lyrics";

	$dataset["song_lyrics"]->database = PRIMARY_DATABASE;

	$dataset["song_lyrics"]->datasetCaption = "Songs";
	$dataset["song_lyrics"]->recordCaption = "song";


	//the set of field definitions must include the primary key
	//NOTE: labels MUST BE UNIQUE
	//--//defineField($fieldspec,$label=false)
	$dataset["song_lyrics"]->defineField("lyrics.id","Song no. ");
	$dataset["song_lyrics"]->defineField("lyrics.title","Title");
	$dataset["song_lyrics"]->defineField("lyrics.written_by","Written by:");
	$dataset["song_lyrics"]->defineField("lyrics.performed_by","Performed by:");
	$dataset["song_lyrics"]->defineField("lyrics.content","Lyrics (&chords)");
	$dataset["song_lyrics"]->defineField("lyrics.original_filename","Original File name");
	$dataset["song_lyrics"]->defineField("lyrics.meta_tags","Search/Sort tags");
	//set which one of the above is the primary key
	//--//setPrimaryKeyField($fieldspec)
	$dataset["song_lyrics"]->setPrimaryKeyField("lyrics.id");


	//hide any fields (e.g. the primary key) that are not to appear in tables, forms or lists
	//--//hideField($tablename,$fieldname)
	//$dataset["song_lyrics"]->hideField("lyrics.act_id");

	//Set the default values for any fields that can not be manually edited...
	//e.g. the server http_auth username is always to be entered in a field
	//or the current date
	//--//->setFieldValue($tablename,$fieldname,$value);

	//define the joins between the different tables in the field list
	//--//defineJoin($joinStatement,joinType) where $joinStatement is "table1.field1 = table2.field2" and $joinType is "INNER", "LEFT", "LEFT OUTER", "RIGHT", "RIGHT OUTER", or "CROSS". If omitted,$joinType is assumed to be "INNER"


		//$dataset["song_lyrics"]->hideField("lyrics.priceRange");
		//$dataset["song_lyrics"]->hideField("priceRanges.id");


	/*Some fields in some tables need to be treated in ways that can't be derived
	from their definitions in MySQL.
	Those field types need to be specified here:
	*/
	//--//->overrideFieldType($fieldspec,$typeName)

?>