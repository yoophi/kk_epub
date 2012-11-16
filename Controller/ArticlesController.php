<?php
App::uses('AppAuthController', 'Controller');
/**
 * Articles Controller
 *
 * @property Article $Article
 */
class ArticlesController extends AppAuthController {


    //public $layout = 'book';
    public $uses = array('Article', 'Book');
    protected $__currentBookId = null;

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Article->recursive = 0;
		$this->set('articles', $articles = $this->paginate());
        //$this->viewClass = 'Json';
        //$this->set('articles', $this->paginate());
        //$this->set('_serialize', array('articles'));
	}


/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}
        $article = $this->Article->read(null, $id);
        $this->__setCurrentBookId($article['Article']['book_id']);
        
		$this->set('article', $article);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
            $this->request->data['Article']['user_id'] = $this->currentUserId;
			$this->Article->create();
			if ($this->Article->save($this->request->data)) {
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

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
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
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}
		if ($this->Article->delete()) {
			$this->Session->setFlash(__('Article deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Article was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

    public function beforeRender() {
        if (!empty($this->__currentBookId)) {
            $current_book_id = $this->__currentBookId;
            $current_book_toc = $this->Book->getBookToc($current_book_id);
            $current_book_info = $this->Book->getBookInfo($current_book_id);

            $this->set(compact('current_book_toc', 'current_book_info', 'current_book_id'));
        }
    }

    public function __setCurrentBookId($book_id) {
        if (!empty($book_id)) {
            $this->__currentBookId = $book_id;
        }
    }

    public function __getCategories() {
        App::uses('Category', 'Model');
        $Category = new Category;
        $conditions = array('Category.user_id', $this->currentUserId);
        $order = array('Category.order ASC');
        $categories = array(0 => '- - -') + $Category->find('list', compact('conditions', 'order'));

        return $categories;
    }
}
