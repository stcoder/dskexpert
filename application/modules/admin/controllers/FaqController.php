<?php
class Admin_FaqController extends Kaipack_Controller_AdminAction
{
	public function newAction()
	{
		$db = new Faq();
		$select = $db->select(array('id', 'username', 'email', 'question', 'isNew', 'created_at'));
		$select->where('isNew = ?', 'yes')->order('created_at DESC');

		$this->view->questions = $db->fetchAll($select);
	}

	public function answerAction()
	{
		if (!$this->getRequest()->isXmlHttpRequest()) {
			throw new Zend_Http_Exception('Request is not ajax');
		}

		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID вопроса');
		}

		if (!$this->_getParam('question')) {
			throw new Zend_Controller_Action_Exception('Вопрос не указан');
		}

		if (!$this->_getParam('answer')) {
			throw new Zend_Controller_Action_Exception('Ответ не указан');
		}

		$db = new Faq();
		$select = $db->select();
		$select->where('isNew = ?', 'yes')->where('id = ?', (int)$this->_getParam('id'));

		$row = $db->fetchRow($select);

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Вопрос не найден');
		}

		$filter = new Zend_Filter_StripTags(array(
				'allowTags' => array('p', 'div', 'br', 'a', 'strong', 'b', 'u', 'em', 'strike', 'sub', 'sup', 'img', 'hr', 'span')
		));

		$row->question = $filter->filter($this->_getParam('question'));
		$row->answer = $filter->filter($this->_getParam('answer'));
		$row->answer_at = date('Y-m-d H:i:s');
		$row->published = 'on';
		$row->isNew = 'no';
		$row->save();

		$config = Config::getInstance();

		$mail = new Zend_Mail('utf-8');
		$mail->setFrom($config->getOption('email-notification'), $config->getOption('title_site'));
		$mail->addTo($row->email);
		$mail->setSubject('Обработка Вашего вопроса');

		$html = '<p>Ваш вопрос был успешно опубликован на нашем <a href="http://stroisvobodno/faq">сайте</a></p>';
		$html .= '<p>Ответ: ' . $row->answer . '</p>';

		$mail->setBodyHtml($html);
		$mail->send();

		$this->_helper->json(array(
			'from' => $row->username,
			'question' => $row->question,
			'answer' => $row->answer
		));
	}
	
	public function editAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID вопроса');
		}

		$db = new Faq();

		$row = $db->find((int)$this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Вопрос не найден');
		}
		
		if ($this->getRequest()->isPost()) {
			if (!$this->_getParam('id')) {
				throw new Zend_Controller_Action_Exception('Не указан ID вопроса');
			}

			if (!$this->_getParam('question')) {
				throw new Zend_Controller_Action_Exception('Вопрос не указан');
			}

			if (!$this->_getParam('answer')) {
				throw new Zend_Controller_Action_Exception('Ответ не указан');
			}
			
			$filter = new Zend_Filter_StripTags(array(
				'allowTags' => array('p', 'div', 'br', 'a', 'strong', 'b', 'u', 'em', 'strike', 'sub', 'sup', 'img', 'hr', 'span')
			));

			$row->question = $filter->filter($this->_getParam('question'));
			$row->answer = $filter->filter($this->_getParam('answer'));
			$row->save();
			
			$this->_redirect('/faq');
		}
		
		$this->view->question = $row;
	}

	public function deleteAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID вопроса');
		}

		$db = new Faq();

		$row = $db->find((int)$this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Вопрос не найден');
		}

		$row->delete();

		if (!$this->getRequest()->isXmlHttpRequest()) {
			$this->_redirect('/faq');
		}
		$this->_helper->json(array(
			'question_delete' => 'ok'
		));
	}

	public function publishedAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID вопроса');
		}

		$db = new Faq();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Вопрос с указанным ID не найден');
		}

		$row->published = 'on';
		$row->save();

		$this->_redirect('/faq');
	}

	public function nopublishedAction()
	{
		if (!$this->_getParam('id')) {
			throw new Zend_Controller_Action_Exception('Не указан ID вопроса');
		}

		$db = new Faq();
		$row = $db->find($this->_getParam('id'))->current();

		if (!$row) {
			throw new Zend_Controller_Action_Exception('Вопрос с указанным ID не найден');
		}

		$row->published = 'off';
		$row->save();

		$this->_redirect('/faq');
	}
}