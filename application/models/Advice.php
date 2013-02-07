<?php
class Advice extends Zend_Db_Table_Abstract
{
	protected $_name = 'advice';

	public function getPaginatorRows ($pageNumber = 1)
	{
		$select = $this->select();
		$select->order('published_at DESC');

		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) {
			$select->where('published = "on"');
		}

		$config = new Config();

		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		$paginator->setCurrentPageNumber($pageNumber);
		$paginator->setItemCountPerPage($config->getOption('count_advices'));
		$paginator->setPageRange(10);
		return $paginator;
	}

    public function getRandom()
    {
        $query  = $this->getAdapter()->query('SELECT * FROM `advice` WHERE id >= (SELECT FLOOR( MAX(id) * RAND()) FROM `advice` ) ORDER BY id LIMIT 2');
        $result = $query->fetchAll();

        return $result;
    }
}