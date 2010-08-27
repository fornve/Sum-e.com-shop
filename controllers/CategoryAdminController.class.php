<?php

class CategoryAdminController extends AdminController
{
	public $breadcrumbs = array( array( 'link' => '/Admin/', 'name' => 'Admin' ), array( 'link' => '/CategoryAdmin/', 'name' => 'Category Admin' ) );

	function Index()
	{
		$this->Assign( 'breadcrumbs', $this->breadcrumbs );
		echo $this->Decorate( "admin/category/index.tpl" );
	}

	function Edit( $id )
	{

		if( $id )
				$category = Category::Retrieve( $id, true );

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
		{
			$input = Common::Inputs( array( 'name', 'parent', 'description', 'delete_image' ), INPUT_POST );

			if( !$category )
			{
				$category = new Category();
				$new_category = true;
			}

			$new_image = CategoryAdminController::UploadImage( $id );
			
			if( $new_image )
				$category->image = $new_image;
			elseif( $input->delete_image && $category->image )
			{
				if( file_exists( $category->image ) )
					unlink( $category->image );

				$category->image = '';
			}

			$category->name = $input->name ? $input->name : 'unnamed';
			$category->parent = $input->parent;
			$category->Save();

			if( $category->id )
				$category_description = Category_Description::Retrieve( $category->id );

			if( !$category_description )
			{
				$category_description = new Category_Description();
				$category_description->Create( 'category_description', $category->id );
			}

			$category_description->category = $category->id;
			$category_description->description = $input->description;
			$category_description->Save();

			$category->FlushCache();
			$category = Category::Retrieve( $category->id, true );

			$_SESSION['user_notification'][] = array( 'text' => "Category saved.", 'type' => 'notice'  );
		}

		if( $new_category )
			Log::Add( "CATEGORY_NEW", 'CATEGORY', $_SESSION[ 'admin' ]->id, "Category [ {$category->id} ] <a href=\"/Category/View/{$category->id}\">{$category->name}</a>" );
		else
			Log::Add( "CATEGORY_EDIT", 'CATEGORY', $_SESSION[ 'admin' ]->id, "Category [ {$category->id} ] <a href=\"/Category/View/{$category->id}\">{$category->name}</a>" );

		if( $category->name )
			$this->breadcrumbs[] = array( 'name' => "Category edit: {$category->name}" );
		else
			$this->breadcrumbs[] = array( 'name' => "New Category" );

		$this->Assign( 'breadcrumbs', $this->breadcrumbs );
		$this->Assign( 'category', $category );
		$this->Assign( 'root_categories', Category::LevelCollection( 0 ) );
		echo $this->Decorate( 'admin/category/edit.tpl' );
	}

	function UploadImage( $id )
	{
		// upload file
		if( $_FILES[ "image" ][ 'name' ] )
		{
			if( !file_exists( ASSETS_PATH ."/category/" ) )
				mkdir( ASSETS_PATH ."/category/" );

			$i = 1;
			do
			{
				$filename = "{$prefix}". $_FILES[ 'image' ][ 'name' ];
				$prefix = $i++ .'_';
				$file = ASSETS_PATH ."/category/". $filename;
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

	function Categories( $id )
	{
		$this->assign( 'category', Category::Retrieve( $id ) );
		$this->assign( 'categories', Category::LevelCollection( $id ) );
		echo $this->Decorate( 'admin/category/child_categories.tpl' );
	}

	function Products( $id )
	{
		self::Redirect( "/ProductAdmin/InCategoryList/{$id}" );
	}

	function Delete( $id )
	{
		$category = Category::Retrieve( $id, true );

		if( Category::LevelCollection( $id ) )
		{
			$_SESSION[ 'user_notification' ][] = array( 'text' => "Category can not be deleted, some child categories exists.", 'type' => 'error'  );
			self::Redirect( "/CategoryAdmin/Categories/{$id}" );
		}
		elseif( Product_Category::CategoryProductCollection( $id ) )
		{
			$_SESSION[ 'user_notification' ][] = array( 'text' => "There are some products assigned to this category.", 'type' => 'error' );
			self::Redirect( "/CategoryAdmin/Products/{$id}" );
		}
		else
		{
			Log::Add( "CATEGORY_DELETE", 'CATEGORY', $_SESSION[ 'admin' ]->id, "Category [ {$category->id} ] - {$category->name}" );
			$category->Delete();
			Site_Config::FlushCache();
			$_SESSION[ 'user_notification' ][] = array( 'text' => "Category deleted.", 'type' => 'notice' );
			self::Redirect( "/" );
		}
	}

	function UnassignAllProducts( $id )
	{
		$category = Category::Retrieve( $id );

		$products = Product_Category::CategoryProductCollection( $id );

		if( $products ) foreach( $products as $product )
		{
			Product_Category::LinkDelete( $product->id, $category->id );
		}

		$_SESSION[ 'user_notification' ][] = array( 'text' => "All products unassigned from {$category->name}.", 'type' => 'notice' );
		self::Redirect( "/Category/Index/{$id}/1" );
	}

}
