<?php

    class ProductAdminController extends AdminController
    {
        public $breadcrumbs = array( array( 'link' => '/Admin/', 'name' => 'Admin' ), array( 'link' => '/ProductAdmin/', 'name' => 'Product Admin' ) );
		public $list_limit = 10;

        function Index()
        {
            self::Redirect( "/ProductAdmin/ProductList/" );
        }

        function ProductList( $page = 1 )
        {
			$this->ChangeStatus();
			$offset = ( $page - 1 ) * $this->list_limit;
			$products_in_shop = count( Product::AdminCollection() );
			$max = ceil( $products_in_shop / $this->list_limit );
			
			$pager = array(
				'offset' => $page,
				'max' => $max,
				'self' => '/ProductAdmin/ProductList'
			);
			$this->Assign( 'pager', $pager );
			
            $this->Assign( 'products', Product::AdminCollection( $this->list_limit, $offset ) );
			$this->Assign( 'products_in_shop', $products_in_shop );
            $this->Assign( 'breadcrumbs', $this->breadcrumbs );
            echo $this->Decorate( 'admin/product/index.tpl' );
        }

		function InCategoryList( $category_id )
		{
			$this->assign( 'category', Category::Retrieve( $category_id ) );
			$this->assign( 'products', Product_Category::CategoryProductCollection( $category_id ) );
            $this->Assign( 'breadcrumbs', $this->breadcrumbs );
			echo $this->Decorate( 'admin/product/index.tpl' );
		}

        function Edit( $id = null )
        {
            AdminController::EnsureAdminAndProductOwner( $id );

			if( $id )
					$product = Product::Retrieve( $id, true );

			if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			{
				if( !$product )
                {
                    $product = new Product();
                    $product->vendor = $_SESSION[ 'admin' ]->vendor-id;
                    $product->Save();
					$new_product = true;
                }

                ProductAdminController::UpdateProductGeneral( $product );
                ProductAdminController::UpdateProductImages( $product );
                ProductAdminController::UpdateProductCategories( $product );
                ProductAdminController::UpdateProductVariants( $product );

                $product = Product::Retrieve( $product->id, true );

				if( $new_product )
					Log::Add( "PRODUCT_NEW", 'PRODUCT', $_SESSION[ 'admin' ]->id, "PRODUCT [ {$product->id} ] <a href=\"/Product/View/{$product->id}\">{$product->name}</a>" );
				else
					Log::Add( "PRODUCT_EDIT", 'PRODUCT', $_SESSION[ 'admin' ]->id, "PRODUCT [ {$product->id} ] <a href=\"/Product/View/{$product->id}\">{$product->name}</a>" );
					
				$_SESSION['user_notification'][] = array( 'text' => "Product saved.", 'type' => 'notice' );
			}

            $this->breadcrumbs[] = array( 'name' => "Edit: {$product->name}" );

            $this->Assign( 'breadcrumbs', $this->breadcrumbs );
            $this->Assign( 'product', $product );
            $this->Assign( 'category_tree',  Category::GetTree() );
            echo $this->Decorate( 'admin/product/edit.tpl' );
        }

		public function ListOrderUp( $product_id, $category_id )
		{
			$product = Product::Retrieve( $product_id, true );
			$product->MoveUp( $category_id );
			self::RedirectReferer();
		}

		public function ListOrderDown( $product_id, $category_id )
		{
			$product = Product::Retrieve( $product_id, true );
			$product->MoveDown( $category_id );
			self::RedirectReferer();
		}


        // private methods

        private function UpdateProductGeneral( $product )
        {
            $input = Common::Inputs( array( 'quantity', 'price', 'name', 'weight', 'status', 'tax', 'description', 'keywords', 'upc', 'storage_location', 'condition' ), INPUT_POST );

            if( $input ) foreach( $input as $key => $value )
            {
                $product->$key = $value;
            }

            $product->Save();
        }

        private function UpdateProductImages( $product )
        { 

            // upload file
            if( $_FILES[ "image" ][ 'name' ] )
            {
                if( !file_exists( ASSETS_PATH ."/product/" ) )
                    mkdir( ASSETS_PATH ."/product/" );
                
                if( !file_exists( ASSETS_PATH ."/product/{$product->id}" ) )
                    mkdir( ASSETS_PATH ."/product/{$product->id}" );

                $i = 1;
                do
                {
                    $filename = "{$prefix}". $_FILES[ 'image' ][ 'name' ];
                    $prefix = $i++ .'_';
                    $file = ASSETS_PATH ."/product/{$product->id}/" . $filename;
                }
                while( file_exists( $file ) );

                if( move_uploaded_file( $_FILES[ 'image' ][ 'tmp_name' ], $file ) )
                {
                    Product_Image::NewImage( $product, $file );
                }
                else
                {
                    $_SESSION[ 'user_notification' ][] = array( "type" => 'error', 'text' => 'Error uploading file.' );
                }
            }

            if( $product->images ) foreach( $product->images as $image )
            {
                $title_keyname = "image_title_{$image->id}";
                $delete_keyname = "image_delete_{$image->id}";
                
                $input = Common::Inputs( array( $title_keyname, $delete_keyname ), INPUT_POST );

                if( $input->$delete_keyname )
                {
                    $_SESSION[ 'user_notification' ][] = array( "type" => 'error', 'text' => "Image {$image->id} deleted!" );
                    $image->Delete();
                }
                elseif( $image->title != $input->$title_keyname )
                {
                    $image->title = $input->$title_keyname;
                    $image->Save();
                }
            }

            // set primary
            $input = Common::Inputs( array( "main_image" ), INPUT_POST );
            if( $input->main_image )
                Product_Image::SetProductPrimary( $product, $input->main_image );
        }

        private function UpdateProductCategories( $product )
        {
			$categories = Category::GetAll();

			foreach( $categories as $category )
			{
				$parameters[] = "category_{$category->id}";
			}

			$input = Common::Inputs( $parameters, INPUT_POST );

			Product_Category::Flush( $product );

			if( $input ) foreach( $input as $key => $value )
			{
				if( $value )
				{
					$product_category = new Product_Category();
					$product_category->product = $product->id;
					$product_category->category = $value;
					$product_category->Create();
				}
			}	
        }

        private function UpdateProductVariants( $product )
        {
            // create new
            $input = Common::Inputs( array( 'new_variant_type', 'new_variant_name', 'new_variant_price_change', 'new_variant_quantity' ), INPUT_POST );

            if( $input->new_variant_name )
            {
                $variant = new Variant();
                $variant->product = $product->id;
                $variant->name = $input->new_variant_name;
                $variant->type = $input->new_variant_type;
                $variant->quantity = $input->new_variant_quantity;
                $variant->price_change = $input->new_variant_price_change;
                $variant->Save();
            }

            // update / delete
            if( $product->variants ) foreach( $product->variants as $variant )
            {
                $delete_key = "delete_variant_{$variant->id}";
                $price_change_key = "variant_price_change_{$variant->id}";
                $quantity_key = "variant_quantity_{$variant->id}";

                $input = Common::Inputs( array( $delete_key, $price_change_key, $quantity_key ), INPUT_POST );
               
                if( $input->$delete_key &&  $input->$delete_key == $variant->id )
                {
                    $variant->Delete();
                }
                elseif( $input->$price_change_key != $variant->price_change || $input->$quantity_key != $variant->quantity )
                {
                    $variant->price_change = $input->$price_change_key;
                    $variant->quantity = $input->$quantity_key;
                    $variant->Save();
                }
            }
		}

		public function Delete( $id )
		{
			$product = Product::Retrieve( $id, true );
			if( !$product || $product->vendor->id != $_SESSION['admin']->vendor->id )
			{
				$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'Product not found.' );
				self::Redirect( '/ProductAdmin/ListAll/' );
			}
			else
			{
				Log::Add( "PRODUCT_DELETE", 'PRODUCT', $_SESSION[ 'admin' ]->id, "PRODUCT [ {$product->id} ] - {$product->name}" );
				$product->deleted = 1;
				$product->status = 0;
				$product->Save();
				$_SESSION[ 'user_notification' ][] = array( 'type' => 'notice', 'text' => "Product {$product->name} deleted." );
				self::Redirect( '/ProductAdmin/ProductList/' );
			}
		}

		public function UnassignFromCategory( $id, $category )
		{
			Product_Category::LinkDelete( $id, $category );
			$_SESSION[ 'user_notification' ][] = array( 'text' => "Product unassigned.", 'type' => 'notice' );
			self::Redirect( $_SERVER['HTTP_REFERER'] );

		}

		private function ChangeStatus()
		{
			$input = Common::Inputs( array( 'enable', 'disable' ) );

			if( $input )
			{
				if( $input->enable )
				{
					$product = Product::Enable( $input->enable );
					$_SESSION[ 'user_notification' ][] = array( "type" => 'notice', 'text' => "Product `{$product->name}` enabled" );
				}

				if( $input->disable )
				{
					$product = Product::Disable( $input->disable );
					$_SESSION[ 'user_notification' ][] = array( "type" => 'notice', 'text' => "Product `{$product->name}` disabled" );
				}
			}

		}

    }
