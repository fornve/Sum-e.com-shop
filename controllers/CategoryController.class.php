<?php

    class CategoryController extends Controller
    {
        public $breadcrumbs = array( array( 'link' => '/CategoryAdmin/', 'name' => 'Category Admin' ) );

        function Index( $id )
        {
            $category = Category::Retrieve( $id );
            $this->assign( 'breadcrumbs', CategoryController::BuildBreadcrumbs( $category ) );
            $this->assign( 'category', $category );

			$sentence =  SearchController::GetSearchedSentence();
            $products = Product_Category::CategoryProductCollection( $id, $sentence );
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