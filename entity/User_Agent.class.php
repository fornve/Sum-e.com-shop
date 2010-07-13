<?php

class User_Agent extends Entity
{
	//protected $schema = array( 'id', 'name', 'agent', 'type', 'count', 'os' );

	static function Retrieve( $id )
	{
		if( !$id )
			return null;

		$query = "SELECT * FROM user_agent WHERE id = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

		return $object;
	}

	static function GetByName( $name ) 
	{
		if( strlen( $name ) < 1 )
			$name = 'unknown';

		$query = "SELECT * FROM user_agent WHERE name = ?";
		$entity = new Entity();
		return $entity->GetFirstResult( $query, $name, __CLASS__ );
	}

	static function GetAll()
	{
		$query = "SELECT * from user_agent ORDER BY count DESC";
		$entity = new Entity();
		return $entity->Collection( $query, null, __CLASS__ );
	}
}
