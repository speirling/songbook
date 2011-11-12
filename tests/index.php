<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('simpletest/autorun.php');
require_once('../admin/configure.inc.php');
echo "
<style type='text/css'>
body {
	font-family:arial;
	font-size:12px;
}
pre {
	font-size: 0.9em;
}
del {
	display: inline-block;
    background-color:#ffaaaa;
    color:#990000;
}

ins {
	display: inline-block;
    background-color:#aaffaa;
    color:#009900;
}
h2.test-fail {
	font-size:1em;
	background-color:#ffaaaa;
	margin:0;
	padding:0.5em;
}
</style>
";
class songbook_tests extends UnitTestCase {
   function test_first() {


    $resultArray = Array (
    "0" => Array (
        "__dataFromHigherUpInTheHierarchy__" => Array (
            "Quotes for this event" => 48,
        ),
        "Event Date" => "2010-07-10",
        "Event Type" => "<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>",
        "Details of the event :" => "40th",
        "Time and Duration of the Event" => "<timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration>",
        "Venue" => "My Gaff",
        "Venue Address" => "<postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress>",
        "Type of Act required" => "Small Acoustic Group",
        "Styles of Music preferred" => "<eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle>",
        "Special Requests" => "Get them up dancing",
        "Preferred Budget" => 200,
        "acradb_button_EditthisEvent" => "Edit this Event",
        "acradb_button_ContacttheselectedActs(throughGoodgigs)" => "Contact the selected Acts (through Goodgigs)",
        "Quotes for this event" => Array (
            "0" => Array (
                "0" => Array (
                    "Quote number: " => 41,
                    "Date Quote Entered" => "2010-06-04 20:30:22",
                    "Event for which the quote is made" => 48,
                    "__dataFromHigherUpInTheHierarchy__" => Array (
                        "Act making the Quote" => 37,
                    ),
                    "Quote value" => 231,
                    "Deposit" => 30,
                    "selected" => 1,
                    "acradb_button_Selectthisquote" => "Select this quote",
                    "acradb_button_De-selectthisquote" => "De-select this quote",
                    "Act making the Quote" => Array (
                        "0" => Array (
                            "0" => Array (
                                "Band No." => 37,
                                "locality" => "Meath",
                            )
                        )
                    )
                ),
                "1" => Array (
                    "Quote number: " => 42,
                    "Date Quote Entered" => "2010-06-04 20:31:06",
                    "Event for which the quote is made" => 48,
                    "__dataFromHigherUpInTheHierarchy__" => Array (
                        "Act making the Quote" => 21,
                    ),
                    "Quote value" => 294,
                    "Deposit" => 50,
                    "selected" => 0,
                    "acradb_button_Selectthisquote" => "Select this quote",
                    "acradb_button_De-selectthisquote" => "De-select this quote",
                    "Act making the Quote" => Array (
                        "0" => Array (
                            "0" => Array (
                                "Band No." => 21,
                                "locality" => "DublinMeathClare",
                            )
                        )
                    )
                ),
                "2" => Array (
                    "Quote number: " => 43,
                    "Date Quote Entered" => "2010-10-15 15:30:48",
                    "Event for which the quote is made" => 48,
                    "__dataFromHigherUpInTheHierarchy__" => Array (
                        "Act making the Quote" => 21,
                    ),
                    "Quote value" => 1050,
                    "Deposit" => 100,
                    "selected" => 0,
                    "acradb_button_Selectthisquote" => "Select this quote",
                    "acradb_button_De-selectthisquote" => "De-select this quote",
                    "Act making the Quote" => Array (
                        "0" => Array (
                            "0" => Array (
                                "Band No." => 21,
                                "locality" => "DublinMeathClare",
                            )
                        )
                    )
                ),
                "3" => Array (
                    "Quote number: " => 44,
                    "Date Quote Entered" => "2010-10-15 15:31:02",
                    "Event for which the quote is made" => 48,
                    "__dataFromHigherUpInTheHierarchy__" => Array (
                        "Act making the Quote" => 22,
                    ),
                    "Quote value" => 1155,
                    "Deposit" => 110,
                    "selected" => 0,
                    "acradb_button_Selectthisquote" => "Select this quote",
                    "acradb_button_De-selectthisquote" => "De-select this quote",
                    "Act making the Quote" => Array (
                        "0" => Array (
                            "0" => Array (
                                "Band No." => 22,
                                "locality" => ""
                            )
                        )
                    )
                )
            )
        )
    )
);






    $dataset_quoteForEvent->add_types_to_fieldDefinitions();
    $dataset_quoteForEvent->refreshData();
    //p($dataset_quoteForEvent->display_order);
    //$dataset_quoteForEvent->hierarchicalView = $dataset_quoteForEvent->createSubDatasetArray();
    //p($dataset_quoteForEvent->hierarchicalView);
        $data[] = Array(
        'dataset' => $dataset_quoteForEvent,
        'resultArray' => $resultArray,
        'expected' => '<div id="dataset_quoteForEvent_primary" class="acradb_reportHolder" start_record="" records_per_page="" total_records=""><div id="record_quoteForEvent_primary_48" class="acradb_recordHolder" ><div id="events_date"><div id="label" class="label">Event Date</div><div id="data" class="date">10/07/2010</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_description"><div id="label" class="label">Event Type</div><div id="data" class="varchar"><eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription></div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_details"><div id="label" class="label">Details of the event :</div><div id="data" class="text">40th</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_time"><div id="label" class="label">Time and Duration of the Event</div><div id="data" class="timeAndDuration"><div id="Time_and_Duration_of_the_Event_display" style=""><p>from 20:00 until 22:00</p></div></div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_venue_name"><div id="label" class="label">Venue</div><div id="data" class="varchar">My Gaff</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_venue_address"><div id="label" class="label">Venue Address</div><div id="data" class="postalAddress"><div id="Venue_Address_display" style=""><p> 76 Balreask Manor,<br/></p><p> Navan</p><p>Meath</p></div></div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_act_type"><div id="label" class="label">Type of Act required</div><div id="data" class="text">Small Acoustic Group</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_styles"><div id="label" class="label">Styles of Music preferred</div><div id="data" class="eventMusicStyle"><div id="Styles_of_Music_preferred_display" style="">Folk/Ballads</div></div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_special_requests"><div id="label" class="label">Special Requests</div><div id="data" class="text">Get them up dancing</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_act_budget"><div id="label" class="label">Preferred Budget</div><div id="data" class="currency_euro">&euro; 200</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_event_id"><div id="label" class="label">Quotes for this event</div><div id="data" class="data"><div id="dataset_quoteForEvent_quotes_primary" class="acradb_reportHolder" start_record="" records_per_page="" total_records=""><div id="record_quoteForEvent_quotes_primary_41" class="acradb_recordHolder selected" ><div id="quotes_quote_id"><div id="label" class="label">Quote number: </div><div id="data" class="tinyint">41</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_quote"><div id="label" class="label">Quote value</div><div id="data" class="currency_euro">&euro; 231</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_deposit"><div id="label" class="label">Deposit</div><div id="data" class="currency_euro">&euro; 30</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_act_id"><div id="label" class="label">Act making the Quote</div><div id="data" class="data"><div id="dataset_quoteForEvent_quotes_acts_primary" class="acradb_reportHolder" start_record="" records_per_page="" total_records=""><div id="record_quoteForEvent_quotes_acts_primary_37" class="acradb_recordHolder" ><div id="acts_act_id"><div id="label" class="label">Band No.</div><div id="data" class="int" onclick="location.href=\'singleAct.php?d[0][dh]=actList&d[0][df]=act_id&d[0][v]=\';" style=\'cursor:pointer\' >37</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div style="clear:both;" class="clear"></div></div></div> </div> <div id="quotes_acradb_button_De-selectthisquote"><div id="data" class="acradb_button" onClick="location.href=\'deselectQuote.php?d[0][dh]=quotes&d[0][df]=quote_id&d[0][v]=41\';" style=\'cursor:pointer\' >De-select this quote</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div id="record_quoteForEvent_quotes_primary_42" class="acradb_recordHolder unselected" ><div id="quotes_quote_id"><div id="label" class="label">Quote number: </div><div id="data" class="tinyint">42</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_quote"><div id="label" class="label">Quote value</div><div id="data" class="currency_euro">&euro; 294</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_deposit"><div id="label" class="label">Deposit</div><div id="data" class="currency_euro">&euro; 50</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_act_id"><div id="label" class="label">Act making the Quote</div><div id="data" class="data"><div id="dataset_quoteForEvent_quotes_acts_primary" class="acradb_reportHolder" start_record="" records_per_page="" total_records=""><div id="record_quoteForEvent_quotes_acts_primary_21" class="acradb_recordHolder" ><div id="acts_act_id"><div id="label" class="label">Band No.</div><div id="data" class="int" onclick="location.href=\'singleAct.php?d[0][dh]=actList&d[0][df]=act_id&d[0][v]=\';" style=\'cursor:pointer\' >21</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div style="clear:both;" class="clear"></div></div></div> </div> <div id="quotes_acradb_button_Selectthisquote"><div id="data" class="acradb_button" onClick="location.href=\'selectQuote.php?d[0][dh]=quotes&d[0][df]=quote_id&d[0][v]=42\';" style=\'cursor:pointer\' >Select this quote</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div id="record_quoteForEvent_quotes_primary_43" class="acradb_recordHolder unselected" ><div id="quotes_quote_id"><div id="label" class="label">Quote number: </div><div id="data" class="tinyint">43</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_quote"><div id="label" class="label">Quote value</div><div id="data" class="currency_euro">&euro; 1050</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_deposit"><div id="label" class="label">Deposit</div><div id="data" class="currency_euro">&euro; 100</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_act_id"><div id="label" class="label">Act making the Quote</div><div id="data" class="data"><div id="dataset_quoteForEvent_quotes_acts_primary" class="acradb_reportHolder" start_record="" records_per_page="" total_records=""><div id="record_quoteForEvent_quotes_acts_primary_21" class="acradb_recordHolder" ><div id="acts_act_id"><div id="label" class="label">Band No.</div><div id="data" class="int" onclick="location.href=\'singleAct.php?d[0][dh]=actList&d[0][df]=act_id&d[0][v]=\';" style=\'cursor:pointer\' >21</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div style="clear:both;" class="clear"></div></div></div> </div> <div id="quotes_acradb_button_Selectthisquote"><div id="data" class="acradb_button" onClick="location.href=\'selectQuote.php?d[0][dh]=quotes&d[0][df]=quote_id&d[0][v]=43\';" style=\'cursor:pointer\' >Select this quote</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div id="record_quoteForEvent_quotes_primary_44" class="acradb_recordHolder unselected" ><div id="quotes_quote_id"><div id="label" class="label">Quote number: </div><div id="data" class="tinyint">44</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_quote"><div id="label" class="label">Quote value</div><div id="data" class="currency_euro">&euro; 1155</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_deposit"><div id="label" class="label">Deposit</div><div id="data" class="currency_euro">&euro; 110</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="quotes_act_id"><div id="label" class="label">Act making the Quote</div><div id="data" class="data"><div id="dataset_quoteForEvent_quotes_acts_primary" class="acradb_reportHolder" start_record="" records_per_page="" total_records=""><div id="record_quoteForEvent_quotes_acts_primary_22" class="acradb_recordHolder" ><div id="acts_act_id"><div id="label" class="label">Band No.</div><div id="data" class="int" onclick="location.href=\'singleAct.php?d[0][dh]=actList&d[0][df]=act_id&d[0][v]=\';" style=\'cursor:pointer\' >22</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div style="clear:both;" class="clear"></div></div></div> </div> <div id="quotes_acradb_button_Selectthisquote"><div id="data" class="acradb_button" onClick="location.href=\'selectQuote.php?d[0][dh]=quotes&d[0][df]=quote_id&d[0][v]=44\';" style=\'cursor:pointer\' >Select this quote</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div style="clear:both;" class="clear"></div></div></div> </div> <div id="events_acradb_button_EditthisEvent"><div id="data" class="acradb_button" onClick="location.href=\'edit.php?d[0][dh]=events&d[0][df]=Event no. :&d[0][v]=48\';" style=\'cursor:pointer\' >Edit this Event</div><div style=\'clear:both;\' class=\'clear\'></div></div> <div id="events_acradb_button_ContacttheselectedActs(throughGoodgigs)"><div id="data" class="acradb_button" onClick="location.href=\'contactGoodgigs_quotesForEvent.php?d[0][dh]=events&d[0][df]=Event no. :&d[0][v]=48\';" style=\'cursor:pointer\' >Contact the selected Acts (through Goodgigs)</div><div style=\'clear:both;\' class=\'clear\'></div></div> <span class="clear"></span> </div> <div style="clear:both;" class="clear"></div></div>'
        );
        //p($dataset_quoteForEvent->display_order, unserialize($data[0]['resultArray']));

        for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = acradb_reportHTML_fromData($data[$index]['resultArray'], $data[$index]['dataset'], $noOfRecordsPerPage = null, $startRecord = null, $contextPath = null);
           //p(unserialize($data[$index]['resultArray']));
         if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result,$data[$index]['expected']);
               p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
        }

   }

   function test_contactGoodGigs_reQuote() {
       $data[] = array(
           'event_data_serialised' => 'a:1:{i:0;a:15:{s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:21:"Quotes for this event";s:2:"48";}s:10:"Event Date";s:10:"2010-07-10";s:10:"Event Type";s:92:"<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>";s:22:"Details of the event :";s:4:"40th";s:30:"Time and Duration of the Event";s:139:"<timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration>";s:5:"Venue";s:7:"My Gaff";s:13:"Venue Address";s:183:"<postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress>";s:20:"Type of Act required";s:20:"Small Acoustic Group";s:25:"Styles of Music preferred";s:88:"<eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle>";s:16:"Special Requests";s:19:"Get them up dancing";s:16:"Preferred Budget";s:3:"200";s:14:"Accepted Quote";s:1:"0";s:27:"acradb_button_EditthisEvent";s:15:"Edit this Event";s:53:"acradb_button_ContacttheselectedActs(throughGoodgigs)";s:44:"Contact the selected Acts (through Goodgigs)";s:21:"Quotes for this event";a:1:{i:0;a:4:{i:0;a:10:{s:14:"Quote number: ";s:2:"41";s:18:"Date Quote Entered";s:19:"2010-06-04 20:30:22";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"37";}s:11:"Quote value";s:3:"231";s:7:"Deposit";s:2:"30";s:8:"selected";s:1:"0";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"37";s:8:"locality";s:52:"<region><county><item>Meath</item></county></region>";}}}}i:1;a:10:{s:14:"Quote number: ";s:2:"42";s:18:"Date Quote Entered";s:19:"2010-06-04 20:31:06";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"21";}s:11:"Quote value";s:3:"294";s:7:"Deposit";s:2:"50";s:8:"selected";s:1:"0";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"21";s:8:"locality";s:89:"<region><county><item>Dublin</item><item>Meath</item><item>Clare</item></county></region>";}}}}i:2;a:10:{s:14:"Quote number: ";s:2:"43";s:18:"Date Quote Entered";s:19:"2010-10-15 15:30:48";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"21";}s:11:"Quote value";s:4:"1050";s:7:"Deposit";s:3:"100";s:8:"selected";s:1:"1";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"21";s:8:"locality";s:89:"<region><county><item>Dublin</item><item>Meath</item><item>Clare</item></county></region>";}}}}i:3;a:10:{s:14:"Quote number: ";s:2:"44";s:18:"Date Quote Entered";s:19:"2010-10-15 15:31:02";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"22";}s:11:"Quote value";s:4:"1155";s:7:"Deposit";s:3:"110";s:8:"selected";s:1:"1";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"22";s:8:"locality";s:0:"";}}}}}}}}',
           'expected' => "=== Event No. == 48 =========\n[Event Date] 2010-07-10<br />[Event Type] <eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription><br />[Details of the event :] 40th<br />[Time and Duration of the Event] <timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration><br />[Venue] My Gaff<br />[Venue Address] <postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress><br />[Type of Act required] Small Acoustic Group<br />[Styles of Music preferred] <eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle><br />[Special Requests] Get them up dancing<br />[Preferred Budget] 200<br />[Accepted Quote] 0<br />\nQuotes that are of interest: \nQuote no. [43] from Act [21] : value [1050] (deposit [100]) \nQuote no. [44] from Act [22] : value [1155] (deposit [110]) \n==============================="
       );
       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = bandchoice_formatForEmail_eventQuotes(unserialize($data[$index]['event_data_serialised']));
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result,$data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }
   }

   function test_bandchoice_formatForEmail_eventQuotes() {
       $data[] = array(
           'event_data_serialised' => 'a:1:{i:0;a:15:{s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:21:"Quotes for this event";s:2:"48";}s:10:"Event Date";s:10:"2010-07-10";s:10:"Event Type";s:92:"<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>";s:22:"Details of the event :";s:4:"40th";s:30:"Time and Duration of the Event";s:139:"<timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration>";s:5:"Venue";s:7:"My Gaff";s:13:"Venue Address";s:183:"<postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress>";s:20:"Type of Act required";s:20:"Small Acoustic Group";s:25:"Styles of Music preferred";s:88:"<eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle>";s:16:"Special Requests";s:19:"Get them up dancing";s:16:"Preferred Budget";s:3:"200";s:14:"Accepted Quote";s:1:"0";s:27:"acradb_button_EditthisEvent";s:15:"Edit this Event";s:53:"acradb_button_ContacttheselectedActs(throughGoodgigs)";s:44:"Contact the selected Acts (through Goodgigs)";s:21:"Quotes for this event";a:1:{i:0;a:4:{i:0;a:10:{s:14:"Quote number: ";s:2:"41";s:18:"Date Quote Entered";s:19:"2010-06-04 20:30:22";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"37";}s:11:"Quote value";s:3:"231";s:7:"Deposit";s:2:"30";s:8:"selected";s:1:"0";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"37";s:8:"locality";s:52:"<region><county><item>Meath</item></county></region>";}}}}i:1;a:10:{s:14:"Quote number: ";s:2:"42";s:18:"Date Quote Entered";s:19:"2010-06-04 20:31:06";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"21";}s:11:"Quote value";s:3:"294";s:7:"Deposit";s:2:"50";s:8:"selected";s:1:"0";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"21";s:8:"locality";s:89:"<region><county><item>Dublin</item><item>Meath</item><item>Clare</item></county></region>";}}}}i:2;a:10:{s:14:"Quote number: ";s:2:"43";s:18:"Date Quote Entered";s:19:"2010-10-15 15:30:48";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"21";}s:11:"Quote value";s:4:"1050";s:7:"Deposit";s:3:"100";s:8:"selected";s:1:"1";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"21";s:8:"locality";s:89:"<region><county><item>Dublin</item><item>Meath</item><item>Clare</item></county></region>";}}}}i:3;a:10:{s:14:"Quote number: ";s:2:"44";s:18:"Date Quote Entered";s:19:"2010-10-15 15:31:02";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"22";}s:11:"Quote value";s:4:"1155";s:7:"Deposit";s:3:"110";s:8:"selected";s:1:"1";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"22";s:8:"locality";s:0:"";}}}}}}}}',
           'expected' => "=== Event No. == 48 =========\n[Event Date] 2010-07-10<br />[Event Type] <eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription><br />[Details of the event :] 40th<br />[Time and Duration of the Event] <timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration><br />[Venue] My Gaff<br />[Venue Address] <postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress><br />[Type of Act required] Small Acoustic Group<br />[Styles of Music preferred] <eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle><br />[Special Requests] Get them up dancing<br />[Preferred Budget] 200<br />[Accepted Quote] 0<br />\nQuotes that are of interest: \nQuote no. [43] from Act [21] : value [1050] (deposit [100]) \nQuote no. [44] from Act [22] : value [1155] (deposit [110]) \n==============================="
       );
       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = bandchoice_formatForEmail_eventQuotes(unserialize($data[$index]['event_data_serialised']));
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result,$data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }
   }

   function test_bandchoice_formatForEmail_quotes() {
       $data[] = array(
           'event_data_serialised' => 'a:10:{s:14:"Quote number: ";s:2:"41";s:18:"Date Quote Entered";s:19:"2010-06-04 20:30:22";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"37";}s:11:"Quote value";s:3:"231";s:7:"Deposit";s:2:"30";s:8:"selected";s:1:"0";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"37";s:8:"locality";s:52:"<region><county><item>Meath</item></county></region>";}}}}',
           'expected' => ''
       );
       $data[] = array(
           'event_data_serialised' => 'a:10:{s:14:"Quote number: ";s:2:"42";s:18:"Date Quote Entered";s:19:"2010-06-04 20:31:06";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"21";}s:11:"Quote value";s:3:"294";s:7:"Deposit";s:2:"50";s:8:"selected";s:1:"0";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"21";s:8:"locality";s:89:"<region><county><item>Dublin</item><item>Meath</item><item>Clare</item></county></region>";}}}}',
           'expected' => ''
       );
       $data[] = array(
           'event_data_serialised' => 'a:10:{s:14:"Quote number: ";s:2:"43";s:18:"Date Quote Entered";s:19:"2010-10-15 15:30:48";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"21";}s:11:"Quote value";s:4:"1050";s:7:"Deposit";s:3:"100";s:8:"selected";s:1:"1";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"21";s:8:"locality";s:89:"<region><county><item>Dublin</item><item>Meath</item><item>Clare</item></county></region>";}}}}',
           'expected' => "Quote no. [43] from Act [21] : value [1050] (deposit [100]) \n"
       );
       $data[] = array(
           'event_data_serialised' => 'a:10:{s:14:"Quote number: ";s:2:"44";s:18:"Date Quote Entered";s:19:"2010-10-15 15:31:02";s:33:"Event for which the quote is made";s:2:"48";s:34:"__dataFromHigherUpInTheHierarchy__";a:1:{s:20:"Act making the Quote";s:2:"22";}s:11:"Quote value";s:4:"1155";s:7:"Deposit";s:3:"110";s:8:"selected";s:1:"1";s:29:"acradb_button_Selectthisquote";s:17:"Select this quote";s:32:"acradb_button_De-selectthisquote";s:20:"De-select this quote";s:20:"Act making the Quote";a:1:{i:0;a:1:{i:0;a:2:{s:8:"Band No.";s:2:"22";s:8:"locality";s:0:"";}}}}',
           'expected' => "Quote no. [44] from Act [22] : value [1155] (deposit [110]) \n"
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
           $result = bandchoice_formatForEmail_quotes(unserialize($data[$index]['event_data_serialised']));
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result,$data[$index]['expected']);
               p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }
   }

   function test_updateFromEditForm() {
       global $dataset;
       $data[] = array(
           'arrayOfValuesIndexedByLabel' => 'a:1:{s:9:"Selected:";i:1;}',
           'dataset' => $dataset["quotes"],
           'keyFieldValue' => '41',
           'expected' => "UPDATE `quotes` SET `selected`  = 1  WHERE `quote_id`  = '41'"
       );
       $data[] = array(
           'arrayOfValuesIndexedByLabel' => 'a:11:{s:7:"user_id";s:2:"58";s:22:"Type__sp__of__sp__user";s:14:"eventOrganiser";s:14:"Full__sp__Name";s:17:"Client Clientsson";s:33:"Company__sp__(if__sp__applicable)";s:15:"Client Services";s:19:"Postal__sp__Address";s:177:"<postalAddress><street><item><line>c</line></item><item><line>c</line></item><item><line>extra</line></item></street><county><item><data>c</data></item></county></postalAddress>";s:18:"Phone__sp__numbers";s:0:"";s:29:"Login__sp__Email__sp__Address";s:17:"client@client.com";s:36:"Additional__sp__Email__sp__addresses";s:0:"";s:42:"Opt__sp__in__sp__to__sp__mailing__sp__list";s:0:"";s:19:"__PASSWORDIGNORE__1";s:0:"";s:19:"__PASSWORDIGNORE__2";s:0:"";}',
           'dataset' => $dataset["users"],
           'keyFieldValue' => '58',
           'expected' => "UPDATE `users` SET `user_type`  = 'eventOrganiser' , `name`  = 'Client Clientsson' , `company`  = 'Client Services' , `postal_address`  = '<postalAddress><street><item><line>c</line></item><item><line>c</line></item><item><line>extra</line></item></street><county><item><data>c</data></item></county></postalAddress>' , `phone`  = '' , `primary_email`  = 'client@client.com' , `additional_email`  = '' , `mailinglist`  = 0  WHERE `user_id`  = '58'"
       );
       $data[] = array(
           'arrayOfValuesIndexedByLabel' => 'a:12:{s:32:"Event__sp__no__dt____sp____cln__";s:2:"48";s:24:"Description__sp____cln__";s:92:"<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>";s:48:"Details__sp__of__sp__the__sp__event__sp____cln__";s:4:"40th";s:17:"Date__sp____cln__";s:12:"10 July 2010";s:55:"Time__sp__and__sp__Duration__sp__of__sp__the__sp__Event";s:139:"<timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration>";s:23:"Type__sp__of__sp__Venue";s:5:"House";s:15:"Venue__sp__Name";s:7:"My Gaff";s:18:"Venue__sp__Address";s:183:"<postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress>";s:35:"Type__sp__of__sp__Act__sp__required";s:20:"Small Acoustic Group";s:40:"Styles__sp__of__sp__Music__sp__preferred";s:88:"<eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle>";s:21:"Special__sp__Requests";s:19:"Get them up dancing";s:21:"Preferred__sp__Budget";s:3:"200";}',
           'dataset' => $dataset["events"],
           'keyFieldValue' => '48',
           'expected' => "UPDATE `events` SET `description`  = '<eventDescription><basic><item><data>Birthday Party</data></item></basic></eventDescription>' , `details`  = '40th' , `date`  = '2010-07-10' , `time`  = '<timeAndDuration><startTime><item><data>20:00</data></item></startTime><endTime><item><data>22:00</data></item></endTime></timeAndDuration>' , `venue_type`  = 'House' , `venue_name`  = 'My Gaff' , `venue_address`  = '<postalAddress><street><item><line>76 Balreask Manor</line></item></street><town><item><data>Navan</data></item></town><county><item><data>Meath</data></item></county></postalAddress>' , `act_type`  = 'Small Acoustic Group' , `styles`  = '<eventMusicStyle><basic><item><data>Folk/Ballads</data></item></basic></eventMusicStyle>' , `special_requests`  = 'Get them up dancing' , `act_budget`  = 200  WHERE `event_id`  = '48'"
       );


       for($index = 0; $index < sizeof($data); $index = $index + 1) {
          $result = $data[$index]['dataset']->updateFromEditForm(
              unserialize($data[$index]['arrayOfValuesIndexedByLabel']),
              $data[$index]['keyFieldValue'],
              $testOnly=true
           );
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result,$data[$index]['expected']);
               p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }

   }


   function test_bandchoice_permission_user_quote() {
       global $dataset;
       $data[] = array(
           'quote_id' => '87',
           'username' => 'client@client.com',
           'expected' => true
       );
       $data[] = array(
           'quote_id' => '40',
           'username' => 'contact@contact.com',
           'expected' => false
       );
       $data[] = array(
           'quote_id' => '87',
           'username' => 'o',
           'expected' => false
       );

       for ( $index = 0; $index < sizeof($data); $index = $index + 1) {
          $result = bandchoice_permission_user_quote($data[$index]['quote_id'], $data[$index]['username']);
           if($result !== $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result,$data[$index]['expected']);
               p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result,$data[$index]['expected']);
       }

   }

}
?>











