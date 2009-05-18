<?php

    class Page extends Entity
    {
        protected $schema = array( 'id', 'title', 'text', 'image', 'type' );

        static function Retrieve( $id, $nocache = false )
        {
            if( !$id )
                return null;

			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );

            if( $nocache )
                $memcache->delete( "Page{$id}" );

			if( $nocache || !$object = $memcache->get( "Page{$id}" ) )
			{
				$query = "SELECT * FROM page WHERE id = ?";
            	$entity = new Entity();
	            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

                if( !$object )
                    return null;

				$memcache->set( "Page{$id}", $object, false, 100 * MEMCACHE_LIFETIME );
			}

			$memcache->close();

            return $object;
        }

		function RetrieveByType( $type )
		{
			$query = "SELECT * FROM page WHERE type = ?";
			$entity = new Entity();
			return $entity->GetFirstResult( $query, $type );
		}

		function FlushCache()
		{
			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );
			$memcache->delete( "Page{$this->id}" );
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
