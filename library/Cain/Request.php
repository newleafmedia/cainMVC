<?php 

namespace Cain;

class Request
{

	protected $_request;

	protected $_module = "application";

	protected $_controller = "index";

	protected $_action = "index";

	protected $_params = array();

	protected $_post = array();

	protected $_server = array();



	public function __construct()
	{
		$this->_server = $_SERVER;

		$this->_params = ($_GET) ? $_GET : array();

		$this->_post = $_POST;

		return $this;
	}



	public function getPost()
	{
		return $this->_post;
	}

	public function getParams()
	{
		return $this->_params;
	}

	public function isPost()
	{
		return (is_array($this->_post)) ? true : false;
	}

	public function getParam( string $param )
	{
		return (isset($this->_params[$param])) ? $this->_params[$param] : NULL;
	}

	public function getRequestUri()
	{
		return $this->_server['REQUEST_URI'];
	}

	public function getServer()
	{
		return $this->_server;
	}

	public function getIp()
	{

	}

	public function setModule( $module )
	{
		$this->_module = $module;
	}

	public function setContoller( $controller )
	{
		$this->_module = $controller;
	}

	public function setAction( $action )
	{
		$this->_action = $action;
	}

	public function getReferral()
	{

	}
	
	
}