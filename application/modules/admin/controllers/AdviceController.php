<?php
class Admin_AdviceController extends Kaipack_Controller_AdminAction
{
	public function addAction()
	{
		if ($this->getRequest()->isPost()) {
			$this->view->title = $this->_getParam('title');
			$this->view->body = $this->_getParam('body');

			if (!$this->_getParam('title') && $this->_getParam('title') == '') {
				$this->view->error = 'Пожалуйста укажите заголовок совета';
				return;
			}

			if (!$this->_getParam('body') && $this->_getParam('body') == '') {
				$this->view->error = 'Пожалуйста укажите содержимое совета';
				return;
			}

			$db = new Advice();
			$row = $db->createRow();
			$row->title = $this->_getParam('title');
			$row->body = $this->_getParam('body');			
			$row->published_at = ($this->_getParam('published')) ? date('Y-m-d H:i:s') : '';
			$row->published = ($this->_getParam('published')) ? 'on' : 'off';
			$row->created_at = date('Y-m-d H:i:s');
			$row->save();

			$this->_redirect('/advice');
		}
	}

	public function deleteAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID совета');
		}

		$db = new Advice();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Совет с указанным ID не найден');
		}

		$row->delete();

		$this->_redirect('/advice');
	}

	public function editAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID совета');
		}

		$db = new Advice();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Совет с указанным ID не найден');
		}

		$this->view->advice = $row;
	}

	public function saveAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID совета');
		}

		if (!$this->_getParam('title') && $this->_getParam('title') == '') {
			$this->view->error = 'Пожалуйста укажите заголовок совета';
			return;
		}

		if (!$this->_getParam('body') && $this->_getParam('body') == '') {
			$this->view->error = 'Пожалуйста укажите содержимое совета';
			return;
		}

		$db = new Advice();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Совет с указанным ID не найден');
		}

		$row->title = $this->_getParam('title');
		$row->body = $this->_getParam('body');
		$row->published_at = ($this->_getParam('published')) ? date('Y-m-d H:i:s') : '';
		$row->published = ($this->_getParam('published')) ? 'on' : 'off';
		$row->save();

		$this->_redirect('/advice');
	}

	public function publishedAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID совета');
		}

		$db = new Advice();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Совет с указанным ID не найден');
		}

		$row->published_at = date('Y-m-d H:i:s');
		$row->published = 'on';
		$row->save();

		$this->_redirect('/advice');
	}

	public function nopublishedAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID совета');
		}

		$db = new Advice();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Совет с указанным ID не найден');
		}

		$row->published = 'off';
		$row->save();

		$this->_redirect('/advice');
	}
}