<?php

class ST_edit_form extends UnitTestCase {
    function test_displayEditForm() {

        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:4:"date";s:12:"labelVisible";b:1;s:5:"label";s:17:"Date of the Event";s:9:"isVisible";b:1;s:4:"type";s:4:"date";}' ,
        'currentFieldValue' => '2011-01-31' ,
        'expected' => "&nbsp;<input name=\"Date__sp__of__sp__the__sp__Event\" id=\"Date__sp__of__sp__the__sp__Event\" type=\"text\" value=\"31 January 2011\" class=\"datepicker\" >"
        );
       /* $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:13:"venue_address";s:12:"labelVisible";b:1;s:5:"label";s:12:"Venue Region";s:9:"isVisible";b:1;s:4:"type";s:19:"postalAddressRegion";}' ,
        'currentFieldValue' => '' ,
        'expected' => ''
        );*/
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:11:"description";s:12:"labelVisible";b:1;s:5:"label";s:13:"Type of event";s:9:"isVisible";b:1;s:4:"type";s:12:"varchar(255)";}' ,
        'currentFieldValue' => 'Wedding Ceremony' ,
        'expected' => "<textarea name=\"Type__sp__of__sp__event\" id=\"Type_of_event\"  class=\"\" rows=\"10\" cols=\"37\">Wedding Ceremony</textarea>"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"act_budget";s:12:"labelVisible";b:1;s:5:"label";s:8:"Budget :";s:9:"isVisible";b:1;s:4:"type";s:13:"currency_euro";}' ,
        'currentFieldValue' => '1000' ,
        'expected' => "<span class=\"currency_symbol\">&euro;</span> &nbsp;<input name=\"Budget__sp____cln__\" id=\"Budget__sp____cln__\" type=\"text\" value=\"1000\" size=\"50\">"
        );
      /*  $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"venue_type";s:12:"labelVisible";b:1;s:5:"label";s:5:"Venue";s:9:"isVisible";b:1;s:4:"type";s:9:"venueType";}' ,
        'currentFieldValue' => '' ,
        'expected' => "			&nbsp<input name=\"Venue\" id=\"Venue\" type=\"text\" value=\"\" size=\"50\"  onChange=\"$(this).siblings('div.hints').hide();\"  onClick=\"$(this).siblings('div.hints').show();\"  >\n<div class='hints' style='display:none' onmouseover=\"$"."(this).hover(function(){$"."(this).css('display','block');},function(){$"."(this).css('display','none');});\">\n<div class=\"hint\" onClick=\"$(this).parent().parent().children('input#Venue').val($"."(this).html()).trigger('change');\">
\npub</div>\n<div class=\"hint\" onClick=\"$(this).parent().parent().children('input#Venue').val($(this).html()).trigger('change');\">
\nhotel</div>\n<div class=\"hint\" onClick=\"$(this).parent().parent().children('input#Venue').val($(this).html()).trigger('change');\">
\nconcert hall</div>\n<div class=\"hint\" onClick=\"$(this).parent().parent().children('input#Venue').val($(this).html()).trigger('change');\">
\ndance hall</div>\n<div class=\"hint\" onClick=\"$(this).parent().parent().children('input#Venue').val($(this).html()).trigger('change');\">
\nopen air square</div>\n<div class=\"hint\" onClick=\"$(this).parent().parent().children('input#Venue').val($(this).html()).trigger('change');\">
\nmarquee
\n</div>\n</div>\n"
        );*/
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"act_type";s:12:"labelVisible";b:1;s:5:"label";s:27:"Kind of Band or Act wanted:";s:9:"isVisible";b:1;s:4:"type";s:4:"text";}' ,
        'currentFieldValue' => '' ,
        'expected' => "<textarea name=\"Kind__sp__of__sp__Band__sp__or__sp__Act__sp__wanted__cln__\" id=\"Kind_of_Band_or_Act_wanted__c__\"  class=\"\" rows=\"10\" cols=\"37\"></textarea>"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:7:"details";s:12:"labelVisible";b:1;s:5:"label";s:21:"Details of the Event:";s:9:"isVisible";b:1;s:4:"type";s:4:"text";}' ,
        'currentFieldValue' => 'fgsfdg' ,
        'expected' => "<textarea name=\"Details__sp__of__sp__the__sp__Event__cln__\" id=\"Details_of_the_Event__c__\"  class=\"\" rows=\"10\" cols=\"37\">fgsfdg</textarea>"
        );
      /*  $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:6:"styles";s:12:"labelVisible";b:1;s:5:"label";s:22:"Styles of Music wanted";s:9:"isVisible";b:1;s:4:"type";s:15:"eventMusicStyle";}' ,
        'currentFieldValue' => '' ,
        'expected' => ""
        );*/
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:16:"special_requests";s:12:"labelVisible";b:1;s:5:"label";s:17:"Special Requests:";s:9:"isVisible";b:1;s:4:"type";s:4:"text";}' ,
        'currentFieldValue' => '' ,
        'expected' => "<textarea name=\"Special__sp__Requests__cln__\" id=\"Special_Requests__c__\"  class=\"\" rows=\"10\" cols=\"37\"></textarea>"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:7:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"event_id";s:12:"labelVisible";b:1;s:5:"label";s:13:"Event number:";s:9:"isVisible";b:1;s:10:"valueFixed";b:1;s:4:"type";s:7:"int(11)";}' ,
        'currentFieldValue' => '49' ,
        'expected' => "&nbsp;<input name=\"Event__sp__number__cln__\" id=\"Event__sp__number__cln__\" type=\"hidden\" value=\"49\">			<span class=\"fixedInput\" id=\"Event_number__c__\" >49</span>"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"received";s:12:"labelVisible";b:1;s:5:"label";s:12:"Date Posted:";s:9:"isVisible";b:1;s:4:"type";s:4:"date";}' ,
        'currentFieldValue' => '2011-01-31 17:00:49' ,
        'expected' => "&nbsp;<input name=\"Date__sp__Posted__cln__\" id=\"Date__sp__Posted__cln__\" type=\"text\" value=\"31 January 2011\" class=\"datepicker\" >"
        );

		for ($index = 0; $index < sizeof($data); $index = $index + 1) { //p($index,sizeof($data),$data[$index]);
           $result = acradb_createEditElement(unserialize($data[$index]['currentFieldDefinition']), $data[$index]['currentFieldValue']);
           if($result != $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }
    }
/*
    function test_updateFromEditForm() {
$data[] = array(
'dataset' => 'O:34:"acradb_tableRelationshipDefinition":42:{s:13:"datasetHandle";s:9:"event_ads";s:11:"description";s:189:"A cut down version of the Events table, using composite fields to simplify layout, intended to be displayed as a sequence of \'Float\'ed blocks to give the impression a \'classified ads\' page.";s:8:"database";s:19:"eofeasa_autocontact";s:14:"datasetCaption";s:14:"Available gigs";s:13:"recordCaption";s:3:"Gig";s:15:"primaryKeyField";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"event_id";s:12:"labelVisible";b:1;s:5:"label";s:13:"Event number:";s:9:"isVisible";b:1;s:10:"valueFixed";b:1;}s:15:"fieldDefinition";a:12:{s:11:"events.date";a:5:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:4:"date";s:12:"labelVisible";b:1;s:5:"label";s:17:"Date of the Event";s:9:"isVisible";b:1;}s:20:"events.venue_address";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:13:"venue_address";s:12:"labelVisible";b:1;s:5:"label";s:12:"Venue Region";s:9:"isVisible";b:1;s:4:"type";s:19:"postalAddressRegion";}s:18:"events.description";a:5:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:11:"description";s:12:"labelVisible";b:1;s:5:"label";s:13:"Type of event";s:9:"isVisible";b:1;}s:17:"events.act_budget";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"act_budget";s:12:"labelVisible";b:1;s:5:"label";s:8:"Budget :";s:9:"isVisible";b:1;s:4:"type";s:13:"currency_euro";}s:17:"events.venue_type";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"venue_type";s:12:"labelVisible";b:1;s:5:"label";s:5:"Venue";s:9:"isVisible";b:1;s:4:"type";s:9:"venueType";}s:15:"events.act_type";a:5:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"act_type";s:12:"labelVisible";b:1;s:5:"label";s:27:"Kind of Band or Act wanted:";s:9:"isVisible";b:1;}s:14:"events.details";a:5:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:7:"details";s:12:"labelVisible";b:1;s:5:"label";s:21:"Details of the Event:";s:9:"isVisible";b:1;}s:13:"events.styles";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:6:"styles";s:12:"labelVisible";b:1;s:5:"label";s:22:"Styles of Music wanted";s:9:"isVisible";b:1;s:4:"type";s:15:"eventMusicStyle";}s:23:"events.special_requests";a:5:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:16:"special_requests";s:12:"labelVisible";b:1;s:5:"label";s:17:"Special Requests:";s:9:"isVisible";b:1;}s:15:"events.event_id";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"event_id";s:12:"labelVisible";b:1;s:5:"label";s:13:"Event number:";s:9:"isVisible";b:1;s:10:"valueFixed";b:1;}s:15:"events.received";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"received";s:12:"labelVisible";b:1;s:5:"label";s:12:"Date Posted:";s:9:"isVisible";b:1;s:4:"type";s:4:"date";}s:38:"events.acradb_button_Quoteforthisevent";a:11:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:31:"acradb_button_Quoteforthisevent";s:12:"labelVisible";b:0;s:5:"label";s:31:"acradb_button_Quoteforthisevent";s:4:"type";s:13:"acradb_button";s:9:"isVisible";b:1;s:12:"defaultValue";s:20:"Quote for this event";s:10:"valueFixed";b:1;s:15:"defaultEnforced";b:1;s:6:"action";a:3:{s:10:"actionType";s:7:"onClick";s:10:"javascript";s:27:"location.href=\'quotefor.php";s:28:"dataForPassing_arrayOfFields";a:1:{i:0;a:3:{s:24:"destinationDatasetHandle";s:6:"events";s:21:"destinationFieldLabel";s:13:"Event number:";s:21:"valueSourceFieldLabel";s:13:"Event number:";}}}s:25:"viewsInWhichActionApplies";a:1:{i:0;s:14:"sequentialList";}}}s:24:"compositeFieldDefinition";a:0:{}s:20:"compositeFieldLabels";a:0:{}s:25:"calculatedFieldDefinition";a:0:{}s:21:"calculatedFieldLabels";a:0:{}s:18:"sortOrderFieldName";N;s:18:"sortOrderTableName";N;s:14:"sortOrderField";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"event_id";s:12:"labelVisible";b:1;s:5:"label";s:13:"Event number:";s:9:"isVisible";b:1;s:10:"valueFixed";b:1;}s:13:"sortDirection";s:4:"DESC";s:10:"tablenames";a:1:{i:0;s:6:"events";}s:6:"labels";a:12:{i:0;s:17:"Date of the Event";i:1;s:12:"Venue Region";i:2;s:13:"Type of event";i:3;s:8:"Budget :";i:4;s:5:"Venue";i:5;s:27:"Kind of Band or Act wanted:";i:6;s:21:"Details of the Event:";i:7;s:22:"Styles of Music wanted";i:8;s:17:"Special Requests:";i:9;s:13:"Event number:";i:10;s:12:"Date Posted:";i:11;s:31:"acradb_button_Quoteforthisevent";}s:14:"fieldsPerTable";a:1:{s:6:"events";a:12:{i:0;s:4:"date";i:1;s:13:"venue_address";i:2;s:11:"description";i:3;s:10:"act_budget";i:4;s:10:"venue_type";i:5;s:8:"act_type";i:6;s:7:"details";i:7;s:6:"styles";i:8;s:16:"special_requests";i:9;s:8:"event_id";i:10;s:8:"received";i:11;s:31:"acradb_button_Quoteforthisevent";}}s:15:"pivotTableArray";a:0:{}s:15:"pivotTableLabel";a:0:{}s:21:"resultArrayStructured";N;s:30:"resultArrayStructuredTotalSize";N;s:15:"resultArrayFlat";N;s:24:"resultArrayFlatTotalSize";N;s:33:"labelToDataSubarrayCrossreference";N;s:26:"fieldToLabelCrossReference";a:12:{s:17:"Date of the Event";s:11:"events.date";s:12:"Venue Region";s:20:"events.venue_address";s:13:"Type of event";s:18:"events.description";s:8:"Budget :";s:17:"events.act_budget";s:5:"Venue";s:17:"events.venue_type";s:27:"Kind of Band or Act wanted:";s:15:"events.act_type";s:21:"Details of the Event:";s:14:"events.details";s:22:"Styles of Music wanted";s:13:"events.styles";s:17:"Special Requests:";s:23:"events.special_requests";s:13:"Event number:";s:15:"events.event_id";s:12:"Date Posted:";s:15:"events.received";s:31:"acradb_button_Quoteforthisevent";s:38:"events.acradb_button_Quoteforthisevent";}s:9:"tableJoin";N;s:24:"joinsCrossReferenceArray";N;s:26:"path_to_dataset_definition";N;s:20:"use_admin_stylesheet";b:1;s:22:"use_default_stylesheet";b:1;s:11:"path_to_css";N;s:22:"fieldnamesActionsArray";N;s:27:"sortableGridHighlightColour";N;s:15:"definitionQuery";N;s:19:"whereANDClauseArray";N;s:18:"whereORClauseArray";N;s:16:"hierarchicalView";N;s:20:"CSS_totalFieldHeight";a:0:{}s:16:"formatConditions";a:0:{}s:17:"fullRecordActions";a:0:{}s:13:"display_order";a:12:{i:0;s:11:"events.date";i:1;s:20:"events.venue_address";i:2;s:18:"events.description";i:3;s:17:"events.act_budget";i:4;s:17:"events.venue_type";i:5;s:15:"events.act_type";i:6;s:14:"events.details";i:7;s:13:"events.styles";i:8;s:23:"events.special_requests";i:9;s:15:"events.event_id";i:10;s:15:"events.received";i:11;s:38:"events.acradb_button_Quoteforthisevent";}}' ,
'arrayOfValuesIndexedByLabel' => Array
(
    'Date__sp__of__sp__the__sp__Event' => "31 January 2011",
    'Venue__sp__Region' => "",
    'Type__sp__of__sp__event' => "Wedding Ceremony",
    'Budget__sp____cln__' => 1000,
    'Venue' => "",
    'Kind__sp__of__sp__Band__sp__or__sp__Act__sp__wanted__cln__' => "",
    'Details__sp__of__sp__the__sp__Event__cln__' => "fgsfdg",
    'Styles__sp__of__sp__Music__sp__wanted' => "",
    'Special__sp__Requests__cln__' => "",
    'Event__sp__number__cln__' => "49",
    'Date__sp__Posted__cln__' => "31 January 2011",
    'acradb_button_Quoteforthisevent' => "Quote for this event"
) ,
'keyFieldValue' => '49' ,
'expected' => "UPDATE `events` SET `date`  = '2011-01-31' , `venue_address`  = '' , `description`  = 'Wedding Ceremony' , `act_budget`  = 1000 , `venue_type`  = '' , `act_type`  = '' , `details`  = 'fgsfdg' , `styles`  = '' , `special_requests`  = '' , `received`  = '2011-01-31' , `acradb_button_Quoteforthisevent`  = 'Quote for this event'  WHERE `event_id`  = '49'"
);

    for ($index = 0; $index < sizeof($data); $index = $index + 1) { //p($index, sizeof($data),unserialize($data[$index]['arrayOfValuesIndexedByLabel']));
           $result = unserialize($data[$index]['dataset'])->updateFromEditForm($data[$index]['arrayOfValuesIndexedByLabel'], $data[$index]['keyFieldValue'], $testOnly = true);
           if($result != $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }
    }
/*
    function test_acradb_update_single_database_record() {
$data[] = array(
'databaseName' => 'eofeasa_autocontact' ,
'table' => 'events' ,
'KeyFieldname' => 'event_id' ,
'value_array' => Array
(
    'date' => '2011-01-31',
    'venue_address' => '',
    'description' => 'Wedding Ceremony',
    'act_budget' => 1000,
    'venue_type' => '',
    'act_type' => '',
    'details' => 'fgsfdg',
    'styles' => '',
    'special_requests' => '',
    'event_id' => 49,
    'received' => '2011-01-31',
    //'acradb_button_Quoteforthisevent' => Quote for this event
) ,
'filterQuery' => 'b:1;' ,
'testOnly' => 'b:0;' ,
'expected' => ''
);

    for ($index = 0; $index < sizeof($data); $index = $index + 1) { p($index, sizeof($data),unserialize($data[$index]['value_array']));
           $result = acradb_update_single_database_record($data[$index]['databaseName'], $data[$index]['table'], $data[$index]['KeyFieldname'], unserialize($data[$index]['value_array']), $data[$index]['filterQuery'], $testOnly = true);
           if($result != $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }
    }
*/

   function test_generate_new_record_value_array_without_keyField() {
       global $dataset;
       $data[] = array(
        'fieldDefinition' => 'a:10:{s:13:"users.user_id";a:7:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:7:"user_id";s:12:"labelVisible";b:0;s:5:"label";s:7:"user_id";s:9:"isVisible";b:1;s:10:"valueFixed";b:1;s:4:"type";s:7:"int(11)";}s:15:"users.user_type";a:6:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:9:"user_type";s:12:"labelVisible";b:1;s:5:"label";s:12:"Type of user";s:9:"isVisible";b:1;s:4:"type";s:35:"enum(\'eventOrganiser\',\'actContact\')";}s:10:"users.name";a:7:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:4:"name";s:12:"labelVisible";b:1;s:5:"label";s:9:"Full Name";s:9:"isVisible";b:1;s:21:"formValidationClasses";s:8:"required";s:4:"type";s:12:"varchar(255)";}s:13:"users.company";a:6:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:7:"company";s:12:"labelVisible";b:1;s:5:"label";s:23:"Company (if applicable)";s:9:"isVisible";b:1;s:4:"type";s:12:"varchar(255)";}s:20:"users.postal_address";a:6:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:14:"postal_address";s:12:"labelVisible";b:1;s:5:"label";s:14:"Postal Address";s:9:"isVisible";b:1;s:4:"type";s:13:"postalAddress";}s:11:"users.phone";a:6:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:5:"phone";s:12:"labelVisible";b:1;s:5:"label";s:13:"Phone numbers";s:9:"isVisible";b:1;s:4:"type";s:10:"stringList";}s:19:"users.primary_email";a:7:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:13:"primary_email";s:12:"labelVisible";b:1;s:5:"label";s:19:"Login Email Address";s:9:"isVisible";b:1;s:21:"formValidationClasses";s:14:"required email";s:4:"type";s:12:"varchar(255)";}s:22:"users.additional_email";a:6:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:16:"additional_email";s:12:"labelVisible";b:1;s:5:"label";s:26:"Additional Email addresses";s:9:"isVisible";b:1;s:4:"type";s:10:"stringList";}s:17:"users.mailinglist";a:6:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:11:"mailinglist";s:12:"labelVisible";b:1;s:5:"label";s:22:"Opt in to mailing list";s:9:"isVisible";b:1;s:4:"type";s:7:"boolean";}s:14:"users.password";a:6:{s:9:"tablename";s:5:"users";s:9:"fieldname";s:8:"password";s:12:"labelVisible";b:1;s:5:"label";s:8:"Password";s:9:"isVisible";b:1;s:4:"type";s:8:"password";}}',
        'primaryKeyField_tablename' => 'users',
        'primaryKeyField_fieldname' => 'user_id',
        'joinsCrossReferenceArray' => null,
        'arrayOfValuesIndexedByLabel' => array (
            'user_id' => '',
            'Type__sp__of__sp__user' => 'eventOrganiser',
            'Full__sp__Name' => 'test organiser',
            'Company__sp__(if__sp__applicable)' => '',
            'Postal__sp__Address' => '',
            'Phone__sp__numbers' => '',
            'Login__sp__Email__sp__Address' => 'organiser@organiser.com',
            'Additional__sp__Email__sp__addresses' => '',
            'Opt__sp__in__sp__to__sp__mailing__sp__list' => '',
            '__PASSWORDIGNORE__1' => 'organiser',
            '__PASSWORDIGNORE__2' => 'organiser',
            'Password' => 'organiser'
          ),
        'expected' => array(
                'user_type' => "'eventOrganiser'",
                'name' => "'test organiser'",
                'company' => "''",
                'postal_address' => "''",
                'phone' => "''",
                'primary_email' => "'organiser@organiser.com'",
                'additional_email' => "''",
                'mailinglist' => 0,
                'password' => "'646d7a4e1e255500706b79e3935b8f74'"
            )
        );

//String fields must have quotes included here because the contents of this array are put verbatim into the query.
//Dates must also have quotes.

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = acradb_generate_new_record_value_array_without_keyField(unserialize($data[$index]['fieldDefinition']), $data[$index]['arrayOfValuesIndexedByLabel'], $data[$index]['primaryKeyField_tablename'], $data[$index]['primaryKeyField_fieldname'], $data[$index]['joinsCrossReferenceArray']);
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }
   }


   function test_acradb_generate_new_record_value_array_from_arrayOfValuesIndexedByLabel() {
       global $dataset;
       $data[] = array(
            'fieldDefinition_array' => 'a:12:{s:11:"events.date";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:4:"date";s:12:"labelVisible";b:1;s:5:"label";s:17:"Date of the Event";s:9:"isVisible";b:1;s:4:"type";s:4:"date";}s:20:"events.venue_address";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:13:"venue_address";s:12:"labelVisible";b:1;s:5:"label";s:12:"Venue Region";s:9:"isVisible";b:1;s:4:"type";s:19:"postalAddressRegion";}s:18:"events.description";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:11:"description";s:12:"labelVisible";b:1;s:5:"label";s:13:"Type of event";s:9:"isVisible";b:1;s:4:"type";s:12:"varchar(255)";}s:17:"events.act_budget";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"act_budget";s:12:"labelVisible";b:1;s:5:"label";s:8:"Budget :";s:9:"isVisible";b:1;s:4:"type";s:13:"currency_euro";}s:17:"events.venue_type";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"venue_type";s:12:"labelVisible";b:1;s:5:"label";s:5:"Venue";s:9:"isVisible";b:1;s:4:"type";s:9:"venueType";}s:15:"events.act_type";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"act_type";s:12:"labelVisible";b:1;s:5:"label";s:27:"Kind of Band or Act wanted:";s:9:"isVisible";b:1;s:4:"type";s:4:"text";}s:14:"events.details";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:7:"details";s:12:"labelVisible";b:1;s:5:"label";s:21:"Details of the Event:";s:9:"isVisible";b:1;s:4:"type";s:4:"text";}s:13:"events.styles";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:6:"styles";s:12:"labelVisible";b:1;s:5:"label";s:22:"Styles of Music wanted";s:9:"isVisible";b:1;s:4:"type";s:15:"eventMusicStyle";}s:23:"events.special_requests";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:16:"special_requests";s:12:"labelVisible";b:1;s:5:"label";s:17:"Special Requests:";s:9:"isVisible";b:1;s:4:"type";s:4:"text";}s:15:"events.event_id";a:7:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"event_id";s:12:"labelVisible";b:1;s:5:"label";s:13:"Event number:";s:9:"isVisible";b:1;s:10:"valueFixed";b:1;s:4:"type";s:7:"int(11)";}s:15:"events.received";a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"received";s:12:"labelVisible";b:1;s:5:"label";s:12:"Date Posted:";s:9:"isVisible";b:1;s:4:"type";s:4:"date";}s:38:"events.acradb_button_Quoteforthisevent";a:11:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:31:"acradb_button_Quoteforthisevent";s:12:"labelVisible";b:0;s:5:"label";s:31:"acradb_button_Quoteforthisevent";s:4:"type";s:13:"acradb_button";s:9:"isVisible";b:1;s:12:"defaultValue";s:20:"Quote for this event";s:10:"valueFixed";b:1;s:15:"defaultEnforced";b:1;s:6:"action";a:3:{s:10:"actionType";s:7:"onClick";s:10:"javascript";s:27:"location.href=\'quotefor.php";s:28:"dataForPassing_arrayOfFields";a:1:{i:0;a:3:{s:24:"destinationDatasetHandle";s:6:"events";s:21:"destinationFieldLabel";s:13:"Event number:";s:21:"valueSourceFieldLabel";s:13:"Event number:";}}}s:25:"viewsInWhichActionApplies";a:1:{i:0;s:14:"sequentialList";}}}' ,
            'arrayOfValuesIndexedByLabel' => 'a:12:{s:32:"Date__sp__of__sp__the__sp__Event";s:15:"31 January 2011";s:17:"Venue__sp__Region";s:0:"";s:23:"Type__sp__of__sp__event";s:16:"Wedding Ceremony";s:19:"Budget__sp____cln__";s:4:"1000";s:5:"Venue";s:0:"";s:58:"Kind__sp__of__sp__Band__sp__or__sp__Act__sp__wanted__cln__";s:0:"";s:42:"Details__sp__of__sp__the__sp__Event__cln__";s:6:"fgsfdg";s:37:"Styles__sp__of__sp__Music__sp__wanted";s:0:"";s:28:"Special__sp__Requests__cln__";s:0:"";s:24:"Event__sp__number__cln__";s:2:"49";s:23:"Date__sp__Posted__cln__";s:15:"31 January 2011";s:31:"acradb_button_Quoteforthisevent";s:20:"Quote for this event";}' ,
            'fieldToLabelCrossReference_array' => 'a:12:{s:17:"Date of the Event";s:11:"events.date";s:12:"Venue Region";s:20:"events.venue_address";s:13:"Type of event";s:18:"events.description";s:8:"Budget :";s:17:"events.act_budget";s:5:"Venue";s:17:"events.venue_type";s:27:"Kind of Band or Act wanted:";s:15:"events.act_type";s:21:"Details of the Event:";s:14:"events.details";s:22:"Styles of Music wanted";s:13:"events.styles";s:17:"Special Requests:";s:23:"events.special_requests";s:13:"Event number:";s:15:"events.event_id";s:12:"Date Posted:";s:15:"events.received";s:31:"acradb_button_Quoteforthisevent";s:38:"events.acradb_button_Quoteforthisevent";}' ,
            'primaryKeyField_fieldname' => 'event_id' ,
            'expected' => Array(
                'date' => "'2011-01-31'",
                'venue_address' => "''",
                'description' => "'Wedding Ceremony'",
                'act_budget' => "1000",
                'venue_type' => "''",
                'act_type' => "''",
                'details' => "'fgsfdg'",
                'styles' => "''",
                'special_requests' => "''",
                'event_id' => "49",
                'received' => "'2011-01-31'"
            )
       );

//String fields must have quotes included here because the contents of this array are put verbatim into the query.
//Dates must also have quotes.
       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $expected_result = $data[$index]['expected'];
           $result = acradb_generate_new_record_value_array_from_arrayOfValuesIndexedByLabel(
               unserialize($data[$index]['fieldDefinition_array']),
               unserialize($data[$index]['arrayOfValuesIndexedByLabel']),
               unserialize($data[$index]['fieldToLabelCrossReference_array']),
               $data[$index]['primaryKeyField_fieldname']);
           if($result !== $expected_result) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $expected_result);
       		   p(acradisp_array_definition($result));
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }
   }
}
?>