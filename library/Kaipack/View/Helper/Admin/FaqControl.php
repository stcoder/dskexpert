<?php
class Kaipack_View_Helper_Admin_FaqControl extends Zend_View_Helper_Abstract
{
	public function Admin_FaqControl($question)
	{
		$auth = Zend_Auth::getInstance();

		if ($auth->hasIdentity() && $auth->getIdentity()->role === 'admin') {
			$this->view->question = $question;
			return $this->view->render('admin/question.html');
		}
	}
}