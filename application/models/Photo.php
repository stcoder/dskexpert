<?php

class Photo extends Zend_Db_Table_Abstract
{
    protected $_name = 'photos';

    public function getPaginatorRows ($pageNumber = 1)
    {
        $select = $this->select();
        $select->order('created_at DESC');

        $config = new Config();

        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($config->getOption('count_photos'));
        $paginator->setPageRange(10);
        return $paginator;
    }

    public function getRandomPhoto()
    {
        $query  = $this->getAdapter()->query('SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `photos`');
        $offset = $query->fetch();

        $query  = $this->getAdapter()->query('SELECT `id`,`url`, `description` FROM `photos` LIMIT ' . $offset['offset'] . ', 1');
        $result = $query->fetch();

        return $result;
    }
}