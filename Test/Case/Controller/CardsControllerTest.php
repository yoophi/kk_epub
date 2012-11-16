<?php
App::uses('CardsController', 'Controller');

/**
 * CardsController Test Case
 *
 */
class CardsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.card',
		'app.user',
		'app.category',
		'app.cardbook_toc',
		'app.book',
		'app.obj'
	);

}
