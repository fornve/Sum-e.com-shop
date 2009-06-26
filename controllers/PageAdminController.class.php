<?php

	class PageAdminController extends AdminController
	{
		public $breadcrumbs = array( array( 'name' => 'Pages Admin', 'link' => '/PageAdmin/' ) );

		function Index()
		{
			$this->assign( 'breadcrumbs', $this->breadcrumbs );
			$this->assign( 'pages', Page::GetAll() );
			echo $this->Decorate( 'admin/page/index.tpl' );
		}

        function Edit( $id )
        {

			if( $id )
					$page = Page::Retrieve( $id, true );

			if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			{
				if( !$page )
				{
					$page = new Page();
					$new_page = true;
				}

				$input = Common::Inputs( array( 'title', 'text', 'delete_image', 'type' ), INPUT_POST );

                $new_image = PageAdminController::UploadImage( $id );

				if( $new_image )
					$page->image = $new_image;

				elseif( $input->delete_image && $page->image )
				{
					if( file_exists( $page->image ) )
						unlink( $page->image );

					$page->image = '';
				}

				if( $input->type )
					Page::CleanType( $input->type );


				if( $new_page )
					Log::Add( "PAGE_NEW", 'PAGE', $_SESSION[ 'admin' ]->id, "Page [ {$page->id} ] <a href=\"/Page/View/{$page->id}\">{$page->name}</a>" );
				else
					Log::Add( "PAGE_EDIT", 'PAGE', $_SESSION[ 'admin' ]->id, "Page [ {$page->id} ] <a href=\"/Page/View/{$page->id}\">{$page->name}</a>" );

				$page->title = $input->title ? $input->title : 'unnamed';
				$page->text = $input->text;
				$page->type = $input->type;
                $page->Save();

				$page->FlushCache();
                $page = Page::Retrieve( $page->id, true );

				$_SESSION['user_notification'][] = array( 'text' => "page saved.", 'type' => 'notice'  );
			}

			if( $page->title )
				$this->breadcrumbs[] = array( 'name' => "Page edit: {$page->title}" );
			else
				$this->breadcrumbs[] = array( 'name' => "New Page" );

            $this->Assign( 'breadcrumbs', $this->breadcrumbs );
            $this->Assign( 'page', $page );
            echo $this->Decorate( 'admin/page/edit.tpl' );
        }

		function Delete( $id )
		{
			$page = Page::Retrieve( $id, true );

			if( $page->id )
			{
				Log::Add( "PAGE_DELETE", 'PAGE', $_SESSION[ 'admin' ]->id, "Page [ {$page->id} ] - {$page->name}" );
				$page->PreDelete();
				$page->Delete();
				$_SESSION[ 'user_notification' ][] = array( "type" => 'notice', 'text' => 'Page deleted.' );
			}
			else
				$_SESSION[ 'user_notification' ][] = array( "type" => 'error', 'text' => 'Page not found.' );

			self::Redirect( '/PageAdmin/' );
		}

		function UploadImage( $id )
		{
			// upload file
            if( $_FILES[ "image" ][ 'name' ] )
            {
                if( !file_exists( ASSETS_PATH ."/page/" ) )
                    mkdir( ASSETS_PATH ."/page/" );

                $i = 1;
                do
                {
                    $filename = "{$prefix}". $_FILES[ 'image' ][ 'name' ];
                    $prefix = $i++ .'_';
                    $file = ASSETS_PATH ."/page/". $filename;
                }
                while( file_exists( $file ) );

                if( move_uploaded_file( $_FILES[ 'image' ][ 'tmp_name' ], $file ) )
                {
                    return $file;
                }
                else
                {
                    $_SESSION[ 'user_notification' ][] = array( "type" => 'error', 'text' => 'Error uploading file.' );
                }
            }
		}

	}
