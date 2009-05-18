<?php

    class PageController extends Controller
    {
        function Index( $id )
        {
            $this->assign( 'breadcrumbs', array( array( 'link' => '/Page/', 'name' => 'Index' ) ) );
            $this->assign( 'pages', Page::GetAll() );
            echo $this->Decorate( 'page/index.tpl' );
        }

        function Image( $size, $id )
        {
            $page = Page::Retrieve( $id );

            if( !$size )
            {
                $this->OriginalImage( $page->image );
            }

            $size = explode( 'x', $size );

            $image = new ImageHandler( $page->image, $size[ 0 ], $size[ 1 ] );
            $image->add_borders = true;
            $image->Output();

        }

    }