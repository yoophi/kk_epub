<?php
App::uses('AppAuthController', 'Controller');
/**
 * Articles Controller
 *
 * @property Article $Article
 */
class MyPhotosController extends AppAuthController {

    public $uses = array('Photo');

    //
    public $allowJsonRequest = array('index', 'add', 'edit', 'delete');

    function index() {
		if ($this->isJsonRequest()) {
			$photos = $this->Photo->find('all', compact('conditions'));
			foreach ($photos as $key => &$value) {
				$value = $value['Photo'];
			}

			$this->set('res', $photos);
			$this->set('_serialize', 'res');
		}
    }

    function simple() {
    }

    function add() {
		if ($this->isJsonRequest()) {
			$data = $this->__getPayLoad();

			try {
				$this->Photo->create();
				$data['user_id'] = $this->currentUserId;
				if ($this->Photo->save(array('Photo' => $data))) {
					$id = $this->Photo->getLastInsertId();
					$photo = $this->Photo->read(null, $id);
					
					if (!empty($photo['Photo'])) {
						$photo = $photo['Photo'];
						$this->set('res', $photo);
						$this->set('_serialize', 'res');
					} else {
						throw new Exception('사진 생성중 에러');
					}
				} else {
					throw new Exception('사진 생성중 에러');
				}
			} catch (Exception $e) {
				$this->set('message', $e->getMessage());
				$this->set('_serialize', array('message'));
			}
		}
    }

    function edit() {
    }

    function delete() {
    	if ($this->isJsonRequest()) {
    		$this->__log(__METHOD__ . __LINE__);
    		$id = $this->request->params['id'];
    		$this->__log($id);
			$this->Photo->id = $id;
    		$this->__log(__METHOD__ . __LINE__);
    		$this->__log($this->request);
			if (!$this->Photo->exists()) {
    		$this->__log(__METHOD__ . __LINE__);
				throw new NotFoundException(__('Invalid photo'));
			}
    		$this->__log(__METHOD__ . __LINE__);
			if ($this->Photo->delete()) {
    		$this->__log(__METHOD__ . __LINE__);
				$this->set('res', true);
			} else {
    		$this->__log(__METHOD__ . __LINE__);
				$this->set('res', false);
			}
    		$this->__log(__METHOD__ . __LINE__);
			$this->set('_serialize', 'res');
		}
    }

	private function __getPayLoad() {
		$payload = FALSE;
		if (isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
			$payload = '';
			$httpContent = fopen('php://input', 'r');
			while ($data = fread($httpContent, 1024)) {
				$payload .= $data;
			}
			fclose($httpContent);
		}

		// check to make sure there was payload and we read it in
		if(!$payload)
			return FALSE;

		// translate the JSON into an associative array
		$obj = json_decode($payload, true);
		return $obj;
	}

	protected function __log() {
		$args = func_get_args();
		if (count($args) == 1) {
			$args = $args[0];
		}               
		$this->log(print_r($args, true), 'rest');
	}                       


}
