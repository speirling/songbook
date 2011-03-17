<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('simpletest/autorun.php');
require_once('environment/configure.inc.php');


class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile(ABSOLUTE_PATH_TO_ACRA_SCRIPTS.'/tests/acradb_generate_singleRecordBlock.php');
        $this->addFile(ABSOLUTE_PATH_TO_ACRA_SCRIPTS.'/tests/basic.php');
        $this->addFile(ABSOLUTE_PATH_TO_ACRA_SCRIPTS.'/tests/database_interaction.php');
        $this->addFile(ABSOLUTE_PATH_TO_ACRA_SCRIPTS.'/tests/structured_data.php');
        $this->addFile(ABSOLUTE_PATH_TO_ACRA_SCRIPTS.'/tests/tr_class.php');
        $this->addFile(ABSOLUTE_PATH_TO_ACRA_SCRIPTS.'/tests/edit_form.php');
    }
}
?>