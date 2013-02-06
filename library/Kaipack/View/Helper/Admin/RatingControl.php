<?php
class Kaipack_View_Helper_Admin_RatingControl extends Zend_View_Helper_Abstract
{
	public function Admin_RatingControl($contractors)
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity() && $auth->getIdentity()->role === 'admin') {
			$this->view->options = $contractors;
			return $this->view->render('admin/rating.html');
		}
	}
}