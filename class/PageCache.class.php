<?php

class PageCache
{
	static function Get( $url )
	{
		$filename = sha1( $url );

		$dir = scandir( PAGE_CACHE_DIR );
		foreach( $dir as $file )
		{
			$file_date = explode( '_', $file );

			if( $file_date[ 1 ] < time() )
			{
				unlink( PAGE_CACHE_DIR ."/{$file}" );
			}
			elseif( $file_date[ 0 ] == $filename )
			{
				return file_get_contents( PAGE_CACHE_DIR ."{$file}";
			}
		}

		return file_get_contents( $filename );
	}

	static function Set( $url, $content, $expires )
	{
		self::Delete( $url );
		$filename = PAGE_CACHE_DIR ."/". sha1( $url ) ."_". time();	
		file_set_contents( $filename, $contents );
	}	

	static function Delete( $url )
	{
		$filename = sha1( $url );
		$command = 'rm '. PAGE_CACHE_DIR .'/'. $filename .'_*';
		`{$command}`;
	}

	static function DeleteAll()
	{
		$command = "rm ". PAGE_CACHE_DIR ."/*";
		`{$command}`;
	}
}

