<?php
class ST_basic extends UnitTestCase {
 function test_generateJoinArrays () {
    global $dataset;

    $definedFields_1 = Array(
        0 => 'events.event_id',
        1 => 'events.date',
        2 => 'events.description',
        3 => 'events.details',
        4 => 'events.time',
        5 => 'events.venue_name',
        6 => 'events.venue_address',
        7 => 'events.act_type',
        8 => 'events.styles',
        9 => 'events.special_requests',
        10 => 'events.act_budget',
        11 => 'quotes.quote_id',
        12 => 'quotes.received',
        13 => 'quotes.event_id',
        14 => 'quotes.act_id',
        15 => 'quotes.quote',
        16 => 'quotes.deposit',
        17 => 'acts.act_id',
        18 => 'acts.region',
        19 => 'events.accepted_quote'
    );
    $joinStatement_1 = 'quotes.act_id = acts.act_id';
    $joinType_1 = 'LEFT';
    $joinComplexity_1 = 'n-1';

    $expectedOut_tableJoinArray_1 = array(
        'leftTable' => 'quotes',
        'leftField' => 'act_id',
        'rightTable' => 'acts',
        'rightField' => 'act_id',
        'joinType' => 'INNER',
        'leftFieldComplexity' => 'n',
        'rightFieldComplexity' => '1'
    );
    $expectedOut_fieldDefJoins_1 = array(
     'quotes.act_id' => Array (
            'anchoredJoins' => Array (
                    0 => Array (
                            'table' => 'acts',
                            'field' => 'act_id',
                            'localEditable' => '1',
                            'remoteEditable' => ''
                        )
                )
        ),
      'acts.act_id' => Array (
            'anchoredJoins' => Array   (
                    0 => Array   (
                            'table' => 'quotes',
                            'field' => 'act_id',
                            'localEditable' => '',
                            'remoteEditable' => '1'
                        )
                )
        )
    );
    $expectedOut_joinsCrossReferenceArray_1 = array(
    'acts' => Array (
            '0' => Array (
                    'table' => 'quotes',
                    'field' => 'act_id',
                    'anchor' => 'act_id'
                )
        ),
    'quotes' => Array  (
            '0' => Array (
                    'table' => 'acts',
                    'field' => 'act_id',
                    'anchor' => 'act_id'
                )
        )
    );

    $definedFields_2 = Array (
        0 => 'acts.act_name',
        1 => 'acts.registered',
        2 => 'acts.region',
        3 => 'acts.styles',
        4 => 'acts.photo',
        5 => 'acts.mp3',
        6 => 'acts.act_description',
        7 => 'acts.set_list',
        8 => 'acts.priceRange',
        9 => 'acts.act_id',
        10 => 'quotes.event_id',
        11 => 'quotes.act_id',
        12 => 'quotes.quote',
        13 => 'events.date',
        14 => 'events.description',
        15 => 'events.event_id',
        16 => 'events.venue_region'
    );
    $joinStatement_2 = 'acts.act_id=quotes.act_id';
    $joinType_2 = 'LEFT';
    $joinComplexity_2 = '1-n';

    $expectedOut_tableJoinArray_2 = array();
    $expectedOut_fieldDefJoins_2 = array();
    $expectedOut_joinsCrossReferenceArray_2 = array();

    list($tableJoinArray,
	     $fieldDefJoins,
	     $joinsCrossReferenceArray
	) = acradb_tr_generateJoinArrays($definedFields_1, $joinStatement_1, $joinType_1 = "INNER", $joinComplexity_1, $editPermission = null);

	$this->assertEqual($tableJoinArray == $expectedOut_tableJoinArray_1,"[acradb_tr_generateJoinArrays()] Table Join array ");
	$this->assertEqual($fieldDefJoins == $expectedOut_fieldDefJoins_1,"[acradb_tr_generateJoinArrays()] fieldDefJoins array");
	$this->assertEqual($joinsCrossReferenceArray == $expectedOut_joinsCrossReferenceArray_1,"[acradb_tr_generateJoinArrays()] joinsCrossReferenceArray");
 }
}
?>