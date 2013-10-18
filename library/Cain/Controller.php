<?php

namespace Cain;

use Cain\View;
use Cain\Template;
use Cain\Session;
use Cain\Request;

class Controller
{

	/**
     * Controller instance of session
     * @var Cain\Session
     */
	protected $_session;



	/**
     * Controller instance of session
     * @var Cain\Session
     */
	protected $_config;

	protected $_moduleConfig;

	/**
     * Controller instance of view
     * @var Cain\View
     */
	protected $view;

	/**
     * Controller instance of Layout
     * @var Cain\Layout
     */
	protected $layout;

	/**
     * Controller instance of Request
     * @var Cain\SRequest
     */
	protected $_request;

	protected $_router;

	/**
     * Allow controller to render a template view. use false for data return;
     * @var BOOL
     */
	protected $_renderView = true;

	/**
     * minimum role of user to view controller
     * @var INT
     */
	protected $_minRole = 0;

	/**
     * Rendered html from layout and View
     * @var mixed|String
     */
	protected $_content;

	/**
     * path to layout phtml
     * @var String
     */
	protected $_layoutPath;

	protected $_viewScript;

	/** Contstructor
	 *
	 * builds view from request, selects proper action, and populates session and request
	 *
	 * @param (array) (session) browser session information
	 * @param (array) (request) request information REST
	 * @return VOID
	 */
	public function __construct( Session $session, Request $request, $config, Router $router )
	{
		$this->_config = $config;

		$this->_router = $router;

		if( $session instanceof Session && $request instanceof Request ){
			$this->_request = $request;
			$this->_session = $session;
		} else {
			throw new Exception('No data to parse for application');
		}

		$this->view = new View();
		$this->layout = new Layout();

		$this->view->request = $this->_request;
		$this->view->session = $this->_session;

		$this->layout->request = $this->_request;
		$this->layout->session = $this->_session;

		$this->layout->setScriptPath( $this->_config['layout']);


		$viewPath = BASE_PATH . '/modules/' . ucfirst($this->_router->getModule()) .'/Views/'. strtolower($this->_router->getController());
		$this->view->addViewPath($viewPath);

		if(isset($this->_config['viewHelpers'])){
			foreach($this->_config['viewHelpers'] as $helper){
				$this->view->addHelper($helper['module'], $helper['class']);
				$this->layout->getView()->addHelper($helper['module'], $helper['class']);
			}
		}
	}

	public function setViewScript( $name )
	{
		$this->_viewScript = $name.'.phtml';
		return $this;
	}

	/** preRender
	 *
	 * Method before html is rendered
	 *
	 * @return VOID
	 */
	public function preRender()
	{
		$module = $this->_router->getModule();
		$helpers = $this->_config['loadedModules'][strtolower($module)]['viewHelpers'];


		if(is_array($helpers)){
			foreach($helpers as $helper){
				if(isset($helper['module'])){
					$this->view->addHelper($helper['module'], $helper['class']);
					$this->layout->getView()->addHelper($helper['module'], $helper['class']);
				}
			}
		}
		return $this;
	}

	/** render
	 *
	 * render html template
	 *
	 * @return VOID
	 */
	public function render()
	{
		if($this->_renderView){
			$contentKey = $this->layout->getContentKey();
			



			$this->layout->$contentKey = $this->view->render( $this->_viewScript );

			$this->_content = $this->layout->render();
		}

		return $this;
	}

	public function getContent()
	{
		return $this->_content;
	}

	/** postRender
	 *
	 * After html render
	 *
	 * @return VOID
	 */
	public function postRender()
	{
		return $this;
	}

	/** getRequest
	 *
	 * get server request object
	 *
	 * @return (array)
	 */
	protected function getRequest()
	{
		return $this->_request;
	}
}