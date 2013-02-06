<?php

class Admin_ArticleController extends Kaipack_Controller_AdminAction
{
	public function addAction()
	{
		if ($this->getRequest()->isPost()) {
			$this->view->title = $this->_getParam('title');
			$this->view->body = $this->_getParam('body');

			if (!$this->_getParam('title') && $this->_getParam('title') == '') {
				$this->view->error = 'Пожалуйста укажите заголовок статьи';
				return;
			}

			if (!$this->_getParam('body') && $this->_getParam('body') == '') {
				$this->view->error = 'Пожалуйста укажите содержимое статьи';
				return;
			}
			
			$body = $this->_getParam('body');
			
			$length = strpos($body, '<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>');

			if ($length) {
				$body = substr(substr($body, 0, $length), 0, $length);
			}

			$db = new Article();
			$row = $db->createRow();
			$row->title = $this->_getParam('title');
			$row->short_content = $body;
			$row->full_content = $this->_getParam('body');			
			$row->published_at = ($this->_getParam('published')) ? date('Y-m-d H:i:s') : '';
			$row->published = ($this->_getParam('published')) ? 'on' : 'off';
			$row->created_at = date('Y-m-d H:i:s');
			$row->save();

			$this->_redirect('/article');
		}
	}
	
	public function editAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID статьи');
		}

		$db = new Article();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Статья с указанным ID не найден');
		}

		$this->view->article = $row;
		
		if ($this->getRequest()->isPost()) {
			if (!$this->_getParam('title') && $this->_getParam('title') == '') {
				$this->view->error = 'Пожалуйста укажите заголовок статьи';
				return;
			}

			if (!$this->_getParam('body') && $this->_getParam('body') == '') {
				$this->view->error = 'Пожалуйста укажите содержимое статьи';
				return;
			}
			
			$body = $this->_getParam('body');
			
			$length = strpos($body, '<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>');

			if ($length) {
				$body = substr(substr($body, 0, $length), 0, $length);
			}
			
			$row->title = $this->_getParam('title');
			$row->short_content = $body;
			$row->full_content = $this->_getParam('body');			
			$row->published_at = ($this->_getParam('published')) ? date('Y-m-d H:i:s') : '';
			$row->published = ($this->_getParam('published')) ? 'on' : 'off';
			$row->save();

			$this->_redirect('/article-' . $this->_getParam('id'));
		}
	}
	
	public function publishedAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID статьи');
		}

		$db = new Article();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Статья с указанным ID не найден');
		}

		$row->published_at = date('Y-m-d H:i:s');
		$row->published = 'on';
		$row->save();

		$this->_redirect('/article');
	}
	
	public function nopublishedAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID статьи');
		}

		$db = new Article();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Статья с указанным ID не найден');
		}
		
		$row->published = 'off';
		$row->save();

		$this->_redirect('/article');
	}
	
	public function deleteAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID статьи');
		}

		$db = new Article();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Статья с указанным ID не найден');
		}

		$row->delete();

		$this->_redirect('/article');
	}
}