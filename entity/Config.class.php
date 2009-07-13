<?php

    class Config extends Entity
    {
        protected $schema = array( 'id', 'title', 'name', 'value', 'type', 'description' );

        static function Retrieve( $id )
        {
             if( !$id )
                return null;

            $query = "SELECT * FROM config WHERE id = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
        }

		static function GetValue( $name )
		{
            $query = "SELECT value FROM config WHERE name = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $name, __CLASS__ );
			return $object->value;
		}

        static function FlushCache()
        {
            $memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );
            $memcache->delete( MEMCACHE_PREFIX ."ShopConfig" );
			$memcache->close();
        }

		static function GetAll()
		{
            $query = "SELECT * FROM config";
            $entity = new Entity();
            $object = $entity->Collection( $query, null, __CLASS__ );

            return $object;
		}

		static function GetCached()
		{
			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );
			$object = $memcache->get( MEMCACHE_PREFIX ."ShopConfig" );

			if( !$object )
			{
				$object = array();

				$config = Config::GetAll();

				if( $config ) foreach( $config as $item )
				{
					$object[ $item->name ] = $item->value;
				}

				$memcache->set( MEMCACHE_PREFIX ."ShopConfig", $object, false, MEMCACHE_LIFETIME );
			}

			$memcache->close();

			return $object;
		}

		static function DefineAll()
		{
			$object = Config::GetCached();
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
    }
