<?php namespace Woodwork\Core;

class Config {

	private $_variables = [];

	public function __construct( $filename ) 
	{
		if ( is_string( $filename ) ) 
		{
			$this->readConfigFromFile( $filename );
		}
	}

	public function readConfigFromFile( $filename ) 
	{
		$success = false;

		if (file_exists($filename))
		{
			$fhandle = @fopen($filename, 'r');
			if ($fhandle) 
			{
				while ($line = fgets($fhandle))
				{
					list($key, $value) = explode('=', $line);
					if ( isset($key) )
					{		
						$this->_variables[strtolower($key)] = trim($value);
					}
				}
			}
			
			fclose($fhandle);
			$sucess = true;
		}

		return $success;
	}

	public function __get( $name ) 
	{
		$value = null;

		if ( isset($this->_variables[$name]) ) 
		{
			$value = $this->_variables[$name];
		}

		return $value;
	}

	public function getArray() 
	{
		return $this->_variables;
	}
}