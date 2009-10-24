<?php

class Imageserver extends Entity
{
	protected $schema = array( 'id', 'file', 'original_url', 'destination_url', 'last_access' );

	function RetrieveByFile( $file, $nocache = false )
	{
		$cache = new Cache();
		$hash = md5( $file );

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."ImageserverFile{$hash}" );

		if( $nocache || !$object = $cache->get( CACHE_PREFIX ."ImageserverFile{$hash}" ) )
		{
			$query = "SELECT * FROM imageserver WHERE file = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $file, __CLASS__ );
			$cache->set( CACHE_PREFIX ."ImageserverFile{$hash}", $object, false, CACHE_LIFETIME );
		}

		return $object;
	}

	function Put( $file, $filename = null )
	{
		if( !file_exists( $file ) )
			return false;

		if( !$filename )
			$filename = explode( '/', $file );
		else
			$filename = explode( '/', $filename );

		$filename = implode( '-', $filename );
		$filename = str_replace( ' ', '_', $filename );

		$command = "cp '{$file}' '/tmp/{$filename}'";
		`{$command}`;

		if( file_exists( "/tmp/{$filename}" ) )
		{
			$uploader = new ImageserverUploader( PROJECT_NAME, md5( IMAGESERVER_TOKEN . date( "Y-m-d" ) ) );
			$uploader->upload( "/tmp/{$filename}" );
			$command = "rm '/tmp/{$filename}'";
			`{$command}`;

			$url = IMAGESERVER .'/'. PROJECT_NAME .'/';
			$url .= $filename;

			return $url;
		}
	}

	public static function SmartyGetUrl( $params, &$smarty )
	{
		var_dump( $params  ); exit;
		return Imageserver::GetUrl( $params[ 'url' ] );
	}

	function GetUrl( $original_url, $nocache = true )
	{
		$cache = new Cache();
		$hash = md5( $original_url );

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."ImageserverUrl{$hash}" );

		if( $nocache || !$object = $cache->get( CACHE_PREFIX ."ImageserverUrl{$hash}" ) )
		{
			$query = "SELECT * FROM imageserver WHERE original_url = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $original_url, __CLASS__ );

			if( $object->destination_url )	
			{
				$cache->set( CACHE_PREFIX ."ImageserverUrl{$hash}", $object, false, CACHE_LIFETIME );	
				return $object->destination_url;
			}

			$data = @file_get_contents( PROJECT_URL . urldecode( $original_url ) );
			$filename = tempnam( '/tmp/', 'emarket' ); 
			file_put_contents( $filename, $data );

			error_log( "Fileserver upload: {$filename} [". filesize( $filename ) ."]" );

			if( file_exists( $filename ) && filesize( $filename ) > 0 )
				$url = self::Put( $filename, $original_url );

			$command = "rm '{$filename}'";
			`{$command}`;

			if( $url )
			{
				$image = new Imageserver();
				$image->file = null;
				$image->original_url = $original_url;
				$image->destination_url = $url;
				$image->created = time();
				$image->Save();
			
				return $url; 
			}
		}

		if( $object->destination_url )
			return $object->destination_url;

		return $original_url;
	}

}
