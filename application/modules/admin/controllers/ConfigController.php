<?php
class Admin_ConfigController extends Kaipack_Controller_AdminAction
{
	public function indexAction()
	{
		$config = new Config();

		$this->view->config = $config->fetchRow();
	}

	public function siteAction()
	{
		if ($this->getRequest()->isPost()) {
			$title = $this->_getParam('title');
			$count_advices = $this->_getParam('advice');
			$count_questions = $this->_getParam('faq');
			$count_ratings = $this->_getParam('rating');

			if (!$title) {
				$title = 'Служба заказчика';
			}

			if (!$count_advices || $count_advices <= 1) {
				$count_advices = 10;
			}

			if (!$count_questions || $count_questions <= 1) {
				$count_questions = 10;
			}

			if (!$count_ratings || $count_ratings <= 1) {
				$count_ratings = 10;
			}

			$db = new Config();
			$row = $db->fetchRow();

			$row->title_site = $title;
			$row->count_advices = $count_advices;
			$row->count_questions = $count_questions;
			$row->count_ratings = $count_ratings;
			$row->save();
		}

		$this->_redirect('/admin/config');
	}

	public function mailAction()
	{
		if ($this->getRequest()->isPost()) {
			$db = new Config();
			$row = $db->fetchRow();

			$row['smtp-server'] = $this->_getParam('smtp-server');
			$row['smtp-login'] = $this->_getParam('smtp-login');
			$row['smtp-password'] = $this->_getParam('smtp-password');
			$row['email-notification'] = $this->_getParam('email');
			$row->save();
		}

		$this->_redirect('/admin/config');
	}

	public function passwordAction()
	{
		if ($this->getRequest()->isPost()) {
			$db = new User();
			$row = $db->fetchRow();

			$oldPassword = sha1($this->_getParam('old-password'));

			if ($oldPassword !== $row->password) {
				$this->view->password_error = 'Вы указали не верный пароль';
				$this->_forward('index');
				return;
			}

			if (!$this->_getParam('new-password') || strlen($this->_getParam('new-password')) < 3) {
				$this->view->password_error = 'Пожалуйста укажите новый пароль. Пароль должен содержать не менее 3-х символов';
				$this->_forward('index');
				return;
			}

			$row->password = sha1($this->_getParam('new-password'));
			$row->save();
			$this->_redirect('/admin/config');
		}

		$this->_redirect('/admin/config');
	}
}