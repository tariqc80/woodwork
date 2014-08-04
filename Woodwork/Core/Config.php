<?php namespace Woodwork\Core;

class Config {

	private $_variables = [];

	public function __construct( $filename ) 
	{
		if ( !is_string( $filename ) ) 
		{
			throw new \Exception( 'Configuration filename must be a string.' );
		}
	
		if ( !file_exists( $filename ) )
		{
			throw new \Exception( "Configuration file '$filename' not found!" );
		}

		$this->readConfigFromFile( $filename );
	}

	public function readConfigFromFile( $filename ) 
	{
		$success = false;

		if ( file_exists($filename) )
		{
			$fhandle = @fopen($filename, 'r');
			if (!$fhandle)
			{
				throw new \Exception( 'Could not open configuration file for reading.' );
			} 

			while ($line = fgets($fhandle))
			{
				if ( preg_match('/^[^=]*=[^=]*$/', $line) )
				{
					list($key, $value) = explode('=', $line);
					if ( isset($key) )
					{		
						$this->_variables[strtolower($key)] = trim($value);
					}
				}
				else
				{
					fclose($fhandle);
					throw new \Exception( 'Configuration file provided is not formatted correctly.' );
				}
			}
			
			fclose($fhandle);
		}
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