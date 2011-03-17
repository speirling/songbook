<?php

class ST_database_interaction extends UnitTestCase {

    function test_acradb_link_to_local_MySQL() {
        $link = acradb_link_to_local_MySQL();
        $this->assertEqual(gettype($link), "resource");
    }

    //depends on the test database being available, and the test datasets being configured
    function test_acradb_get_query_result_array() {
        $expected = Array (
            0 => Array (  "id" => 1, "name" => "anto" ),
            1 => Array (  "id" => 2, "name" => "bill" ),
            2 => Array (  "id" => 3, "name" => "conor" ),
            3 => Array (  "id" => 4, "name" => "derek" )
        );
        $result = acradb_get_query_result_array("SELECT * FROM names", PRIMARY_DATABASE, true);
        $this->assertEqual($result, $expected);
    }

    function test_generate_insert_query_from_value_array() {
        $data[] = array(
            'value_array' => array(
                'user_type' => "'eventOrganiser'",
                'name' => "'test organiser'",
                'company' => "''",
                'postal_address' => "''",
                'phone' => "''",
                'primary_email' => "'organiser@organiser.com'",
                'additional_email' => "''",
                'mailinglist' => 0,
                'password' => "'646d7a4e1e255500706b79e3935b8f74'"
            ),
            'table' => 'users',
            'expected' => "INSERT INTO `users` ( `user_type` , `name` , `company` , `postal_address` , `phone` , `primary_email` , `additional_email` , `mailinglist` , `password` ) VALUES ('eventOrganiser' , 'test organiser' , '' , '' , '' , 'organiser@organiser.com' , '' , 0 , '646d7a4e1e255500706b79e3935b8f74' )"
         );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = acradb_generate_insert_query_from_value_array($data[$index]['value_array'], $data[$index]['table']);
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
       		   p($result,$data[$index]['expected']);
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }

    }

    function test_acradb_formatValueForMySQL() {
        $data[] = array(
        'currentFieldDefinition' => 'a:7:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"event_id";s:12:"labelVisible";b:1;s:5:"label";s:11:"Event no. :";s:9:"isVisible";b:1;s:10:"valueFixed";b:1;s:4:"type";s:7:"int(11)";}' ,
        'currentFieldValue' => '48' ,
        'expected' => 48
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:11:"description";s:12:"labelVisible";b:1;s:5:"label";s:13:"Description :";s:9:"isVisible";b:1;s:4:"type";s:16:"eventDescription";}' ,
        'currentFieldValue' => '<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>' ,
        'expected' => "'<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:7:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:7:"details";s:12:"labelVisible";b:1;s:5:"label";s:22:"Details of the event :";s:9:"isVisible";b:1;s:12:"editFormNote";s:99:"Wedding? Ceremony? Reception? Retirement party? Corporate function? Give as much detail as you can.";s:4:"type";s:4:"text";}' ,
        'currentFieldValue' => '40th' ,
        'expected' => "'40th'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:4:"date";s:12:"labelVisible";b:1;s:5:"label";s:6:"Date :";s:9:"isVisible";b:1;s:4:"type";s:4:"date";}' ,
        'currentFieldValue' => '1 January 1970' ,
        'expected' => "'1970-01-01'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:4:"time";s:12:"labelVisible";b:1;s:5:"label";s:30:"Time and Duration of the Event";s:9:"isVisible";b:1;s:4:"type";s:15:"timeAndDuration";}' ,
        'currentFieldValue' => '<timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration>' ,
        'expected' => "'<timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration>'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"venue_type";s:12:"labelVisible";b:1;s:5:"label";s:13:"Type of Venue";s:9:"isVisible";b:1;s:4:"type";s:9:"venueType";}' ,
        'currentFieldValue' => 'House' ,
        'expected' => "'House'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"venue_name";s:12:"labelVisible";b:1;s:5:"label";s:10:"Venue Name";s:9:"isVisible";b:1;s:4:"type";s:12:"varchar(255)";}' ,
        'currentFieldValue' => 'My Gaff' ,
        'expected' => "'My Gaff'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:7:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:8:"act_type";s:12:"labelVisible";b:1;s:5:"label";s:20:"Type of Act required";s:9:"isVisible";b:1;s:12:"editFormNote";s:133:"We have every kind of live music act from solo musicians to groups of two, four, six, and more musicians / singers. We also have DJs.";s:4:"type";s:4:"text";}' ,
        'currentFieldValue' => 'Small Acoustic Group' ,
        'expected' => "'Small Acoustic Group'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:7:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:6:"styles";s:12:"labelVisible";b:1;s:5:"label";s:25:"Styles of Music preferred";s:9:"isVisible";b:1;s:12:"editFormNote";s:198:"Please specify the kind of music you would like: Popular charts; Traditional Irish; Jazz; Blues; Soul, Country, etc. Our acts cover every imaginable kind from Rat Pack to Popular, Classical to Folk.";s:4:"type";s:15:"eventMusicStyle";}' ,
        'currentFieldValue' => '<eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle>' ,
        'expected' => "'<eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle>'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:7:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:10:"act_budget";s:12:"labelVisible";b:1;s:5:"label";s:16:"Preferred Budget";s:9:"isVisible";b:1;s:12:"editFormNote";s:50:"<b>Note</b>: please enter numbers only (no commas)";s:4:"type";s:13:"currency_euro";}' ,
        'currentFieldValue' => '200' ,
        'expected' => 200
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:11:"description";s:12:"labelVisible";b:1;s:5:"label";s:13:"Description :";s:9:"isVisible";b:1;s:4:"type";s:16:"eventDescription";}' ,
        'currentFieldValue' => '<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>' ,
        'expected' => "'<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:13:"venue_address";s:12:"labelVisible";b:1;s:5:"label";s:13:"Venue Address";s:9:"isVisible";b:1;s:4:"type";s:13:"postalAddress";}' ,
        'currentFieldValue' => '<postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress>' ,
        'expected' => "'<postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress>'"
        );
        $data[] = array(
        'currentFieldDefinition' => 'a:6:{s:9:"tablename";s:6:"events";s:9:"fieldname";s:16:"special_requests";s:12:"labelVisible";b:1;s:5:"label";s:16:"Special Requests";s:9:"isVisible";b:1;s:4:"type";s:4:"text";}' ,
        'currentFieldValue' => 'Get them up dancing' ,
        'expected' => "'Get them up dancing'"
        );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = acradb_formatValueForMySQL(unserialize($data[$index]['currentFieldDefinition']), $data[$index]['currentFieldValue']);
           if($result != $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
       		   p($result);
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }


    }
}
?>