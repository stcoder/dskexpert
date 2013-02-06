<?php
class Kaipack_View_Helper_Admin_ArticleControl extends Zend_View_Helper_Abstract
{
	public function Admin_ArticleControl($article)
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity() && $auth->getIdentity()->role === 'admin') {
			$this->view->article = $article;
			return $this->view->render('admin/article.html');
		}
	}
}