<?php
App::uses('SongTag', 'Model');

/**
 * SongTag Test Case
 *
 */
class SongTagTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.song_tag',
		'app.tag',
		'app.song',
		'app.song_instance',
		'app.performer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SongTag = ClassRegistry::init('SongTag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SongTag);

		parent::tearDown();
	}

}
