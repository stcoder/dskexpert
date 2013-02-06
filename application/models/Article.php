<?php
class Article extends Zend_Db_Table_Abstract
{
	protected $_name = 'article';

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
		$paginator->setItemCountPerPage($config->getOption('count_articles'));
		$paginator->setPageRange(10);
		return $paginator;
	}

    public function getRandomArticles()
    {
        /*$query  = $this->getAdapter()->query('SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `article`');
        $offset = $query->fetch();

        $query  = $this->getAdapter()->query('SELECT `title`,`id`,`short_content` FROM `article` LIMIT ' . $offset['offset'] . ', 2');*/
        $query  = $this->getAdapter()->query('SELECT * FROM `article` WHERE id >= (SELECT FLOOR( MAX(id) * RAND()) FROM `article` ) ORDER BY id LIMIT 2');
        $result = $query->fetchAll();

        return $result;
    }
}