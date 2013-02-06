<?php
class Admin_AuthController extends Kaipack_Controller_Action
{
	public function loginAction()
	{
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect('/');
		}
		$form = new Form_AdminLogin();

		if ($this->getRequest()->isPost()) {
			if ($form->isValid($_POST)) {
				$user = new User();
				$auth = Zend_Auth::getInstance();
				$authAdapter = new Zend_Auth_Adapter_DbTable($user->getAdapter(), 'user', 'login', 'password', 'sha1(?)');
                $authAdapter
                        ->setIdentity($form->login->getValue())
                        ->setCredential($form->password->getValue());
                        
                $result = $auth->authenticate($authAdapter);
                if ($result->isValid()) {
                    $storage = new Zend_Auth_Storage_Session();
                    $storage->write($authAdapter->getResultRowObject(null, array('password')));
                    $this->_redirect('/');
                } else {
                    $this->view->errorMessage = 'Неверный логин или пароль.';
                }
			}
		}
		$this->view->form = $form;
	}

	public function logoutAction()
	{
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
	}
}