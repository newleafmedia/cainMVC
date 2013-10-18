<?php

namespace Cain;

use Cain\db;
use Cain\Config;
use Cain\Request;
use Cain\Session;

class Application
{

	/**
     * File path to config file.
     * @var String
     */
	protected static $config = array();

	/**
     * runtime environment
     * @var String
     */
	protected static $environment = 'production';

	/**
     * Application http request object
     * @var Cain\Request
     */
	protected static $_request;

	/**
     * Session object
     * @var Cain\Session
     */
	protected static $_session;

	/**
     * Application routing
     * @var Cain\Router
     */
	protected static $_router;

	/**
     * Controller object
     * @var Cain\Controller
     */
	protected static $_controller;


	/**
     * Exception object
     * @var Cain\Controller
     */
	public static $exception;

	/**
     * Controller object
     * @var Cain\Controller
     */
	protected $_controllerFile = 'Controller.php';


	/** Contstructor
	 *
	 * Constructor for application. builds and connects all components
	 *
	 * @param (string) (configPath) path to config file
	 * @param (string) (environment) application running environment
	 * @return VOID
	 */
	public function __construct( $configPath, $environment )
	{
		self::$config = $this->loadConfig( $configPath );

		self::$_session = new Session( self::$config );

		self::$environment = $environment;

		self::$_request = new Request();

	}

	/** helper
	 *
	 * load helper
	 *
	 * @return Helper instance
	 */
	public static function helper(string $helper)
	{

	}

	/** model
	 *
	 * load Model
	 *
	 * @return Model instance
	 */
	public static function model(string $model)
	{

	}

	/** controller
	 *
	 * load Controller
	 *
	 * @return Model instance
	 */
	public static function controller(string $model)
	{

	}

	/** Db
	 *
	 * load Cain\Db\Pdo instance
	 *
	 * @return Db instance
	 */
	public static function db()
	{

	}

	/** model
	 *
	 * load Model
	 *
	 * @return Model instance
	 */
	public function loadConfig( $path )
	{
		if(file_exists( $path )) {
			$config = include_once( $path );

			return $config;

		} else {
			throw new \Exception('Config File Not Found.');
		}
	}

	public function loadModules()
	{

	}

	/** run
	 *
	 * Build and run proper controller
	 *
	 * @return VOID
	 */
	public function run()
	{
		$this->route();
	}

	protected function route()
	{
		self::$_router = new Router( self::$_request, self::$config );

		self::$_router->loadController();
		
		$class = self::$_router->getControllerClass();

		$action = self::$_router->getAction();

		self::$_controller = new $class( self::$_session, self::$_request, self::$config, self::$_router);

		self::$_controller->preRender()->setViewScript( $action )
							->$action();
		self::$_controller->render()
							->postRender();


		$finalContent = self::$_controller->getContent();
		echo $finalContent;
	}

	public static function forward( $module = NULL, $controller = NULL, $action = NULL)
	{
		self::$_router = self::$_router->forward( $module, $controller, $action);

		self::$_router->loadController();

		$class = self::$_router->getControllerClass();

		$action = self::$_router->getAction();

		self::$_controller = new $class( self::$_session, self::$_request, self::$config, self::$_router);

		self::$_controller->preRender()->setViewScript( $action )
							->$action();
		self::$_controller->render()
							->postRender();


		$finalContent = self::$_controller->getContent();
		echo $finalContent;
	}

	public static function redirect( $url = "/", $params = array())
	{
		$pString = '';

		if(count($params) > 0){
			$pString = "?".http_build_query($params);
		}



		header("Location: ".BASE_URL . $url . $pString);
	}
}


