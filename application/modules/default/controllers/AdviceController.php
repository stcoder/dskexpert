<?php
class AdviceController extends Kaipack_Controller_Action
{
	public function indexAction()
	{
		$this->view->headTitle('Полезные советы', 'PREPEND');

		$advices = new Advice();

		$this->view->advices = $advices->getPaginatorRows((int) $this->getRequest()->getParam('page', 1));
	}
}