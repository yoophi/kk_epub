<?php
App::uses('AppAuthController', 'Controller');
/**
 * Articles Controller
 *
 * @property Card $Card
 * @property Cardbook $Cardbook
 */
class MyCardsController extends AppAuthController {

    public $uses = array('Card', 'Cardbook');
    public $paginate = array('Card' => array(
        'conditions' => array(),
        'fields' => array('Card.id', 'Card.user_id', 'Card.subject', 'Card.created', 'Card.category_id', 'Category.name')
    ));

    protected $__currentCardbookId = null;

    //
    public $allowJsonRequest = array('index', 'add', 'edit', 'delete');

    function index() {
        if ($this->isJsonRequest()) {
            $fields = Set::flatten(
                array(
                    'Card' => array(
                        'id',
                        'category_id',
                        'subject',
                        'created'
                    )
                )
            );
            $cards = $this->Card->find('all', compact('conditions', 'fields'));
            foreach ($cards as $key => &$value) {
                $value = $value['Card'];
            }

            $this->set('res', $cards);
            $this->set('_serialize', 'res');
            return;
        }

        $this->Card->recursive = 0;
        $this->paginate['Card']['conditions'][] = array('Card.user_id' => $this->currentUserId);
        $cards = $this->paginate();

        $this->set('cards', $cards);
    }

    function add() {
		if ($this->request->is('post')) {
            $this->request->data['Card']['user_id'] = $this->currentUserId;
			$this->Card->create();
			if ($this->Card->save($this->request->data)) {
				$this->Session->setFlash(__('The article has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The article could not be saved. Please, try again.'));
			}
		}

        $categories = $this->__getCategories();
        $this->set('categories', $categories);

        $use_texteditor = $this->Auth->user('use_texteditor');
        $this->set('use_texteditor', $use_texteditor);
    }

    function view() {
         // pr('<h1>'.__METHOD__.'</h1>'); pr($this->request); exit;
        $id = $this->request->params['id'];
        $article = $this->Article->read(null, $id);
        pr($article); exit;
    }


    function destroy() {
        pr('<h1>'.__METHOD__.'</h1>'); pr($this->request); exit;
    }

    public function __getCategories() {
        App::uses('Category', 'Model');
        $Category = new Category;
        $conditions = array('Category.user_id', $this->currentUserId);
        $order = array('Category.order ASC');
        $categories = array(0 => '- - -') + $Category->find('list', compact('conditions', 'order'));

        return $categories;
    }

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit() {
        $id = $this->getArticleId();
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {

            $input = $this->request->data['Article']['text'];
            if (!empty($this->request->data['Article']['is_html'])) {
                // Handle Wysiwyg
                $html = $input;

                // Markdownify
                require_once(APP . 'Vendor/markdownify/markdownify.php');
                $leap = MDFY_LINKS_EACH_PARAGRAPH;
                // $keephtml = MDFY_KEEPHTML;
                $keephtml = MDFY_KEEPHTML;
                $md = new Markdownify($leap, MDFY_BODYWIDTH, $keephtml);

                $text = $md->parseString($input);
            } else {
                // Handle Markdown text

                $text = $input;

                // Markdown
                require_once(APP . 'Vendor/markdown/markdown.php');
                $html = Markdown($text);
            }

            $content_raw = $text;
            $content_html = $html;

            $this->request->data['Article'] = array_merge($this->request->data['Article'], compact('content_raw', 'content_html'));

			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash(__('The article has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The article could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Article->read(null, $id);
		}

        $this->__setCurrentBookId($this->request->data['Article']['book_id']);

        $use_texteditor = $this->Auth->user('use_texteditor');
        if ($use_texteditor) {
            $content = $this->request->data['Article']['content_raw'];
        } else {
            $content = $this->request->data['Article']['content_html'];
        }

        $categories = $this->__getCategories();
        $this->set('categories', $categories);
        $this->set('use_texteditor', $use_texteditor);
        $this->set('content', $content);
	}

    protected function getArticleId() {
        if (empty($this->request->params['id']) || !is_numeric($this->request->params['id'])) {
            throw new BadRequestException;
        }

        return $this->request->params['id']; 
    }

    public function beforeRender() {
        if (!empty($this->__currentCardbookId)) {
            $current_cardbook_id = $this->__currentCardbookId;
            $current_cardbook_info = $this->Book->getBookInfo($current_cardbook_id);

            $this->set(compact('current_cardbook_info', 'current_book_id'));
        }
    }

    public function __setCurrentBookId($book_id) {
        if (!empty($book_id)) {
            $this->__currentCardbookId = $book_id;
        }
    }

}
