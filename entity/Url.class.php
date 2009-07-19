<?php

    class Url extends Entity
    {
        protected $schema = array( 'id', 'organic', 'artificial' );

        static function Retrieve( $id, $nocache = false )
        {
            if( !$id )
                return null;

			$query = "SELECT * FROM url WHERE id = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
        }

		function GetOrganic( $url )
		{
			$cache = new Cache();

            if( $nocache )
                $cache->delete( "Page". sha1( $url ) );

			if( $nocache || !$object = $cache->get( "Page". sha1( $url ) ) )
			{
				$query = "SELECT * FROM url WHERE artificial = ?";
				$entity = new Entity();
				$object = $entity->GetFirstResult( $query, $url );

                if( !$object )
                    return null;

				$cache->set( "Page". sha1( $url ), $object, false, 100 * MEMCACHE_LIFETIME );
			}

            return $object->organic;
		}

		public static function GetAll()
		{
			$query = "SELECT * FROM url ORDER BY organic";
			$entity = new Entity();
			return $entity->Collection( $query, null, __CLASS__ );
		}
	}
