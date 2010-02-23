<?php

class Page extends Entity
{
	protected $schema = array( 'id', 'title', 'text', 'image', 'type' );

	static function Retrieve( $id, $nocache = false )
	{
		if( !$id )
			return null;

		$cache = new Cache();

		if( $nocache )
			$cache->delete( "Page{$id}" );

		if( $nocache || !$object = $cache->get( "Page{$id}" ) )
		{
			$query = "SELECT * FROM page WHERE id = ?";
			$entity = new Entity();
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

		$cache = new Cache();

		if( $nocache )
			$cache->delete( "Page{$type}" );

		if( $nocache || !$object = $cache->get( "Page{$type}" ) )
		{
			$query = "SELECT * FROM page WHERE type = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $type, __CLASS__ );
			
			if( !$object )
				return null;

			$cache->set( "Page{$type}", $object, false, 100 * CACHE_LIFETIME );
		}
		
		return $object;
	}

	function FlushCache()
	{
		$cache = new Cache();
		$cache->delete( "Page{$this->id}" );
	}

	public static function GetAll()
	{
		$query = "SELECT * FROM page ORDER BY title";
		$entity = new Entity();
		return $entity->Collection( $query, null, __CLASS__ );
	}

	public static function CleanType( $type )
	{
		$query = "UPDATE page SET type = '' WHERE type = ?";
		$entity = new Entity();
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
