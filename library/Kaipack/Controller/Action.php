<?php
class Kaipack_Controller_Action extends Zend_Controller_Action
{
	public function setPage($url_key = 'home')
	{
		$model = new page_model_Table();
		$page = $model->findByUrlKey($url_key);

		if (!$page) {
			throw new Zend_Controller_Action_Exception('Page not found', 404);
		}

		$this->view->headTitle($page->title, 'PREPEND');
		$this->view->headMeta()->setName('description', $page->description);
		$this->view->headMeta()->setName('keywords', $page->keywords);
		$this->view->body = $page->body;

		$viewRenderer = $this->_helper->getHelper('viewRenderer');

		$viewRenderer->renderScript('index/page.html');
	}

	public function layoutOff()
	{
		$this->_helper->layout()->disableLayout();
	}

	public function viewOff()
	{
		$this->_helper->viewRenderer->setNoRender();
	}
}