<?php
App::uses('CardbookToc', 'Model');

/**
 * CardbookToc Test Case
 *
 */
class CardbookTocTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.cardbook_toc',
		'app.book',
		'app.obj',
		'app.card',
		'app.user',
		'app.category'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CardbookToc = ClassRegistry::init('CardbookToc');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CardbookToc);

		parent::tearDown();
	}

}
