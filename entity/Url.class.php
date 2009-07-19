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

		function Decode( $urli, $nocache = false )
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
				$artificial = substr( sha1( $organic ) 0, 3 + $i++ );
			{
			while( !self::Decode( $artificial )

			$url = new Url();
			$url->organic = $organic;
			$url->artificial = $artificial;
			$url->Save();

			return $artificial;
		}
	}
