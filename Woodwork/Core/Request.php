<?php namespace Woodwork\Core;

class Request {

	private $_post = null;
	private $_get = null;
	
	private $_route = null;
	private $_controller = null;
	private $_action = null;
	private $_session = null;
	private $_user = null;

	public function __construct()
	{
		$this->_get = array();
		$this->_post = $_POST;

		if ( isset($_GET['r']) ) 
		{
			$this->_route = $_GET['r'];
			unset( $_GET['r'] );

			$parts = explode('/', $this->_route);

			$this->_controller = (isset($parts[0])) ? $parts[0] : null;
			$this->_action = (isset($parts[1])) ? $parts[1] : null;
			
			$p_count = count($parts);
			for($i = 2; $i < $p_count; $i += 2)
			{
				$this->_get[ $parts[$i] ]  = (isset($parts[$i+1])) ? $parts[$i+1] : null;
			}
		}

		foreach ($_GET as $key => $value)
		{
			$this->_get[ $key ] = $value;
		}

		session_start();


		$this->_session = &$_SESSION;
	}


	public function getController() 
	{
		return (!empty($this->_controller)) ? $this->_controller : 'index';
	}

	public function getAction() 
	{
		return (!empty($this->_action)) ? $this->_action : 'index';
	}

	public function getGetParams()
	{
		return $this->_get;
	}
	
	public function getPostParams()
	{
		return $this->_post;
	}

	public function &getSession()
	{
		return $this->_session;
	}

	public function setUser( $user )
	{
		$this->_user = $user;
		$this->_session['user'] = $user;
	}

	public function getUser()
	{
		if ($this->_user === null)
		{
			if (isset($this->_session['user']))
			{
				$this->_user = $this->_session['user'];
			}
		}

		return $this->_user;
	}

}

