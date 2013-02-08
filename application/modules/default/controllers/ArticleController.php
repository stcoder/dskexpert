<?php

class ArticleController extends Kaipack_Controller_Action
{
	public function indexAction()
	{
		$this->view->headTitle('Статьи', 'PREPEND');

		$articles = new Article();

		$this->view->articles = $articles->getPaginatorRows((int) $this->getRequest()->getParam('page', 1));
	}
	
	public function showAction()
	{
		$article = new Article();
		
		if (!$this->_getParam('article_id')) {
			$this->_redirect('/article');
		}
		
		$row = $article->find((int) $this->_getParam('article_id'))->current();
		
		if (!$row) {
			$this->view->error = 'Статья не найдена';
			return;
		}
		$this->view->headTitle($row->title, 'PREPEND');
		$this->view->article = $row;
	}
}