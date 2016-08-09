<?php
class Controller {
 	const JSON_RESP = 'json_resp';
	protected $_model;
	protected $_controller;
	protected $_action;
	protected $_template;
	protected $_json_resp;

	function __construct($model, $controller, $action, $json_resp='') {
		$this->_controller = $controller;
		$this->_action = $action;
		$this->_model = $model;
		$this->$model =& new $model;
		$this->_template =& new Template($controller,$action);
		$this->_json_resp = $json_resp;
	}

	function set($name,$value) {
		$this->_template->set($name,$value);
	}

	 

}
