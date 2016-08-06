<?php
class Model extends DbQuery {
	protected $_model;
 
	function __construct() {
		$this->open_connection(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		$this->_model = get_class($this);
		$this->_table = strtolower($this->_model)."s";
	}

	function __destruct() {
	}
}
