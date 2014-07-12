<?php
class SongsController extends AppController {
    public $helpers = array('Html', 'Form');

    public function index() {
        $this->set('songs', $this->Song->find('all'));
    }
}


?>
