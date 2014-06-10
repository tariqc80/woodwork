<?php namespace Woodwork\Core;

interface DbAdapter {

	public function connect($name, $password, $database, $port );
	public function close();
	
	public function query( $table, $query );
	
	// public function insert( $query );
	
	// public function delete( $query );
	
	// public function update( $query );
}