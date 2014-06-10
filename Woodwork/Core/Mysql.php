<?php namespace Woodwork\Core;

class Mysql implements DbAdapter {
	
	private $_conn = null;

	public function __construct($hostname, $username, $password, $database, $port = 3306)
	{
		$this->connect($hostname, $username, $password, $database, $port);
	}

	public function connect( $hostname, $username, $password, $database, $port = 3306)
	{
		$this->_conn = new \PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);

		if (!$this->_conn) {
			throw new \Exception("Could not establish connection to mysql database. :O");
		}
	}

	public function close()
	{
		if ($this->_conn)
		{
			$this->_conn->close();
		}
	}

	public function execute_query( $query, $values = array() )
	{
		$result = false;
		$stmt = $this->_conn->prepare( $query );

		if (!empty($values))
		{				
			foreach( $values as $key => $_value )
			{			
				if ( is_integer($_value) )
				{
					$type = \PDO::PARAM_INT;
				}
				elseif ( is_bool($_value) )
				{
					$type = \PDO::PARAM_BOOL;
				}
				elseif ( is_null($_value) )
				{
					$type = \PDO::PARAM_NULL;
				}
				else
				{	
					$type = \PDO::PARAM_STR;
				}

				if (is_int($key))
				{
					++$key;
				}

				$stmt->bindValue($key, $_value, $type);
			}
		}					

		if ($stmt->execute())
		{
			$result = $stmt->fetchAll( \PDO::FETCH_ASSOC );
		}
		else
		{
			throw new \Exception( implode(' - ', $stmt->errorInfo()) );
		}
		
		return $result;
	}

	public function addFiltersToQuery( &$query, $filters )
	{
		if (!empty($filters))
		{
			$query .= " WHERE ";
			
			$count = count($filters);
			$types = '';
			foreach ($filters as $column => $value)
			{
				$query .= "$column = ?";		
				if (--$count != 0)
				{
					$query .= ' AND ';
				}
			}
		}
	}	

	public function query( $table, $filters )
	{
		if (!$this->_conn) 
		{
			throw new \Exception('Mysql connection is bad. :(');
		}

		if (empty($table))
		{
			throw new \Exception('Table name is not valid. :/');
		}

		$return_array = array();

		$query = "SELECT * FROM $table";
		$this->addFiltersToQuery( $query, $filters );
		$result = $this->execute_query( $query, array_values($filters));
	
		return $result;
	}


	public function insert( $table, $fields )
	{
		if (!$this->_conn) 
		{
			throw new \Exception('Mysql connection is bad. :(');
		}

		if (empty($table))
		{
			throw new \Exception('Table name is not valid. :/');
		}

		$query = "INSERT INTO `$table` (`";

		$query .= implode('`,`', array_keys($fields));
		$query .= '`) VALUES (';

		$count = count($fields);
		$types = '';
		foreach ( $fields as $name => $value )
		{
			$query .= "?";

			if (--$count != 0)
			{
				$query .= ', ';
			}
		}
		$query .= ')';
					
		$return = false;	
		if ($this->execute_query( $query, array_values($fields) ) !== false)
		{
			// $lastInsertId = $this->_conn->lastInsertId();
			$return = $this->_conn->lastInsertId();
		}

		return $return;
	}

	// public function update( $table, $filters = array(), $fields = array() )
	// {
	// 	if (!$this->_conn) 
	// 	{
	// 		throw new \Exception('Mysql connection is bad. :(');
	// 	}

	// 	if (empty($table))
	// 	{
	// 		throw new \Exception('Table name is not valid.');
	// 	}

	// 	$query = "UPDATE $table SET ( ";

	// 	$count = count($fields);
	// 	$types = '';
	// 	foreach ($fields as $name => $value) 
	// 	{
	// 		$query .= "$name = ?";
			
	// 		if (is_integer($value))
	// 		{
	// 			$types .= 'i';
	// 		}

	// 		if (is_string($value))
	// 		{
	// 			$types .= 's';
	// 		}

	// 		if (--$count != 0)
	// 		{
	// 			$query .= ', ';
	// 		}
	// 	}

	// 	$query .= ') ';

	// 	$this->addFiltersToQuery( $query, $filters );
		
	// 	return $this->execute_query( $query, array_merge(array_values($fields), array_values($filters) ) );
	// }

}