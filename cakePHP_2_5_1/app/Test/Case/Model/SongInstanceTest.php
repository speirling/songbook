<?php
App::uses('SongInstance', 'Model');

/**
 * SongInstance Test Case
 *
 */
class SongInstanceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.song_instance',
		'app.song',
		'app.performer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SongInstance = ClassRegistry::init('SongInstance');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SongInstance);

		parent::tearDown();
	}

}
