<?php
class Config extends Zend_Db_Table_Abstract
{
	protected $_name = 'config';

	protected $_row = null;

	protected static $_instance = null;

	public static function getInstance()
	{
		if (is_null(static::$_instance)) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}

	public function getOption($option)
	{
		if (is_null($this->_row)) {
			$select = $this->select();

			$this->_row = $this->fetchRow($select);
		}

		return $this->_row[$option];
	}
}