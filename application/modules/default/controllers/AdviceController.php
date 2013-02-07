<?php
class AdviceController extends Kaipack_Controller_Action
{
	public function indexAction()
	{
		$this->view->headTitle('Полезные советы', 'PREPEND');

		$advices = new Advice();

		$this->view->advices = $advices->getPaginatorRows((int) $this->getRequest()->getParam('page', 1));
	}

    public function showAction()
    {
        $article = new Advice();

        if (!$this->_getParam('advice_id')) {
            $this->_redirect('/advice');
        }

        $row = $article->find((int) $this->_getParam('advice_id'))->current();

        if (!$row) {
            $this->view->error = 'Совет не найден';
            return;
        }
        $this->view->headTitle($row->title, 'PREPEND');
        $this->view->advice = $row;
    }
}