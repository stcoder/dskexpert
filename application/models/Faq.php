<?php
class Faq extends Zend_Db_Table_Abstract
{
	protected $_name = 'faq';

	public function counNewQuestions()
	{
		$select = $this->select(array('isNew'));
		$select->where('isNew = ?', 'yes');

		return $this->fetchAll($select)->count();
	}

	public function getPublishedQuestion()
	{
		$select = $this->select(array('id', 'question', 'answer', 'created_at', 'answer_at', 'published', 'isNew'));
		$select->where('published = ?', 'on')
			->where('isNew = ?', 'no')
			->order('answer_at DESC')
			->where('answer != ""');

		return $this->fetchAll($select);
	}

	public function getPaginatorRows($pageNumber = 1)
	{
		$select = $this->select(array('id', 'question', 'answer', 'created_at', 'answer_at', 'published', 'isNew'));
		$select->where('isNew = ?', 'no')
			->order('answer_at DESC')
			->where('answer != ""');

		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) {
			$select->where('published = "on"');
		}

		$config = new Config();

		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		$paginator->setCurrentPageNumber($pageNumber);
		$paginator->setItemCountPerPage($config->getOption('count_questions'));
		$paginator->setPageRange(10);
		return $paginator;
	}
}