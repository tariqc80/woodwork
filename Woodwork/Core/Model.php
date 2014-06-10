<?php namespace Woodwork\Core;

class Model {

	private static $_database = null;
	private $_fields = array();

	public function __construct( array $fields )
	{
		foreach ($fields as $name => $value) 
		{
			$this->_fields[$name] = $value;
		}
	}

	public static function getDb() 
	{
		if (!self::$_database)
		{
			self::$_database = Application::getDatabase();
		}

		return self::$_database;
	}

	public static function getAll()
	{
		$db = self::getDb();

		// use late static binding to get the name of our model class.
		$modelClass = get_called_class();
		$rows = $db->getAllRows( $modelClass::$tableName );

		$models = array();

		foreach ( $rows as $_row )
		{
			$models[] = new $modelClass( $_row );
		}

		return $models;
	}

	public static function get( $id, $column = 'id' )
	{
		$model = null;
		$db = self::getDb();
		$modelClass = get_called_class();
		$row = $db->get( $modelClass::$tableName, $id, $column );

		if (!empty($row))
		{
			$model = new $modelClass($row[0]);
		}
		
		return $model;
	}

	public function __get( $name )
	{
		$value = null;
		if (isset($this->_fields[$name]))
		{
			$value = $this->_fields[$name];
		}

		return $value;
	}


	public function save() 
	{
		$success = false;
		$db = self::getDb();

		if (isset($this->id))
		{
			// update not implemented
			$success = false;
		}
		else
		{
			$modelClass = get_called_class();
			$lastInsertId = $db->insert( $modelClass::$tableName, $this->_fields );

			var_dump($lastInsertId);

			if (!!$lastInsertId)
			{
				$this->_fields['id'] = $lastInsertId;
				$success = true;
			}
		}

		return $success;
	}

}