<?php
App::uses('AppAuthController', 'Controller');
/**
 * Books Controller
 *
 * @property Book $Book
 */
class BooksController extends AppAuthController {

    public $uses = array('Book', 'Toc');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Book->recursive = 0;
		$this->set('books', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
        if (empty($id) && !empty($this->request->params['id'])) {
            $id = $this->request->params['id']; 
        }

		$this->Book->id = $id;
		if (!$this->Book->exists()) {
			throw new NotFoundException(__('Invalid book'));
		}
		$this->set('book', $this->Book->read(null, $id));
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

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
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
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Book->id = $id;
		if (!$this->Book->exists()) {
			throw new NotFoundException(__('Invalid book'));
		}
		if ($this->Book->delete()) {
			$this->Session->setFlash(__('Book deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Book was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

    function toc($id = null) {
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
     * javascript 이용한 수정 UI에 사용했었던 코드인데 잠시 보관처리
     */
    function toc_modify($book_id = null) {
		$this->Book->id = $book_id;
		if (!$this->Book->exists()) {
			throw new NotFoundException(__('Invalid book'));
		}

        /*
        App::uses('TocItem', 'Model');
        $this->TocItem = new TocItem();
        $this->TocItem->Behaviors->attach('Tree', array(
            'scope' => array('TocItem.book_id' => $book_id)
            ));
        */
        //pr($this->TocItem);

        $book = $this->Book->read(null, $book_id);
        //$toc_items = $this->TocItem->find('threaded');
        $conditions = array('Toc.book_id' => $book_id);
        $order = array('Toc.order' => 'ASC');
        $toc_items = $this->Toc->find('threaded', compact('conditions', 'order'));
        $this->set('book', $book['Book']);
        $this->set('toc_items', $toc_items);

        /*
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Book->save($this->request->data)) {
				$this->Session->setFlash(__('The book has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The book could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Book->read(null, $book_id);
		}
        */
    }

    function toc_add() {
		if ($this->request->is('post')) {
            $book_id = $this->request->data['Toc']['book_id'];
            $this->request->data['Toc']['obj_type'] = 'article';
			$this->Toc->create();
			if ($this->Toc->save($this->request->data)) {
				$this->Session->setFlash(__('The toc has been saved'));
			} else {
				$this->Session->setFlash(__('The toc could not be saved. Please, try again.'));
			}
            $this->redirect('/books/toc/' . $book_id);
		}
    }


    public function toc_update($book_id) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

        // pr($this->request->data); exit;
        //pr(compact('book_id'));
        
        $children = $this->Toc->children($this->Toc->getBookNodeId($book_id));
        //pr($children);

        foreach($children as $item) {
            $this->Toc->id = $item['Toc']['id'];
            $this->Toc->delete();
        }

        //echo '<hr />';
        $json = json_decode($this->request->data['Toc']['json'], true);
        //pr($json);
        $post = Set::nest($json, array('idPath' => '/id', 'parentPath' => '/parent_id'));
        //pr($post[0]['children']);

        $old_ids = array();

        foreach($json as $item) {
            //echo '<hr />';
            //pr('start');
            //pr($item);
            if (!empty($item['parent_id'])) {
                $item['orig_id'] = $item['id'];
                unset($item['id']);
                unset($item['left']);
                unset($item['right']);
                $item['obj_type'] = 'article';
                $item['obj_id'] = $item['article_id'];
                $item['book_id'] = $book_id;

                if (isset($old_ids[$item['parent_id']])) {
                    //pr('parent_id replaced.');
                    $item['parent_id'] = $old_ids[$item['parent_id']];
                }

                $this->Toc->create();
                if ($this->Toc->save(array('Toc'=>$item))) {
                    ;
                    // pr($this->Toc);
                    //pr('save success');
                    $new_id = $this->Toc->getLastInsertId();
                    $old_ids[$item['orig_id']] = $new_id;

                } //else {
                    //pr('save error');
                //}
                //pr('end');
            }
        }

        $this->Session->setFlash('목차정보를 업데이트했습니다.');

        $this->redirect('/books/toc/' . $book_id);

        //echo '<hr />';
        //pr($this->Toc->getBookTocThreaded($book_id));


        // $this->TocItem->Behaviors->disable('Tree');
        //pr($json);
        //exit;

        /*
        App::uses('TocItem', 'Model');
        $this->TocItem = new TocItem();

        foreach($json as $item) {
            $this->TocItem->save(array('TocItem' => $item));
        }

        echo sprintf('<a href="%s">%s</a>', Router::url('/books/toc/' . $book_id), '/books/toc/' . $book_id);

        exit;
        */
    }

    public function toc_link_article() {
		if (!$this->request->is('post')) {
            if (empty($this->request->params['named']['toc_id'])) {
                throw new BadRequestException(__('Invalid book'));
            }

            $toc_id = $this->request->params['named']['toc_id'];
            $conditions = array('id' => $toc_id);
            $toc = $this->Toc->find('first', compact('conditions'));

            $this->set(compact('toc_id', 'toc'));
        } else {
            // handle POST request

            if (empty($this->request->data['Toc']['action']) || !in_array($this->request->data['Toc']['action'], array('LINK', 'ADD'))) {
                throw new BadRequestException(__('Invalid request'));
            }

            App::uses('Article', 'Model');
            $this->Article = new Article;

            if ($this->request->data['Toc']['action'] == 'LINK') {
                $toc_id = $this->data['Toc']['id'];
                $article_id = $this->data['Toc']['article_id'];

                $conditions = array('id' => $toc_id);
                $toc = $this->Toc->find('first', compact('conditions'));
                $book_id = $toc['Toc']['book_id'];

                $this->Article->save(array('id' => $article_id, 'book_id' => $book_id));

                $data = array('id' => $toc_id, 'obj_id' => $article_id);
                if ($this->Toc->save($data)) {
                    $this->Session->setFlash(__('The toc has been saved'));
                } else {
                    $this->Session->setFlash(__('The toc could not be saved. Please, try again.')); 
                }

                $this->redirect('/books/toc/' . $book_id);
            } else {

                $this->Article->create();
                $data = array('subject' => 'untitled');
                $this->Article->save($data);
                $article_id = $this->Article->getLastInsertId();

                $toc_id = $this->data['Toc']['id'];
                $conditions = array('id' => $toc_id);
                $toc = $this->Toc->find('first', compact('conditions'));
                $book_id = $toc['Toc']['book_id'];

                $this->Article->save(array('id' => $article_id, 'book_id' => $book_id));

                $data = array('id' => $toc_id, 'obj_id' => $article_id);
                if ($this->Toc->save($data)) {
                    $this->Session->setFlash(__('The toc has been saved'));
                    $this->redirect('/articles/edit/' . $article_id);
                } else {
                    $this->Session->setFlash(__('The toc could not be saved. Please, try again.')); 
                }
                $this->redirect('/books/toc/' . $book_id);
            }
        }

    }

    public function toc_unlink_article() {
        $toc_id = $this->request->params['named']['toc_id'];
        $toc = $this->Toc->read(array('id', 'book_id', 'obj_type', 'obj_id'), $toc_id);
        // throw exception if not exists... 

        $book_id = $toc['Toc']['book_id'];

        $data = Set::merge($toc['Toc'], array('obj_id' => null));

        $this->Toc->save($data);
        $toc = $this->Toc->read(array('id', 'book_id', 'obj_type', 'obj_id'), $toc_id);
        $book_id = $toc['Toc']['book_id'];

        $this->redirect('/books/toc/' . $book_id);
    }

    public function toc_remove() {
        $toc_id = $this->request->params['named']['toc_id'];
        $toc = $this->Toc->read(array('id', 'book_id', 'obj_type', 'obj_id'), $toc_id);
        // throw exception if not exists... 
        $book_id = $toc['Toc']['book_id'];

        $this->Toc->delete($toc_id);

        $this->redirect('/books/toc/' . $book_id);
    }

}
