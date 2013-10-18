<?php

namespace Application\Controllers;


use Cain\Controller;
use Cain\Application;


class ErrorController extends Controller
{
	public function index()
	{
		$this->view->exception = Application::$exception;

		switch($this->view->exception->getCode())
		{
			case 404:
				$this->view->message = 'Page not found.';
				break;
			default:
				$this->view->message = 'An error occured.';
		}
		
	}
}