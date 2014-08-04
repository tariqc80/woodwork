<?php namespace Woodwork\Controllers;

class IndexController extends \Woodwork\Core\Controller {

	public function index () 
	{	
		$this->render('Index/index');
	}

}