<?php
class Kaipack_View_Helper_Admin_AdviceControl extends Zend_View_Helper_Abstract
{
	public function Admin_AdviceControl($advice)
	{
		$auth = Zend_Auth::getInstance();

		if ($auth->hasIdentity() && $auth->getIdentity()->role === 'admin') {
			$this->view->advice = $advice;
			return $this->view->render('admin/advice.html');
		}
	}
}