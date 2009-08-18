<?php
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * <marek@dajnowski.net> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Marek Dajnowski
 * ----------------------------------------------------------------------------
 */

    class Url extends Entity
    {
        protected $schema = array( 'id', 'organic', 'artificial' );
		public static tiny_url_hash_length = 3;

        static function Retrieve( $id, $nocache = false )
        {
            if( !$id )
                return null;

			$query = "SELECT * FROM url WHERE id = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
        }

		function Decode( $url, $nocache = false )
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

				$cache->set( "Page". sha1( $url ), $object, false, 100 * CACHE_LIFETIME );
			}

            return $object->organic;
		}

		public static function GetAll()
		{
			$query = "SELECT * FROM url ORDER BY organic";
			$entity = new Entity();
			return $entity->Collection( $query, null, __CLASS__ );
		}

		public static function Encode( $organic )
		{
			$entity = new Entity();
			$query = "SELECT * FROM url WHERE organic = ?";
			$url = $entity->GetFirstResult( $query, $organic, __CLASS__ );
			
			if( $url )
				return $url->artificial;
			else
				return $organic;
		}

		public function TinyUrl( $organic )
		{
			$artificial = self::Encode( $organic );

			if( $artificial )
				return $artificial;

			do
			{
				$artificial = substr( sha1( $organic ), 0, self::tiny_url_hash_length + $i++ );
			}
			while( !self::Decode( $artificial ) );

			$url = new Url();
			$url->organic = $organic;
			$url->artificial = $artificial;
			$url->Save();

			return $artificial;
		}
	}
