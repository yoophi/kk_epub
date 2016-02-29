<?php
App::uses('AppAuthController', 'Controller');
/**
 * Cardbooks Controller
 *
 * @property Cardbook $Cardbook
 */
class MyCardbooksController extends AppAuthController {

    public $uses = array('Cardbook');
    public $paginate = array('Cardbook' => array('conditions' => array()));
    public $allowJsonRequest = array(
             'index', 
             'spine_index', 'spine_add', 'spine_delete', 'spine_order_update'
           );

    function index() {
		$this->Cardbook->recursive = 0;
        $this->paginate['Cardbook']['conditions'][] = array('Cardbook.user_id' => $this->currentUserId);
        $cardbooks = $this->paginate();
		$this->set('cardbooks', $cardbooks);
        $this->set('_serialize', array('cardbooks'));
    }

    function view() {
        $cardbook_id = $this->getCardbookId();
		$this->Cardbook->id = $cardbook_id;
		if (!$this->Cardbook->exists()) {
			throw new NotFoundException(__('Invalid cardbook'));
		}

        $cardbook = $this->Cardbook->getBookInfo($cardbook_id);
        $this->set(compact('cardbook'));
    }

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit() {
        $id = $this->getCardbookId();
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
        $id = $this->getCardbookId();
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
        $id = $this->getCardbookId();
//		$this->Book->id = $id;
//
//        $spine  = $this->Book->getSpine($id);
        $book   = $this->Book->getBookInfo($id);
//
        //$this->set(compact('spine', 'toc', 'book'));
        $this->set(compact('book'));
    }

    protected function getCardbookId() {
        $book_id = null;
        foreach(array('book_id', 'id') as $key) {
            if (!empty($this->request->params[$key])) {
                $book_id = $this->request->params[$key];
                break;
            }
        }
        if (!is_numeric($book_id)) {
            throw new BadRequestException;
        }

        return $book_id;
    }

    function spine_index() {
        if (!$this->isJsonRequest()) {
            $this->setAction('spine');
            return;
        }
        $id = $this->getCardbookId();
        $this->Book->id = $id;

        $spine  = $this->Book->getSpine($id);
//        $book   = $this->Book->getBookInfo($id);

//        $this->set(compact('spine', 'toc', 'book'));

//        $this->set('res', $this->request->params);
        $this->set('res', $spine);
        $this->set('_serialize', 'res');
    }

    function spine_add() {
        $book_id = $this->getCardbookId();
        $data = $this->__getPayLoad();
        $result = null;
        $this->log(print_r($data, true), 'rest');

        // ids
        if (isset($data['article_ids'])) {
            $success = 0;
            foreach($data['article_ids'] as $article_id) {
                if (is_numeric($article_id)) {
                   if ($this->Book->addSpine($book_id, $article_id)) {
                       $success++;
                   }
                }
            }
            if ($success > 0) {
                $result = true;
            }
        } elseif (isset($data['article_id']) && is_numeric($data['article_id'])) {
            $this->Book->addSpine($book_id, $data['article_id']);
        } else {
            $result = false;
        }

        $this->set('res', $result);
        $this->set('_serialize', 'res');
    }

    function spine_delete() {
        $spine_id = $this->request->params['spine_id'];
        $res = $this->Book->BookSpine->delete($spine_id);
        $this->set('res', $res);
        $this->set('_serialize', 'res');
    }

    function spine_order_update() {
        $book_id = $this->getCardbookId();
        $data = $this->__getPayLoad();

        foreach ($data as $item) {
            $this->log(print_r($item, true), 'rest');
            $this->Book->BookSpine->id = $item['id'];
            $this->Book->BookSpine->saveField('order', $item['order']);
        }

        $this->set('res', 'xxx');
        $this->set('_serialize', 'res');
    }

}
