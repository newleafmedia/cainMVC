<?php

namespace Cain\Layout;

class Header
{

	/**
     * Html title
     * @var String
     */
	protected $_title = "";

	/**
     * Html docType
     * @var String
     */
	protected $_doctype = "<!DOCTYPE html>";

	/**
     * Html head links
     * @var array
     */
	protected $_headLinks = array();

	/**
     * Html head Javascript links
     * @var array
     */
	protected $_headScripts = array();

	/**
     * Html footer Javascript links
     * @var array
     */
	protected $_footerScripts = array();

	/**
     * Html header Javascript inline scripts
     * @var array
     */
	protected $_footerInlineScripts = array();

	/**
     * Html footer Javascript inline scripts
     * @var array
     */
	protected $_headerInlineScripts = array();

	/**
     * Html head meta tags
     * @var array
     */
	protected $_meta = array();

	/**
     * Site Config
     * @var Array
     */
	protected $_config;

	public function __construct( $config )
	{
		$this->_config = $config;

		if(isset($config['header'])){
			foreach($config['header'] as $key=>$value){
				$_key = '$_'.$key;
				$this->$_key = $value;
			}
		}
	}

	public function setTitle( $title )
	{
		$this->_title = $title;

		return $this;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function appendTitle( $title )
	{
		$this->_title .= $this->_title . $title;

		return $this;
	}

	public function prependTitle( $title )
	{
		$this->_title .= $title . $this->_title;

		return $this;
	}

	public function addFooterScript( $url, $type = 'text/javascript', $defer = false, $attributes = array() )
	{
		$att = ' ';
		foreach($attributes as $key => $value) {
			$att .= $key.'="'.$value.'" ';
		}
		$url = str_replace(BASE_URL, '');
		$_defer = ($defer) ? ' defer="defer"' : '';
		$this->_footerScripts[] = '<script src="'.BASE_URL.$url.'" type="'.$type.'" '.$_defer.' '.$att.'></script>';
		return $this;
	}

	public function getFooterScripts()
	{
		return implode("\n", $this->_footerScripts);
	}

	public function addHeaderScript( $url, $type = 'text/javascript', $defer = false, $attributes = array() )
	{
		$att = ' ';
		foreach($attributes as $key => $value) {
			$att .= $key.'="'.$value.'" ';
		}
		$url = str_replace(BASE_URL, '');
		$_defer = ($defer) ? ' defer="defer"' : '';
		$this->_footerScripts[] = '<script src="'.BASE_URL.$url.'" type="'.$type.'" '.$_defer.' '.$att.'></script>';
		return $this;
	}

	public function getHeaderScripts()
	{
		return implode("\n", $this->_headScripts);
	}

	public function addHeadLink( $src, $rel="stylesheet", $type = "text/css", $media = "all", $attributes = array())
	{
		$att = ' ';
		foreach($attributes as $key => $value) {
			$att .= $key.'="'.$value.'" ';
		}
		$this->_headLinks[] = '<link rel="'.$rel.'" href="'.$src.'" type="'.$type.'" media="'.$media.'" '.$att.'>';
		return $this;
	}

	public function getHeadLinks()
	{
		return implode("\n", $this->_headLinks);
	}

	public function getDoctype()
	{
		return $this->_doctype;
	}

	public function getMetaTags()
	{
		$meta = '';

	}

	
}