<?php

class Site_Config extends Entity
{
	protected $schema = array( 'id', 'title', 'name', 'value', 'type', 'description' );

	static function Retrieve( $id )
	{
		 if( !$id )
			return null;

		$query = "SELECT * FROM site_config WHERE id = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

		return $object;
	}

	static function GetValue( $name )
	{
		$query = "SELECT value FROM site_config WHERE name = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $name, __CLASS__ );
		return $object->value;
	}

	static function SetValue( $name, $value )
	{
		$query = "Update site_config SET value = ? WHERE name = ?";
		$entity = new Entity();
		$entity->Query( $query, array( $value, $name ) );
	}

	function FlushCache()
	{
		$cache = new Cache();
		$cache->delete( CACHE_PREFIX ."ShopConfig" );
	}

	static function GetAll()
	{
		$query = "SELECT * FROM site_config";
		$entity = new Entity();
		$object = $entity->Collection( $query, null, __CLASS__ );

		return $object;
	}

	static function GetCached()
	{
		$cache = new Cache();
		$object = $cache->get( CACHE_PREFIX ."ShopConfig" );

		if( !$object )
		{
			$object = array();

			$config = self::GetAll();

			if( $config ) foreach( $config as $item )
			{
				$object[ $item->name ] = $item->value;
			}

			$cache->set( CACHE_PREFIX ."ShopConfig", $object, false, CACHE_LIFETIME );
		}

		return $object;
	}

	static function DefineAll()
	{
		$object = self::GetCached();
		if( $object ) foreach( $object as $key => $value )
		{
			$key = strtoupper( $key );
			if( !defined( $key ) )
			{
				define( $key, $value );
				// var_dump( "{$key} => {$value}" ); // show all defined
			}
		}
	}

	static function GetVat()
	{
		if( constant( 'VAT_DISPLAY' ) == 1 && defined( 'VAT_VALUE' ) )
			$vat = constant( 'VAT_VALUE' ) / 100;
		else
			$vat = 0;

		return $vat;
	}

	static function PageCache()
	{
		$config = self::GetCached();

		return $config[ 'page_cache' ];	
	}
}
