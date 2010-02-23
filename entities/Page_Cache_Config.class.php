<?php

class Page_Cache_Config extends Entity
{
	protected $schema = array( 'id', 'controller', 'method', 'lifetime' );

	 static function Retrieve( $id )
	{
		 if( !$id )
			return null;

		$query = "SELECT * FROM page_cache_config WHERE id = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

		return $object;
	}

   static function Get( $controller, $method )
	{
		 if( !$id )
			return null;

		$cache = new Cache();
		$object = $cache->Get( CACHE_PREFIX ."PageCacheConfig{$id}" );

		if( !$object )
		{
			$query = "SELECT * FROM config WHERE id = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			$cache->Set( ,CACHE_PREFIX ."PageCacheConfig{$id}"  $object ,  null, CACHE_EXPIRE );
		}

		return $object;
	}


}
