<?php
class Contractors extends Zend_Db_Table_Abstract
{
	protected $_name = 'contractors_rating';

	public function counNewQuestions()
	{
		$select = $this->select(array('is_new'));
		$select->where('is_new = ?', 'yes');

		return $this->fetchAll($select)->count();
	}
}