<?php

namespace Cain;

use Cain\Cache;
use Cain\Escaper\Escaper;

class View
{
	protected $pathStack = array();

	protected $cache;

	protected $_helpers = array();

	public $escaper;

	protected $_header;

	public function __construct()
	{
		$this->escaper = new Escaper();
	}

	public function addHelper($module, $class)
	{
		if($module && $class){
			$path = BASE_PATH.'/modules/'.ucfirst($module).'/Helpers/'.ucfirst($class).'.php';
			$className = ucfirst($module).'\\Helpers\\'.ucfirst($class);
			include_once($path);
			$this->_helpers[ strtolower($module).'/'.strtolower($class) ] = new $className();
		}
		return $this;
	}

	public function render( $template ) {

		$cache = ($this->cache) ? $this->cache : false;

		$content = false; //load Cache by id

		ob_start( );

		$exists = 0;

		if(!$content){
			if( is_array($this->pathStack) ){
	       		foreach($this->pathStack as $path){
	       			$file = $path . DIRECTORY_SEPARATOR . $template;
	       			if(file_exists($file)){
	       				$exists++;

						include $file;
						break;
					}
				}
			}

			if( $exists === 0 ) {
				throw new Exception($template.' not found in view path. ' . implode(", ", $this->pathStack));
			}

			$content = ob_get_clean( );

			/* $cache->setTags();

			$cache->setId();

			$cache->save(); */
		}

		return $content;
	}

	protected function partial( $file, $module = null, $params = array())
	{
		$view = new View();
		foreach($this->pathStack as $path) {
			$view->addViewPath($path);
		}
		if($module){
			$view->addViewPath( BASE_PATH . '/modules/' . ucfirst($module) .'/Views/');
		}

		foreach($params as $key => $value){
			$view->$key = $value;
		}

		return $view->render($file . '.phtml');
	}

	public function addViewPath( $path ) {
		array_unshift($this->pathStack , $path);
	}


	public function setScriptPath( $path ) {
		$this->pathStack = array($path);
	}

	public function setCache( $cache )
	{
		$this->_cache = $cache;
	}
}