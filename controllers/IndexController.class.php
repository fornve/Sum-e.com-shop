<?php
class IndexController extends Controller
{
	function Index()
	{
		$this->assign('categories', Category::LevelCollection( 0 ));
		echo $this->decorate( 'index.tpl' );
	}

	function NotFound()
	{
        header( "HTTP/1.0 404 Not Found" );
		if( PRODUCTION )
			echo $this->Decorate( "404.tpl" );
		else
			echo $this->Decorate( "404-development.tpl" );
	}

	function Image( $size = null, $file )
	{
		if( !$size )
		{
			$this->OriginalImage( $file );
		}

		$size = explode( 'x', $size );

        $image = new ImageHandler( ASSETS_PATH . $file, $size[ 0 ], $size[ 1 ] );
		$image->add_borders = true;
		$image->Output();

	}

	function OriginalImage( $file )
	{
		header( "Content-Type: image/jpeg" );
		readfile( ASSETS_PATH . $file );
	}
}
