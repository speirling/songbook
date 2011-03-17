<?php

class ST_tr_class extends UnitTestCase {

    function test_trClass_constructor() {
        global $test_dataset;

        $expected = 'O:34:"acradb_tableRelationshipDefinition":39:{s:13:"datasetHandle";N;s:11:"description";s:0:"";s:8:"database";s:'.strlen(PRIMARY_DATABASE).':"'.PRIMARY_DATABASE.'";s:14:"datasetCaption";N;s:13:"recordCaption";N;s:15:"primaryKeyField";N;s:15:"fieldDefinition";N;s:24:"compositeFieldDefinition";a:0:{}s:20:"compositeFieldLabels";a:0:{}s:18:"sortOrderFieldName";N;s:18:"sortOrderTableName";N;s:14:"sortOrderField";N;s:13:"sortDirection";N;s:10:"tablenames";a:0:{}s:6:"labels";a:0:{}s:14:"fieldsPerTable";a:0:{}s:15:"pivotTableArray";a:0:{}s:15:"pivotTableLabel";a:0:{}s:21:"resultArrayStructured";N;s:30:"resultArrayStructuredTotalSize";N;s:15:"resultArrayFlat";N;s:24:"resultArrayFlatTotalSize";N;s:33:"labelToDataSubarrayCrossreference";N;s:26:"fieldToLabelCrossReference";a:0:{}s:9:"tableJoin";N;s:24:"joinsCrossReferenceArray";N;s:26:"path_to_dataset_definition";N;s:20:"use_admin_stylesheet";N;s:22:"use_default_stylesheet";N;s:11:"path_to_css";N;s:22:"fieldnamesActionsArray";N;s:27:"sortableGridHighlightColour";N;s:15:"definitionQuery";N;s:19:"whereANDClauseArray";N;s:18:"whereORClauseArray";N;s:16:"hierarchicalView";N;s:20:"CSS_totalFieldHeight";a:0:{}s:16:"formatConditions";a:0:{}s:17:"fullRecordActions";a:0:{}}';

        $test_dataset = new acradb_tableRelationshipDefinition;
        $test_dataset->database = PRIMARY_DATABASE;
        $this->assertEqual($test_dataset, unserialize($expected));
    }

    function test_defineField() {
        global $test_dataset;

        //no label specified
        $expected = 'a:5:{s:9:"tablename";s:5:"names";s:9:"fieldname";s:2:"id";s:12:"labelVisible";b:0;s:5:"label";s:2:"id";s:9:"isVisible";b:1;}';
        $test_dataset->defineField("names.id");
        $this->assertEqual($test_dataset->fieldDefinition['names.id'], unserialize($expected));
/*
        //label specified
        $expected = 'a:5:{s:9:"tablename";s:5:"names";s:9:"fieldname";s:4:"name";s:12:"labelVisible";b:1;s:5:"label";s:10:"First Name";s:9:"isVisible";b:1;}';
	    $test_dataset->defineField("names.name","First Name");
        $this->assertEqual($test_dataset->fieldDefinition['names.name'], unserialize($expected));
*/
//Previous commented out because you CAN define a field twice, and it breaks other things - needs to be fixed.
        $data[] = array(
        'fieldSpec' => 'names.name' ,
        'label' => 'First Name' ,
        'expected' => unserialize('a:5:{s:9:"tablename";s:5:"names";s:9:"fieldname";s:4:"name";s:12:"labelVisible";b:1;s:5:"label";s:10:"First Name";s:9:"isVisible";b:1;}')
        );

		for ($index = 0; $index < sizeof($data); $index = $index + 1) { //p($index, sizeof($data), $data[$index], $data[$index]['fieldspec']);
           $test_dataset->defineField($data[$index]['fieldSpec'], $data[$index]['label']);
           $result = $test_dataset->fieldDefinition[$data[$index]['fieldSpec']];
           if($result != $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }

    }

    function test_setLabel() {
        global $test_dataset;

        $data[] = array(
        'fieldSpec' => 'names.name' ,
        'label' => 'Test Label' ,
        'expected' => unserialize('a:5:{s:9:"tablename";s:5:"names";s:9:"fieldname";s:4:"name";s:12:"labelVisible";b:1;s:5:"label";s:10:"Test Label";s:9:"isVisible";b:1;}')
        );
        $data[] = array(
        'fieldSpec' => 'names.name' ,
        'label' => 'First Name' ,
        'expected' => unserialize('a:5:{s:9:"tablename";s:5:"names";s:9:"fieldname";s:4:"name";s:12:"labelVisible";b:1;s:5:"label";s:10:"First Name";s:9:"isVisible";b:1;}')
        );

		for ($index = 0; $index < sizeof($data); $index = $index + 1) { //p($index, sizeof($data), $data[$index], $data[$index]['fieldspec']);
           $test_dataset->setLabel($data[$index]['fieldSpec'], $data[$index]['label']);
           $result = $test_dataset->fieldDefinition[$data[$index]['fieldSpec']];
           if($result != $data[$index]['expected']) {
               echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
               acradisp_compare($result, $data[$index]['expected']);
       		   p(str_replace("\n","\\n",str_replace('"','\"',$result)));
           }
           $this->assertEqual($result, $data[$index]['expected']);
       }



    }

    function test_add_types_to_fieldDefinitions() {
        //depends on the test database existing, and having the fields that match the $test_dataset
        global $test_dataset;

        $result = $test_dataset->add_types_to_fieldDefinitions();
        $this->assertTrue($result);
        //p($test_dataset->fieldDefinition);
    }

    function test_displaySequentialList() {
        global $test_dataset;

        $test_dataset->setPrimaryKeyField("names.id");
        $result = $test_dataset->displaySequentialList();
        $this->assertEqual($result,"<link href=\"/bandchoice.com/www/acra_i/tests/environment/.css\" rel=\"stylesheet\" type=\"text/css\">\n\n<div id=\"dataset__primary\" class=\"acradb_reportHolder\">\n  <div id=\"record__primary_1\" class=\"acradb_recordHolder\" >\n  <div id=\"names_id\">\n     <div id=\"data\"  class=\"int\">\n1\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n  <div id=\"names_name\">\n     <div id=\"label\" class=\"label\">First Name</div>\n     <div id=\"data\"  class=\"varchar\">\nanto\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n\n\n   </div>\n\n  <div id=\"record__primary_2\" class=\"acradb_recordHolder\" >\n  <div id=\"names_id\">\n     <div id=\"data\"  class=\"int\">\n2\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n  <div id=\"names_name\">\n     <div id=\"label\" class=\"label\">First Name</div>\n     <div id=\"data\"  class=\"varchar\">\nbill\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n\n\n   </div>\n\n  <div id=\"record__primary_3\" class=\"acradb_recordHolder\" >\n  <div id=\"names_id\">\n     <div id=\"data\"  class=\"int\">\n3\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n  <div id=\"names_name\">\n     <div id=\"label\" class=\"label\">First Name</div>\n     <div id=\"data\"  class=\"varchar\">\nconor\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n\n\n   </div>\n\n  <div id=\"record__primary_4\" class=\"acradb_recordHolder\" >\n  <div id=\"names_id\">\n     <div id=\"data\"  class=\"int\">\n4\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n  <div id=\"names_name\">\n     <div id=\"label\" class=\"label\">First Name</div>\n     <div id=\"data\"  class=\"varchar\">\nderek\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n\n\n   </div>\n\n<div style='clear:both;' class='clear'></div>\n\n</div>\n");
        //p($result);
        //p(str_replace("\n","\\n",str_replace('"','\"',$result)));
    }



    function test_define_calculated_field() {
        global $test_dataset_2;
        $test_dataset_2 = new acradb_tableRelationshipDefinition;
        $test_dataset_2->database = PRIMARY_DATABASE;
        $test_dataset_2->defineField("fruit.fruit","Fruit Name");
        $test_dataset_2->defineField("fruit.cost","Cost Price");
        $test_dataset_2->defineField("fruit.markup","Added on");
        $test_dataset_2->defineCalculatedField(array(
            array("delimiter" => "("),
            array("field" => "fruit.cost"),
            array("operator" => "+"),
            array("field" => "fruit.markup"),
            array("delimiter" => ")"),
            array("operator" => "*"),
            array("value" => 1.21)
         ),"Total Price", 2);
         $expected = 'a:1:{s:11:"Total Price";a:9:{s:7:"content";a:7:{i:0;a:1:{s:9:"delimiter";s:1:"(";}i:1;a:1:{s:5:"field";s:10:"fruit.cost";}i:2;a:1:{s:8:"operator";s:1:"+";}i:3;a:1:{s:5:"field";s:12:"fruit.markup";}i:4;a:1:{s:9:"delimiter";s:1:")";}i:5;a:1:{s:8:"operator";s:1:"*";}i:6;a:1:{s:5:"value";d:1.20999999999999996447286321199499070644378662109375;}}s:10:"tablenames";a:1:{i:0;s:5:"fruit";}s:10:"fieldnames";a:2:{i:0;s:4:"cost";i:1;s:6:"markup";}s:17:"nominal_tablename";s:10:"calc_fruit";s:17:"nominal_fieldname";s:15:"costmarkup_calc";s:9:"isVisible";b:1;s:12:"labelVisible";b:1;s:9:"precision";i:2;s:4:"type";s:4:"text";}}';
           /*
            $test_dataset_2->calculatedFieldDefinition = Array(
            [Total Price] => Array (
                    [content] => Array (
                            [0] => Array ( [field] => fruit.cost )
                            [1] => Array ( [operator] => + )
                            [2] => Array ( [field] => fruit.markup )
                            [3] => Array ( [operator] => * )
                            [4] => Array ( [value] => 1.21 )
                        )
                    [tablenames] => Array ( [0] => fruit )
                    [fieldnames] => Array (
                            [0] => cost
                            [1] => markup
                        )
                    [nominal_tablename] => maths_fruit
                    [nominal_fieldname] => costmarkup_maths
                    [isVisible] => 1
                    [labelVisible] => 1
                )
        	)
            */
         //p(serialize($test_dataset_2->calculatedFieldDefinition));
         $this->assertEqual($test_dataset_2->calculatedFieldDefinition, unserialize($expected));
    }

    function test_define_calculated_field_with_type() {
        global $test_dataset_3;
        $test_dataset_3 = new acradb_tableRelationshipDefinition;
        $test_dataset_3->database = PRIMARY_DATABASE;
        $test_dataset_3->defineField("fruit.fruit","Fruit Name");
        $test_dataset_3->defineField("fruit.cost","Cost Price");
        $test_dataset_3->defineField("fruit.markup","Added on");
        $test_dataset_3->defineCalculatedField(array(
            array("delimiter" => "("),
            array("field" => "fruit.cost"),
            array("operator" => "+"),
            array("field" => "fruit.markup"),
            array("delimiter" => ")"),
            array("operator" => "*"),
            array("value" => 1.21)
         ),"Total Price", 2, "currency_euro");
         $expected = 'a:1:{s:11:"Total Price";a:9:{s:7:"content";a:7:{i:0;a:1:{s:9:"delimiter";s:1:"(";}i:1;a:1:{s:5:"field";s:10:"fruit.cost";}i:2;a:1:{s:8:"operator";s:1:"+";}i:3;a:1:{s:5:"field";s:12:"fruit.markup";}i:4;a:1:{s:9:"delimiter";s:1:")";}i:5;a:1:{s:8:"operator";s:1:"*";}i:6;a:1:{s:5:"value";d:1.20999999999999996447286321199499070644378662109375;}}s:10:"tablenames";a:1:{i:0;s:5:"fruit";}s:10:"fieldnames";a:2:{i:0;s:4:"cost";i:1;s:6:"markup";}s:17:"nominal_tablename";s:10:"calc_fruit";s:17:"nominal_fieldname";s:15:"costmarkup_calc";s:9:"isVisible";b:1;s:12:"labelVisible";b:1;s:9:"precision";i:2;s:4:"type";s:13:"currency_euro";}}';
           /*
            $test_dataset_3->calculatedFieldDefinition = Array(
            [Total Price] => Array (
                    [content] => Array (
                            [0] => Array ( [field] => fruit.cost )
                            [1] => Array ( [operator] => + )
                            [2] => Array ( [field] => fruit.markup )
                            [3] => Array ( [operator] => * )
                            [4] => Array ( [value] => 1.21 )
                        )
                    [tablenames] => Array ( [0] => fruit )
                    [fieldnames] => Array (
                            [0] => cost
                            [1] => markup
                        )
                    [nominal_tablename] => maths_fruit
                    [nominal_fieldname] => costmarkup_maths
                    [isVisible] => 1
                    [labelVisible] => 1
                )
        	)
            */
         //p(serialize($test_dataset_3->calculatedFieldDefinition));
         $this->assertEqual($test_dataset_3->calculatedFieldDefinition, unserialize($expected));
    }

    function test_acradb_tr_addCalculatedFieldValueToRow() {
        global $test_dataset_2;

        $data_row = array("Fruit Name" => "Apple", "Cost Price" => 0.15, "Added on" => 0.05);
        $result = acradb_tr_createArrayOfCalculatedFieldValues($data_row, $test_dataset_2->calculatedFieldDefinition, $test_dataset_2->fieldDefinition);
        $this->assertEqual($result,Array("Total Price" => 0.24));

        $data_row = array("Fruit Name" => "Banana", "Cost Price" => 0.21, "Added on" => 0.07);
        $result = acradb_tr_createArrayOfCalculatedFieldValues($data_row, $test_dataset_2->calculatedFieldDefinition, $test_dataset_2->fieldDefinition);
        $this->assertEqual($result,Array("Total Price" => 0.34));

        $data_row = array("Fruit Name" => "Banana", "Cost Price" => null, "Added on" => null);
        $result = acradb_tr_createArrayOfCalculatedFieldValues($data_row, $test_dataset_2->calculatedFieldDefinition, $test_dataset_2->fieldDefinition);
        $this->assertEqual($result,Array("Total Price" => 0));

    }

    function test_acradb_tr_fieldOrder() {
        $temp1_dataset = new acradb_tableRelationshipDefinition;
    	$temp1_dataset->defineField("acts.act_id");
    	$temp1_dataset->overrideFieldType("acts.act_id","int(11)");
    	$temp1_dataset->defineField("acts.registered","Date Entered");
    	$temp1_dataset->overrideFieldType("acts.registered","date");
    	$temp1_dataset->defineCompositeField(array ("Act No. ",	array("acts","act_id")));
    	$temp1_dataset->defineField("acts.act_name","Act Name");
    	$temp1_dataset->overrideFieldType("acts.act_name","varchar(255)");
	    $temp1_dataset->defineField("acts.region","Locality");
    	$temp1_dataset->overrideFieldType("acts.region","region");

	    $this->assertEqual($temp1_dataset->display_order,Array(
            '0' => 'acts.act_id',
            '1' => 'acts.registered',
            '2' => 'composite_acts.act_id_composite',
            '3' => 'acts.act_name',
            '4' => 'acts.region'
        ));

    	$data_array = Array (
                'act_id' => 21,
                'Date Entered' => "2009-04-29 00:00:00",
                'Act Name' => "Pub Céilí",
                'Locality' => "<region><county><item>Dublin</item></county></region>",
                'act_id_composite' => "Goodgigs Act No. 21"
            );

       $expected = "  <div id=\"acts_registered\">\n     <div id=\"label\" class=\"label\">Date Entered</div>\n     <div id=\"data\"  class=\"date\">\n29/04/2009\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n  <div id=\"composite_acts_act_id_composite\">\n     <div id=\"data\"  class=\"text\">\nGoodgigs Act No. 21\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n  <div id=\"acts_act_name\">\n     <div id=\"label\" class=\"label\">Act Name</div>\n     <div id=\"data\"  class=\"varchar\">\nPub Céilí\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n  <div id=\"acts_region\">\n     <div id=\"label\" class=\"label\">Locality</div>\n     <div id=\"data\"  class=\"region\">\n			<div id=\"Locality_display\" style=\"\">\nDublin\n</div>\n\n    </div>\n    <div style='clear:both;' class='clear'></div>\n  </div>\n";

      $result = acradb_generate_singleRecordBlock($data_array, $temp1_dataset, "sequentialList", false);
       if($result !== $expected) {
           echo "<h2 class='test-fail'>".__FUNCTION__."[".$index."]"."</h2>";
           acradisp_compare($result,$expected);
           p(str_replace("\n","\\n",str_replace('"','\"',$result)));
       }
       $this->assertEqual($result,$expected);


   }

}
?>