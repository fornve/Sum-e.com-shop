<?php

class Page extends Entity
{
	static function Retrieve( $id, $nocache = false )
	{
		if( !$id )
			return null;

		$cache = Cache::getInstance();

		if( $nocache )
			$cache->delete( "Page{$id}" );

		if( $nocache || !$object = $cache->get( "Page{$id}" ) )
		{
			$query = "SELECT * FROM page WHERE id = ?";
			$entity = Entity::getInstance();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			if( !$object )
				return null;

			$cache->set( "Page{$id}", $object, false, 100 * CACHE_LIFETIME );
		}

		return $object;
	}

	function RetrieveByType( $type, $nocache = false )
	{
		if( strlen( $type ) < 1 )
			return null;

		$cache = Cache::getInstance();

		if( $nocache )
			$cache->delete( "Page{$type}" );

		if( $nocache || !$object = $cache->get( "Page{$type}" ) )
		{
			$query = "SELECT * FROM page WHERE type = ?";
			$entity = Entity::getInstance();
			$object = $entity->GetFirstResult( $query, $type, __CLASS__ );
			
			if( !$object )
				return null;

			$cache->set( "Page{$type}", $object, false, 100 * CACHE_LIFETIME );
		}
		
		return $object;
	}

	function FlushCache()
	{
		$cache = Cache::getInstance();
		$cache->delete( "Page{$this->id}" );
	}

	public static function GetAll()
	{
		$query = "SELECT * FROM page ORDER BY title";
		$entity = Entity::getInstance();
		return $entity->Collection( $query, null, __CLASS__ );
	}

	public static function CleanType( $type )
	{
		$query = "UPDATE page SET type = '' WHERE type = ?";
		$entity = Entity::getInstance();
		$entity->Query( $query, $type );
	}

	function PreDelete()
	{
		if( file_exists( $this->image ) )
		{
			unlink( $this->image );
		}
	}
}
