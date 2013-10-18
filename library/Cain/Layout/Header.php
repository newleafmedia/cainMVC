<?php

namespace Cain\Layout;

class Header
{

	protected $_title = "";

	protected $_doctype = "";

	protected $_headLinks = "";

	protected $_headScripts = array();

	protected $_footerScripts = array();

	protected $_footerInlineScripts = array();

	protected $_headerInlineScripts = array();

	protected $_meta = array();

	public function __construct()
	{

	}

	public function setTitle( $title )
	{
		$this->_title = $title;

		return $this;
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

	public function addFooterScript( $url, $type = 'text/javascript', $defer = false $attributes = array() )
	{
		$att = ' ';
		foreach($attributes as $key => $value) {
			$att .= $key.'="'.$value.'" ';
		}
		$url = str_replace(BASE_URL, '')
		$_defer = ($defer) ? ' defer="defer"' : '';
		$this->_footerScripts[] = '<script src="'.BASE_URL.$url.'" type="'.$type.'" '.$_defer.' '.$att.'></script>';
		return $this;
	}

	public function addHeaderScript( $url, $type = 'text/javascript', $defer = false, $attributes = array() )
	{
		$att = ' ';
		foreach($attributes as $key => $value) {
			$att .= $key.'="'.$value.'" ';
		}
		$url = str_replace(BASE_URL, '')
		$_defer = ($defer) ? ' defer="defer"' : '';
		$this->_footerScripts[] = '<script src="'.BASE_URL.$url.'" type="'.$type.'" '.$_defer.' '.$att.'></script>';
		return $this;
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

	
}