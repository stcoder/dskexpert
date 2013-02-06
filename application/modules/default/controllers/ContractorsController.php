<?php
class ContractorsController extends Kaipack_Controller_Action
{
	public function indexAction()
	{
		$contractors = new Contractors();

		$select = $contractors->select();

		$select->order('published_at DESC');

		$rating = $this->_getParam('rating');

		if (in_array($rating, array('best', 'worst'))) {
			if ($rating == 'best') {
				$select->where('rating >= 3');
			} else {
				$select->where('rating <= 3');
			}
		}

		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) {
			$select->where('published = "yes"');
		}

		$config = new Config();

		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
		$paginator->setItemCountPerPage($config->getOption('count_ratings'));
		$paginator->setPageRange(10);

		$this->view->contractors = $paginator;
		$this->view->is_ajax = $this->getRequest()->isXmlHttpRequest();

		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->_helper->layout()->disableLayout();
			$this->render('items');
		}
	}

	public function addAction()
	{
		$form = new Form_AddRating();

		if ($this->getRequest()->isPost()) {
			if ($form->isValid($_POST)) {
				$model = new Contractors();
				$row = $model->createRow();
				$row->user_name = $form->getValue('name');
				$row->user_email = $form->getValue('email');
				$row->company_name = $form->getValue('company_name');
				$row->comment = $this->_getParam('comment');
				$row->rating = $form->getValue('rating');
				$row->published = 'no';
				$row->is_new = 'yes';
				$row->created_at = date('Y-m-d H:i:s');
				$row->save();

				$config = Config::getInstance();

				$mail = new Zend_Mail('utf-8');
				$mail->setFrom($config->getOption('email-notification'), $config->getOption('title_site'));
				$mail->addTo($config->getOption('email-notification'));
				$mail->setSubject('Новый отзыв');

				$html = '<p>Новый отзыв от: ' . $form->getValue('name') . ' <em>(' . $form->getValue('email') . ')</em></p>';
				$html .= '<p>О фирме: ' . $form->getValue('company_name') . '</p>';
				$html .= '<p>Оценка: ' . $form->getValue('rating') . '</p>';
				$html .= '<p>Отзыв: ' . $this->_getParam('comment') . '</p>';

				$mail->setBodyHtml($html);
				$mail->send();

				$this->view->send = true;
				return;
			}
		}

		$this->view->form = $form;
	}
}