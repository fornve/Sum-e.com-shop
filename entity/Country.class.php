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

        static function GetAll( $entity = null )
        {
			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );

            if( $nocache )
                $memcache->delete( MEMCACHE_PREFIX ."Countries" );

			if( $nocache || !$object = $memcache->get( MEMCACHE_PREFIX ."Countries" ) )
			{
				$query = "SELECT * FROM country ORDER BY name";

				if( !$entity )
					$entity = new Entity();

				$object = $entity->Collection( $query, null, __CLASS__ );

				$memcache->set( MEMCACHE_PREFIX ."Countries", $object, false, MEMCACHE_LIFETIME * 100 );
			}

			return $object;
        }
	}