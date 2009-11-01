<?php

class CategoryController extends Controller
{
	public $breadcrumbs = array( array( 'link' => '/CategoryAdmin/', 'name' => 'Category Admin' ) );

	function Index( $id, $page = 1 )
	{
		$category = Category::Retrieve( $id, false, $this->entity );
		$this->assign( 'breadcrumbs', CategoryController::BuildBreadcrumbs( $category ) );
		$this->assign( 'category', $category );

		$sentence =  SearchController::GetSearchedSentence();

		// pager
		{ 
			if( $page < 1 )
				$page = 1;

			$limit = Config::GetValue( 'category_products_limit' );
			$offset = ( floor( $page ) - 1 ) * $limit;
			$all_products = count( Product_Category::CategoryProductCollection( $id, $sentence ) );
			
			$max = ceil( $all_products / $limit );

			if( $max < 1 )
				$max = 1;
				
			/*$pager = array(
				'offset' => $page,
				'max' => $max,
				'self' => "/Category/Index/{$category->id}",
				'option' => "{$category->name}"
			);*/
			$this->Assign( 'pager', $pager );
		}

		$products = Product_Category::CategoryProductCollection( $id, $sentence, $limit, $offset );

		$this->assign( 'title', $category->name );
		$this->assign( 'products', $products );
		$this->assign( 'category_search_sentence', $sentence );
		echo $this->Decorate( 'category/index.tpl' );
	}

	function BuildBreadcrumbs( $category )
	{
		if( $category->parent )
			$breadcrumbs[] = array(
								'link' => "/Category/Index/{$category->parent->id}/{$category->parent->name}",
								'name' => $category->parent->name
								);

		$breadcrumbs[] = array(
							'link' => "/Category/Index/{$category->id}/{$category->name}",
							'name' => $category->name
							);

		return $breadcrumbs;
	}

	function GetMenu( $id )
	{
		$this->assign( 'category', Category::Retrieve( $id ) );
		$this->assign( 'categories', Category::GetTree() );
		echo $this->decorate( 'category/menu.tpl' );
	}

	function GetBreadcrumbs( $id )
	{
		$category = Category::Retrieve( $id );
		$this->assign( 'breadcrumbs', CategoryController::BuildBreadcrumbs( $category ) );
		echo $this->decorate( 'misc/breadcrumbs.tpl' );
	}

	function Image( $size, $id )
	{
		$category = Category::Retrieve( $id );

		if( !$size )
		{
			$this->OriginalImage( $category->image );
		}

		$size = explode( 'x', $size );

		$image = new ImageHandler( $category->image, $size[ 0 ], $size[ 1 ] );
		$image->add_borders = true;
		$image->Output();

	}
}
