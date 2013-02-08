<?php
class Admin_PageController extends Kaipack_Controller_AdminAction
{
	public function editAction()
	{
		if (!$this->_getParam('page')) {
			throw new Zend_Controller_Action_Exception('Страница не выбрана');
		}
		$model = new page_model_Table();
		$page = $model->findByUrlKey($this->_getParam('page'));

		if (!$page) {
			throw new Zend_Controller_Action_Exception('Page not found', 404);
		}

		$this->view->page = $page;
	}

	public function saveAction()
	{
		if (!$this->_getParam('page')) {
			throw new Zend_Controller_Action_Exception('Страница не выбрана');
		}
		$model = new page_model_Table();
		$page = $model->findByUrlKey($this->_getParam('page'));

		if (!$page) {
			throw new Zend_Controller_Action_Exception('Page not found', 404);
		}

		// если пришел заголовок страницы
		$filter = new Zend_Filter_StripTags();
		$title = $filter->filter($this->_getParam('title'));
		$page->title = $title;

		$keywords = $filter->filter($this->_getParam('keywords'));
		$page->keywords = $keywords;

		$description = $filter->filter($this->_getParam('description'));
		$page->description = $description;

		$page->body = $this->_getParam('redactor_content');

		$page->save();
		$this->_redirect('/' . $this->_getParam('page'));
	}
}