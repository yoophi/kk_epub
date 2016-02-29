<?php
App::uses('AppAuthController', 'Controller');
/**
 * Articles Controller
 *
 * @property Photo $Photo
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
    		$id = $this->request->params['photo_id'];
			$this->Photo->id = $id;
            $this->__log(print_r($this->request, true));
			if (!$this->Photo->exists()) {
				throw new NotFoundException(__('Invalid photo'));
			}
			if ($this->Photo->delete()) {
				$this->set('res', true);
			} else {
				$this->set('res', false);
			}
			$this->set('_serialize', 'res');
		}
    }


	protected function __log() {
		$args = func_get_args();
		if (count($args) == 1) {
			$args = $args[0];
		}               
		$this->log(print_r($args, true), 'rest');
	}                       


}
