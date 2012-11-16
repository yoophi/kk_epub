<?php
App::uses('Cardbook', 'Model');

/**
 * Cardbook Test Case
 *
 */
class CardbookTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.cardbook',
		'app.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Cardbook = ClassRegistry::init('Cardbook');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Cardbook);

		parent::tearDown();
	}

}
