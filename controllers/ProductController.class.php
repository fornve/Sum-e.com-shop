<?php

class ProductController extends Controller
{
	function Index( $id )
	{
		ProductController::View( $id );
	}

	function View( $id )
	{
		ProductController::IncrementVisited( $id );

		$product = Product::Retrieve( $id );

		if( !$product || !$product->status || $product->deleted )
			self::Redirect( "/Product/NotFound/" );
		
		if( $product->keywords )
		   $metas[] = array( 'name' => 'keywords', 'content' => $product->keywords );

		$metas[] = array( 'name' => 'description', 'content' => strip_tags( $product->description ) );

		$category = Category::Retrieve( $product->categories[ 0 ] );

		if( $category->parent )
			$breadcrumbs[] =  array( 'link' => "/Category/index/{$category->parent->id}/{$category->parent->name}", 'name' => $category->parent->name );
	
		$breadcrumbs[] =  array( 'link' => "/Category/index/{$category->id}/{$category->name}", 'name' => $category->name );
		$breadcrumbs[] = array( 'name' => $product->name );

		$this->assign( 'breadcrumbs', $breadcrumbs );
		$this->assign( 'category', $category );
		$this->assign( 'metas', $metas );
		$this->assign( 'title', "{$product->name} - {$category->name}" );
		$this->assign( 'product', $product );
		//$this->assign( 'related_products', $product->RelatedCollection() );
		$this->assign( 'vat_multiply', ( 1 + Site_Config::GetVat() ) );
		echo $this->Decorate( 'product/view.tpl' );
	}

	function Image( $size, $id )
	{
		$image = Product_Image::Retrieve( $id );

		if( !$image )
		{
			$image = new stdClass();
			$image->image = "";
		}

		if( !$size )
		{
			$this->OriginalImage( $image->image );
		}

		$size = explode( 'x', $size );

		$image = new ImageHandler( $image->image, $size[ 0 ], $size[ 1 ] );
		$image->add_borders = true;
		$image->Output();

	}

	function OriginalImage( $file )
	{
		header( "Content-Type: image/jpeg" );
		readfile( $file );
	}

	function NotFound()
	{
		echo "product not found... needs own page with search and related";
	}
	
	private static function IncrementVisited( $id )
	{
		if( $_SESSION[ 'visited_product' ][ $id ] )
			return false;
		
		$_SESSION[ 'visited_product' ][ $id ] = true;
		Product::IncrementVisited( $id );
	}
}
