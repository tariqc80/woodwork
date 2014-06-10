<?php namespace Woodwork\Core;

class Database {
	
	private $_adapter = null;

	public function __construct( $adapter ) 
	{
		$this->_adapter = $adapter;
	}

	public function getAllRows( $table, $filters = array() )
	{	
		return $this->_adapter->query( $table, $filters );
	}

	public function get( $table, $id, $column = 'id')
	{		
		return $this->_adapter->query( $table, array( $column => $id ) );
	}

	public function insert( $table, $fields )
	{
		return $this->_adapter->insert( $table, $fields );
	}


}