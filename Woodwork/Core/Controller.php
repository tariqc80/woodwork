<?php namespace Woodwork\Core;


class Controller {
	
	private $_request = null;
	private $_flashMessage = null;

	public function __construct( $request ) 
	{

		if (!$request)
		{
			throw new \Exception('Invalid request object.');
		}

		$this->_request = $request;

	}

	public function getRequest()
	{
		return $this->_request;
	}

	public function getBaseUrl()
	{
		return Application::getBaseUrl();
	}

	public function redirect( $route = '' )
	{

		$base = Application::getBaseUrl();
		header("Location: $base/$route");
		exit;
	}

	public function getFlashMessage()
	{
		$session = &$this->_request->getSession();
		$msg = isset($session['flash_message']) ? $session['flash_message'] : null;
		unset($session['flash_message']);

		return $msg;
	}

	public function setFlashMessage( $msg )
	{
		$session = &$this->_request->getSession();
		$session['flash_message'] = $msg;
	}

	/*
		Empty index action 	
	*/
	public function index()
	{

	}

	public function render ( $view )
	{

		$viewpath = __DIR__ . '/../Views/' . $view . '.php';

		if (!file_exists($viewpath))
		{
			throw new \Exception('View file not found.');
		}

		include ($viewpath);
	}

}