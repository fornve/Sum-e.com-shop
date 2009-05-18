<?php

    class ProductController extends Controller
    {
        function Index( $id )
        {
            ProductController::View( $id );
        }

        function View( $id )
        {
            Product::IncrementVisited( $id );
            $product = Product::Retrieve( $id );

            if( !$product )
                self::Redirect( "/Product/NotFound/" );
            
            if( $product->keywords )
               $metas[] = array( 'name' => 'keywords', 'content' => $product->keywords );

            $metas[] = array( 'name' => 'description', 'content' => strip_tags( $product->description ) );

            $this->assign( 'metas', $metas );
            $this->assign( 'title', $product->name );
            $this->assign( 'product', $product );
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
            echo "product not found... needs own page";
        }
    }
