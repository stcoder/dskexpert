<?php
class IndexController extends Kaipack_Controller_Action
{

    function indexAction()
    {
        $model = new page_model_Table();
        $page = $model->findByUrlKey('index');

        if (!$page) {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        }

        $this->view->headTitle($page->title, 'PREPEND');
        $this->view->headMeta()->setName('description', $page->description);
        $this->view->headMeta()->setName('keywords', $page->keywords);
        $this->view->body = $page->body;

        $article = new Article();
        $this->view->articles = $article->getRandomArticles();

        $photo = new Photo();
        $this->view->photo = $photo->getRandomPhoto();
	}

	function serviceAction()
	{
		$this->setPage('service');
	}

	function adviceAction()
	{
		$this->setPage('advice');
	}

	function articleAction()
	{
		$this->setPage('article');
	}

	function faqAction()
	{
		$this->setPage('faq');
	}

	function ratingContractorsAction()
	{
		$this->setPage('rating-contractors');
	}

	function tenderAction()
	{
		$this->setPage('tender');
	}

	function costOfServicesAction()
	{
		$this->setPage('cost-of-services');
	}

	function contactsAction()
	{
		$this->setPage('contacts');
	}
}