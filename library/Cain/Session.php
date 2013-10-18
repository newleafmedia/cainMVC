<?php

namespace Cain;

use Cain\Config;

class Session
{

	protected $session;

	protected $config;

	protected $lifetime = 3600;
	

	public function __construct( $config )
	{
		//if($config instanceof Config) {
			$this->config = $config; 
		//}
	}

	public function start()
	{

	}

	public function destroy()
	{

	}

	public function set( string $var )
	{

	}

	public function get( string $var )
	{

	}
	
}