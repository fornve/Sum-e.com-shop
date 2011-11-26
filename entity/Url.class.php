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
	//protected $schema = array( 'id', 'organic', 'artificial' );
	public static $tiny_url_hash_length = 3;

	public $not_allowed = array(
	);

	static function retrieve( $id, $nocache = false )
	{
		if( !$id )
			return null;

		$cache = Cache::getInstance();

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."UrlRetrieve". $id );

		if( $nocache || !$object = $cache->get( CACHE_PREFIX ."UrlRetrieve". $id ) )
		{
			$query = "SELECT * FROM url WHERE id = ?";
			$entity = Entity::getInstance();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			if( $object )
			{
				$cache->set( CACHE_PREFIX ."UrlRetrieve". $id, $object, false, 100 * CACHE_LIFETIME );
			}
		}

		return $object;
	}

	public function preDelete()
	{
		$this->flushCache();
	}

	public function flushCache()
	{
		$cache = Cache::getInstance();
		$cache->delete( CACHE_PREFIX ."UrlRetrieve". $this->id );
		$cache->delete( CACHE_PREFIX ."Url". sha1( $this->artificial ) );
		$cache->delete( CACHE_PREFIX ."SeoUrl". sha1( $this->organic ) );
		$cache->delete( CACHE_PREFIX ."UrlOrganic". md5( $this->organic ) );
	}

	public function getByArtificial( $url )
	{
		if( !$url )
			return null;

		$query = "SELECT * FROM url WHERE artificial = ?";
		$entity = Entity::getInstance();
		$object = &$entity->GetFirstResult( $query, $url, __CLASS__ );

		return $object;
	}

	public function getByOrganic( $url, $nocache = false )
	{
		if( !$url )
			return null;

		$cache = Cache::getInstance();

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."UrlOrganic". md5( $url ) );

		$object = &$cache->get( CACHE_PREFIX ."UrlOrganic". md5( $url ) );

		if( $nocache || !$object )
		{
			$query = "SELECT * FROM url WHERE organic = ?";
			$entity = Entity::getInstance();
			$object = $entity->GetFirstResult( $query, $url, __CLASS__ );

			if( $object )
			{
				$cache->set( CACHE_PREFIX ."UrlOrganic". md5( $url ), $object, false, CACHE_LIFETIME );
			}
			else
			{
				$cache->set( CACHE_PREFIX ."UrlOrganic". md5( $url ), 1, false, round( CACHE_LIFETIME / 100 ) );
			}
		}

		if( $object === 1 )
		{
			$object = null;
		}

		return $object;
	}

	public function deleteByOrganic( $organic )
	{
		$url = self::getByOrganic( $organic, true );

		if( $url )
		{
			$url->delete();
		}
	}

	public function decode( $url, $nocache = false )
	{
		$cache = Cache::getInstance();

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."Url". sha1( $url ) );

		if( $nocache || !$object = &$cache->get( CACHE_PREFIX ."Url". sha1( $url ) ) )
		{
			$query = "SELECT * FROM url WHERE artificial = ?";
			$entity = Entity::getInstance();
			$object = $entity->GetFirstResult( $query, $url, __CLASS__ );

			if( $object )
			{
				$cache->set( CACHE_PREFIX ."Url". sha1( $url ), $object, false, 100 * CACHE_LIFETIME );
			}
		}

		return is_object( $object ) && isset( $object->organic ) ? $object->organic : $url;
	}

	public static function getAll()
	{
		$query = "SELECT * FROM url ORDER BY organic";
		$entity = Entity::getInstance();
		return $entity->collection( $query, null, __CLASS__ );
	}

	public function _( $organic )
	{
		return self::encode( $organic );
	}

	public static function encode( $organic, $nocache = false )
	{
		if( !is_array( $organic ) )
		{
			$organic = array( $organic );
		}

		$cache = Cache::getInstance();
		$cache_hash = sha1( serialize( $organic ) );

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."SeoUrl". $cache_hash );

		$object = &$cache->get( CACHE_PREFIX ."SeoUrl". $cache_hash );

		if( $nocache || !$object )
		{
			$entity = Entity::getInstance();
			$query = "SELECT * FROM url WHERE ";

			$query_suffix = array();

			foreach( $organic as &$url )
			{
				$query_suffix[] = " organic = ? ";
				$attributes[] = urldecode( $url );
			}

			$query .= implode( "OR", $query_suffix );

			$url = &$entity->GetFirstResult( $query, $attributes, __CLASS__, 1 );

			if( $url )
			{
				$cache->set( CACHE_PREFIX ."SeoUrl". $cache_hash, $object, false, 100 * CACHE_LIFETIME );

				return $url->artificial;
			}
			else
			{
				$cache->set( CACHE_PREFIX ."SeoUrl". $cache_hash, 1, false, round( CACHE_LIFETIME / 100 ) );
				return $organic[ 0 ];
			}
		}

		if( !is_object( $object ) )
		{
			return $organic[ 0 ];
		}
		else
		{
			return $object->artificial;
		}
	}

	public function tinyUrl( $organic )
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

	public function seoCategory( $id, $page, $name )
	{
		return self::_( array(
			"/category/index/{$id}/{$page}/". self::encodeTail( $name ),
			"/category/index/{$id}/{$page}"
		) );
	}

	public function seoPage( $id, $name )
	{
		return self::_( array(
			"/page/view/{$id}/". self::encodetail( $name ),
			"/page/view/{$id}"
		) );
	}

	public function getAllByArtificial()
	{
		$query = "SELECT * FROM url ORDER BY artificial";
		$entity = Entity::getInstance();
		return $entity->collection( $query, null, __CLASS__ );
	}

	public static function encodeTail( $tail )
	{
		return urlencode(
			preg_replace(
				"/[^a-z0-9\\.\\-]/i",
				'',
				strtolower(
					str_replace( ' ', '-', $tail ) ) )
		);
	}
}
