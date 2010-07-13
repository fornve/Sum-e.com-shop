<?php

class Country extends Entity
{
	protected $schema = array( 'code', 'name' );

	static function Retrieve( $id )
	{
		if( !$id )
			return null;

		$query = "SELECT * FROM country WHERE code = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

		return $object;
	}

	static function GetAll( $entity = null, $nocache = false )
	{
		$cache = new Cache();

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."Countries" );

		if( $nocache || !$object = $cache->get( CACHE_PREFIX ."Countries" ) )
		{
			$query = "SELECT * FROM country ORDER BY name";

			if( !$entity )
				$entity = new Entity();

			$object = $entity->Collection( $query, null, __CLASS__ );

			$cache->set( CACHE_PREFIX ."Countries", $object, false, CACHE_LIFETIME * 100 );
		}

		return $object;
	}

}
