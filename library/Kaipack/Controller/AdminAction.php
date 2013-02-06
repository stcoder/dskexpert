<?php

class Kaipack_Controller_AdminAction extends Kaipack_Controller_Action
{
	protected $_auth = null;
	public function init()
	{
		$this->_auth = Zend_Auth::getInstance();

		if (!$this->_auth->hasIdentity() && $this->_auth->getIdentity()->role !== 'admin') {
			$this->_redirect('/admin');
		}
	}
}