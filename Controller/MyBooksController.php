<?php
App::uses('AppAuthController', 'Controller');
/**
 * Articles Controller
 *
 * @property Article $Article
 */
class MyBooksController extends AppAuthController {

    public $uses = array('Book', 'Toc');
    public $paginate = array('Book' => array('conditions' => array()));
    public $allowJsonRequest = array('index');

    function index() {
		$this->Book->recursive = 0;
        $this->paginate['Book']['conditions'][] = array('Book.user_id' => $this->Auth->user('id'));
        $books = $this->paginate();
		$this->set('books', $books);
        $this->set('_serialize', array('books'));
    }

    function view() {
        $id = $this->getBookId();
		$this->Book->id = $id;
		if (!$this->Book->exists()) {
			throw new NotFoundException(__('Invalid book'));
		}

        /** find book_node_id **/
        $conditions = array('obj_type' => 'book', 'obj_id' => $id);
        $book_node = $this->Toc->find('first', compact('conditions'));
        $book_node_id = $book_node['Toc']['id'];
        /** end find book_node_id **/

		$book = $this->Book->read(null, $id);
        $toc = $this->Toc->children($book_node_id);
        $tocs = $this->Toc->find('threaded', array('conditions' => array('Toc.book_id' => $id), 'order' => 'Toc.order ASC'));

        $parent_ids = $this->Toc->generateTreeList(array('Toc.book_id'=>$id));
        // $parent_ids = array(0 => '- - -') + $parent_ids;

        $this->set(compact('book', 'toc', 'tocs', 'parent_ids'));
    }

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit() {
        $id = $this->getBookId();
		$this->Book->id = $id;
		if (!$this->Book->exists()) {
			throw new NotFoundException(__('Invalid book'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Book->save($this->request->data)) {
				$this->Session->setFlash(__('The book has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The book could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Book->read(null, $id);
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
            $this->request->data['Book']['user_id'] = $this->currentUserId;
			$this->Book->create();
			if (true && $this->Book->save($this->request->data)) {

                /** find user node id **/
                $conditions = array('obj_type' => 'user', 'obj_id' => $this->currentUserId);
                $user = $this->Toc->find('first', compact('conditions'));
                $user_node_id = $user['Toc']['obj_id'];
                //pr(compact('user', 'user_node_id'));
                //exit;
                /** end find user_node_id **/

                $book_id = $this->Book->getLastInsertId();

                $this->Toc->create();
                $this->Toc->save(array(
                            'book_id' => $book_id,
                            'name' => $this->request->data['Book']['subject'],
                            'parent_id' => $user_node_id,
                            'obj_type' => 'book',
                            'obj_id' => $book_id
                            ));

				$this->Session->setFlash(__('The book has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The book could not be saved. Please, try again.'));
			}
		}
	}

    function destroy() {
        pr('<h1>'.__METHOD__.'</h1>'); pr($this->request); exit;
    }

    function toc() {
        $id = $this->getBookId();
		$this->Book->id = $id;
		if (!$this->Book->exists()) {
			throw new NotFoundException(__('Invalid book'));
		}

        /** find book_node_id **/
        $conditions = array('obj_type' => 'book', 'obj_id' => $id);
        $book_node = $this->Toc->find('first', compact('conditions'));
        $book_node_id = $book_node['Toc']['id'];
        /** end find book_node_id **/

		$book = $this->Book->read(null, $id);
        $toc = $this->Toc->children($book_node_id);
        $tocs = $this->Toc->find('threaded', array('conditions' => array('Toc.book_id' => $id), 'order' => 'Toc.order ASC'));

        $parent_ids = $this->Toc->generateTreeList(array('Toc.book_id'=>$id));
        // $parent_ids = array(0 => '- - -') + $parent_ids;

        $this->set(compact('book', 'toc', 'tocs', 'parent_ids'));
    }

    function spine() {
        $id = $this->getBookId();
		$this->Book->id = $id;

        $spine  = $this->Book->getSpine($id);
        $toc    = $this->Book->getToc($id);
        $book   = $this->Book->getBookInfo($id);
        pr($book);

        $this->set(compact('spine', 'toc', 'book'));
    }

    protected function getBookId() {
        if (empty($this->request->params['id']) || !is_numeric($this->request->params['id'])) {
            throw new BadRequestException;
        }

        return $this->request->params['id']; 
    }

}
