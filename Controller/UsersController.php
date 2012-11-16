<?php
App::uses('AppAuthController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppAuthController {


    function beforeFilter() {
        parent::beforeFilter();
        if ($this->action == 'join') {
            $this->Auth->allow();
        }
    }

    public function join() {
    }
    
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
			}
		}
	}
	
	public function logout() {
		$redirect = $this->Auth->logout();
		$this->redirect($redirect);
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

    function settings() {

		if ($this->request->is('post')) {
            $updated_settings = $this->request->data;
            $updated_settings['User']['id'] = $this->currentUserId;

			if ($this->User->save($updated_settings)) {
                $this->Session->write('Auth.User.use_texteditor', $updated_settings['User']['use_texteditor']);
				$this->Session->setFlash(__('설정이 업데이트되었습니다.'));
				$this->redirect('/users/settings');
			} else {
				$this->Session->setFlash(__('설정 변경중 오류가 발생하였습니다.'));
			}
		}

        $fields = array('use_texteditor');
        $conditions = array('id' => $this->currentUserId);
        $settings = $this->User->find('first', compact('fields', 'conditions'));
        $this->set('settings', $settings);

    }
}
