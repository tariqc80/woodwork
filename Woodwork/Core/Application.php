<?php namespace Woodwork\Core;

class Application {

	private static $_config = null;
	private static $_database = null;
	private $_request = null;

	public function __construct( $config ) 
	{
		if ($config)
		{
			self::$_config = $config;
		}
		else 
		{
			throw new \Exception('Invalid configuration.');
		}
	}

	public static function getConfig()
	{
		return self::$_config;
	}

	public static function getBaseUrl()
	{
		return self::$_config->baseurl;
	}

	public static function getDatabase()
	{
		if (!self::$_config)
		{
			throw new \Exception('Configuration not loaded or valid');
		}

		if (!self::$_database)
		{
			$adapterName = __NAMESPACE__ . '\\' .ucfirst( strtolower( self::$_config->adapter ) );
			$adapter = new $adapterName(self::$_config->hostname, self::$_config->username, 
				self::$_config->password, self::$_config->database, (integer) self::$_config->port);

			self::$_database = new Database( $adapter );
		}

		return self::$_database;
	}

	public function run() 
	{
		$this->_request = new Request();

		$controllerName = ucfirst($this->_request->getController()) . 'Controller';
		$action = $this->_request->getAction();

		if (!class_exists($controllerName))
		{
			throw new \Exception("$controllerName Page not found");
		}

		$controller = new $controllerName( $this->_request );

		if (!$controller || !method_exists($controller, $action))
		{
			throw new \Exception('Page not found');
		}
		
		$controller->$action();
	}

}