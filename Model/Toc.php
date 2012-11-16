<?php
App::uses('AppModel', 'Model');
/**
 * Toc Model
 *
 * @property Book $Book
 * @property Toc $ParentToc
 * @property Toc $ChildToc
 */
class Toc extends AppModel {

    public $actsAs = array('Tree');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'book_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
	public $belongsTo = array(
		'Book' => array(
			'className' => 'Book',
			'foreignKey' => 'book_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ParentToc' => array(
			'className' => 'Toc',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
 */

    public function getBookNodeId($book_id) {
        $conditions = array('obj_type' => 'book', 'obj_id' => $book_id);
        $book_node = $this->find('first', compact('conditions'));
        if (!empty($book_node[$this->alias]['id'])) {
            $book_node_id = $book_node[$this->alias]['id'];
            return $book_node_id;
        }

        return false;
    }

    public function getBookTocThreaded($book_id) {
        //pr(__METHOD__);
        if ($book_node_id = $this->getBookNodeId($book_id)) {
            $root = $this->read(null, $book_node_id);
            //pr($root);

            $conditions = array('Toc.book_id' => $book_id);
            $order = 'Toc.order ASC';
            $tocs = $this->find('threaded', compact('conditions', 'order'));

            if (!empty($tocs)) {
                return $tocs;
            }
        }

        return false;
    }

}
