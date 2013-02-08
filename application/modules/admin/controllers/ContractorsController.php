<?php
class Admin_ContractorsController extends Kaipack_Controller_AdminAction
{
	public function newAction()
	{
		$db = new Contractors();
		$select = $db->select(array(
			'id',
			'user_name',
			'user_email',
			'company_name',
			'company_description',
			'comment',
			'rating',
			'is_new',
			'created_at'
		));
		$select->where('is_new = ?', 'yes')->order('created_at DESC');

		$this->view->comments = $db->fetchAll($select);
	}

	public function processAction()
	{
		if (!$this->getRequest()->isXmlHttpRequest()) {
			throw new Zend_Http_Exception('Request is not ajax');
		}

		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID отзыва');
		}

		if (!$this->_getParam('company_name')) {
			throw new Zend_Controller_Action_Exception('Не указано наименование компании');
		}

		/*if (!$this->_getParam('company_description')) {
			throw new Zend_Controller_Action_Exception('Не указано описание компании');
		}*/

		if (!$this->_getParam('comment')) {
			throw new Zend_Controller_Action_Exception('Не указан отзыв');
		}

		$db = new Contractors();
		$select = $db->select();
		$select->where('is_new = ?', 'yes')->where('id = ?', (int)$this->_getParam('id'));

		$row = $db->fetchRow($select);

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Отзыв не найден');
		}

		$filter = new Zend_Filter_StripTags();

		$row->company_name = $filter->filter($this->_getParam('company_name'));
		$row->comment = $filter->filter($this->_getParam('comment'));
		$row->published = 'yes';
		$row->is_new = 'no';
		$row->published_at = date('Y-m-d H:i:s');
		$row->save();

		$config = Config::getInstance();

		$mail = new Zend_Mail('utf-8');
		$mail->setFrom($config->getOption('email-notification'), $config->getOption('title_site'));
		$mail->addTo($row->user_email);
		$mail->setSubject('Обработка Вашего отзыва');

		$html = '<p>Ваш отзыв о фирме ' . $row->company_name . ' был успешно опубликован на нашем <a href="http://stroisvobodno/rating-contractors">сайте</a></p>';

		$mail->setBodyHtml($html);
		$mail->send();

		$this->_helper->json(array(
			'status' => 'ok'
		));
	}

	public function deleteAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID отзыва');
		}

		$db = new Contractors();

		$row = $db->find((int)$this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Отзыв не найден');
		}

		$row->delete();

		if (!$this->getRequest()->isXmlHttpRequest()) {
			$this->_redirect('/rating-contractors');
		}
		$this->_helper->json(array(
			'rating_delete' => 'ok'
		));
	}

	public function publishedAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID отзыва');
		}

		$db = new Contractors();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Отзыв с указанным ID не найден');
		}

		$row->published = 'yes';
		$row->save();

		$this->_redirect('/rating-contractors');
	}

	public function nopublishedAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID отзыва');
		}

		$db = new Contractors();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Отзыв с указанным ID не найден');
		}

		$row->published = 'no';
		$row->save();

		$this->_redirect('/rating-contractors');
	}
	
	public function editAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID отзыва');
		}

		$db = new Contractors();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Отзыв с указанным ID не найден');
		}
		
		$this->view->comment = $row;
		
		if ($this->getRequest()->isXmlHttpRequest()) {
			if (!$this->_getParam('company_name')) {
				throw new Zend_Controller_Action_Exception('Не указано наименование компании');
			}

			if (!$this->_getParam('comment')) {
				throw new Zend_Controller_Action_Exception('Не указан отзыв');
			}

			$filter = new Zend_Filter_StripTags(array(
				'allowTags' => array('p', 'div', 'br', 'a', 'strong', 'b', 'u', 'em', 'strike', 'sub', 'sup', 'img', 'hr', 'span')
			));

			$row->company_name = $filter->filter($this->_getParam('company_name'));
			$row->comment = $filter->filter($this->_getParam('comment'));
			$row->rating = (int) $this->_getParam('rating');
			$row->save();
			
			$this->_helper->json(array(
				'message' => 'Сохранено'
			));
		}
	}
}