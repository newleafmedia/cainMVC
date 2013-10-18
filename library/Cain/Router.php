<?php 

namespace Cain;

class Router
{
	protected $_module = "application";

	protected $_controller = "index";

	protected $_action = "index";

	protected $_params = array();

	protected $_config = array();

	protected $_controllerClass;

	public function __construct( Request $request, $config)
	{
		$this->_config = $config;

		$this->parseRoute( $request );

		return $this;
	}

	public function parseRoute( $request )
	{

		$server = $request->getServer();

		$url = parse_url( $server['REQUEST_URI'] );

		$paths = array_values(array_filter(explode("/", $url['path'])));

		$pCount = 0;
		$key = '';

		/*if(isset($paths[0]) && $paths[0] == "index.php"){
			unset($paths[0]);
			$paths = array_values($paths);
		}*/

		if(isset($paths[0]) && !isset($this->_config['modules'][ strtolower($paths[0]) ])){
			array_unshift($paths, 'application');
			$paths = array_values($paths);
		}

		$array = array();
		foreach($paths as $i => $path){
			if(count($paths) )
			if($i < 4){
				switch( $i )
				{
					case 0:
							$this->_module = ($path) ? $path : 'application';
						break;
					case 1:
						$this->_controller = ($path) ? $path : 'index';
						break;
					case 2:
						$this->_action = ($path) ? $path : 'index';
						break;
					default:
						
				}
			} else {
				if($pCount === 0) {
					$key = $path;
					$pCount++;
				} else {
					$array[ $key ] = $path;
					$key = '';
					$pCount = 0;
				}
			}
		}

		$this->_params = $array;
	}

	public function setModule( $module )
	{
		$this->_module = $module;
		return $this;
	}

	public function setController( $controller )
	{
		$this->_controller = $controller;
		return $this;
	}

	public function setAction( $action )
	{
		$this->_action = $action;
		return $this;
	}

	public function getModule()
	{
		return $this->_module;
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function getAction()
	{
		return $this->_action;
	}

	public function getParams()
	{
		return $this->_params;
	}

	public function getControllerClass()
	{
		return '\\' . ucfirst($this->getModule()).'\\Controllers\\'.$this->_controllerClass;
	}

	public function forward( $module = NULL, $controller = NULL, $action = NULL )
	{
		if($module){
			$this->setModule($module);
		}
		if($controller){
			$this->setController($controller);
		}
		if($action){
			$this->setAction($action);
		}

		$this->loadController();

		return $this;
	}



	public function loadController()
	{
		$module = ucfirst($this->getModule());

		if(!isset($this->_config['modules'][ strtolower($module) ])){
			throw new Exception('Module not found', 404);
		}


		$modulePath = $this->_config['modules'][ strtolower($module) ];

		$this->_controllerClass = ucfirst($this->getController().'Controller');
		
		$path = $modulePath . DIRECTORY_SEPARATOR  . 'Controllers'. DIRECTORY_SEPARATOR . $this->_controllerClass . '.php';


		if(!file_exists($path)){
			throw new Exception('Controller not found', 404);
		}

		include_once($path);
	}
}