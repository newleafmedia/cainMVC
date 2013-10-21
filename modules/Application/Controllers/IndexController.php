<?php

namespace Application\Controllers;


use Cain\Controller;


class IndexController extends Controller
{
	public function index()
	{
		$this->getHeader()->setTitle('Testing');
		$this->getHeader()->prependTitle('Testing | ');
	}
}