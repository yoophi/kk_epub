<?php
App::uses('AppModel', 'Model');
/**
 * Cardbook Model
 *
 * @property User $User
 */
class Cardbook extends AppModel {

    public $hasMany = array('CardbookSpine');
    public $actsAs = array('Containable');

    /**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'subject' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    public function getBookInfo($cardbook_id) {
        if (empty($cardbook_id)) {
            return false;
        }

        $this->unbindModel(array('hasMany' => array('CardbookSpine')));

        $conditions = array('Cardbook.id' => $cardbook_id);
        $fields = array('Cardbook.*', 'User.id', 'User.username', 'User.email');
        $this->bindModel(array('belongsTo' => array('User')));

        $book_info = $this->find('first', compact('conditions', 'fields'));
        $book_info = Set::flatten($book_info);
        return $book_info;
    }

}
