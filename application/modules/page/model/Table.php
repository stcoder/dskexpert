<?php
class Page_Model_Table extends Zend_Db_Table_Abstract
{
    protected $_name = 'page';

    public function findById($id)
    {
        $validator = new Zend_Validate_Digits();
        if (!$validator->isValid($id)) {
            throw new Zend_Db_Table_Exception('Used id is not int');
        }

        $row = $this->find($id)->current();

        if (!$row) {
            throw new Zend_Db_Table_Exception("Page id $id is not found");
        }

        return $row;
    }

    public function findByUrlKey($key)
    {
        $validator = new Zend_Validate_Regex('/[a-z0-9_-]+/');
        if (!$validator->isValid($key)) {
            throw new Zend_Db_Table_Exception('Used url key is not correct');
        }

        $select = $this->select()->where('url_key = ?', $key);
        $row = $this->fetchRow($select);

        if (!$row) {
            throw new Zend_Db_Table_Exception("Page url key '$key' is not found");
        }

        return $row;
    }
}