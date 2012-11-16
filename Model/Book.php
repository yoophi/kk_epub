<?php
App::uses('AppModel', 'Model');
class Book extends AppModel {

    public $hasMany = array('BookSpine');
    public $actsAs = array('Containable');

    public function getBookInfo($book_id) {
        if (empty($book_id)) {
            return false;
        }

        $this->unbindModel(array('hasMany' => array('BookSpine')));

        $conditions = array('Book.id' => $book_id);
        $fields = array('Book.*', 'User.id', 'User.username', 'User.email');
        $this->bindModel(array('belongsTo' => array('User')));

        $book_info = $this->find('first', compact('conditions', 'fields'));
        $book_info = Set::flatten($book_info);
        return $book_info;
    }

    public function getBookToc($book_id) {
        App::uses('Toc', 'Model');
        $Toc = new Toc;

        $tocs = $Toc->getBookTocThreaded($book_id);
        return $tocs;
    }

    public function getToc($book_id = null ) {
        if (empty($book_id) && !empty($this->id)) {
            $book_id = $this->id;
        }

        if (empty($book_id)) {
            throw Exception('invalid id');
        }

        $fields = array('toc_json');
        $data = $this->read($fields, $book_id);
        if (empty($data[$this->alias]['toc_json'])) {
            return false;
        } 

        $toc = json_decode($data[$this->alias]['toc_json'], true);
        return $toc;
    }

    public function getSpine($book_id = null) {
        if (empty($book_id) && !empty($this->id)) {
            $book_id = $this->id;
        }

        if (empty($book_id)) {
            throw Exception('invalid id');
        }

        $this->BookSpine->bindModel(array('belongsTo' => array('Article')));
//        $joins = array(
//        'table' => 'articles',
//        'alias' => 'Article',
//        'type' => 'LEFT',
//        'conditions' => 
//            'BookSpine.article_id = Article.id'
//        );
        $conditions = array('BookSpine.book_id' => $book_id);
        $order = 'BookSpine.order ASC';
        $contain = array('Article');
        $fields = array('Book.*', 'BookSpine.*', 'Article.subject');
        $items = $this->BookSpine->find('all', compact('conditions', 'order', 'contain', 'fields'));
        pr($items);
        $return = array();

        foreach ($items as $k => $v) {
            $return[] = $v['BookSpine'];
        }

        return $return;
    }

}
?>
