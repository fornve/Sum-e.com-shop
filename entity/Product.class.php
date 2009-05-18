<?php
	class Product extends Entity
	{
		protected $schema = array( 'id', 'quantity', 'ordered', 'sold', 'price', 'name', 'description', 'keywords', 'added_date', 'weight', 'status', 'tax', 'vendor', 'upc', 'storage_location', 'visited', 'condition', 'deleted' );

		static function Retrieve( $id, $nocache = false )
		{
            if( !$id )
                return null;

			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );

            if( $nocache )
                $memcache->delete( MEMCACHE_PREFIX ."ShopProductRetrieve{$id}" );

			if( $nocache || !$object = $memcache->get( MEMCACHE_PREFIX ."ShopProductRetrieve{$id}" ) )
			{
				$query = "SELECT * FROM product WHERE id = ?";
				$entity = new Entity();
				$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

                if( !$object )
                    return null;

				$object->images = Product_Image::ProductCollection( $id, $nocache );
				$object->shippings = Shipping::ProductCollection( $id );
                $object->categories = Product_Category::ProductCollection( $id );
                $object->variants = Variant::ProductCollection( $id );

    	        $object->tax = Tax::Retrieve( $object->tax );
	            $object->vendor = Vendor::Retrieve( $object->vendor );

				$memcache->set( MEMCACHE_PREFIX ."ShopProductRetrieve{$id}", $object, false, MEMCACHE_LIFETIME );
			}
                
			$memcache->close();

			return $object;
		}

		function FlushCache()
		{
			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );
			$memcache->delete( MEMCACHE_PREFIX ."ShopProductRetrieve{$this->id}" );
		}

        static function AdminCollection()
        {
            $query = "SELECT id FROM product WHERE deleted = false ORDER BY name";
            $entity = new Entity();
            $result = $entity->Collection( $query, null, __CLASS__ );

            if( $result ) foreach ( $result as $item )
            {
                $products[] = Product::Retrieve( $item->id );
            }

            return $products;
        }

        function InCategory( $category_id )
        {
            if( $this->categories ) foreach( $this->categories as $product_category )
            {
                if( $category_id == $product_category )
                    return true;
            }
        }

        function InBranch( $category_id )
        {
            $categories = Category::LevelCollection( $category_id );

            if( $categories ) foreach( $categories as $category )
            {
                if( $this->categories ) foreach( $this->categories as $product_category )
                {
                    if( $category->id == $product_category )
                        return true;
                }
            }
        }

        function GetMainImage()
        {
            if( $this->images ) foreach( $this->images as $image )
            {
                if( $image->main )
                    return $image;
            }
        }

        static function IncrementVisited( $id )
        {
            if( !$id )
                return false;

            $entity = new Entity();
            $query = "UPDATE product SET visited = visited + 1 WHERE id = ?";
            $entity->Query( $query, $id );
        }

        function IsForSale()
        {
            if( !$this->status )
                return false;

            // check if there is something in variants
            if( count( $this->variants ) )
            {
                foreach( $this->variants as $variant )
                {
                    if( $variant->quantity > 0 )
                        return true;
                }
            }
            elseif( $this->quantity > 0 )
                return true;

            return false;
        }

		function Order( $quantity )
		{
			$product = Product::Retrieve( $this->id, true );
			$product->quantity -= $quantity;
			$product->ordered += $quantity;
			$product->Save();
			$product->FlushCache();
		}

		function UnOrder( $quantity )
		{
			$product = Product::Retrieve( $this->id, true );
			$product->quantity += $quantity;
			$product->ordered -= $quantity;
			$product->Save();
			$product->FlushCache();
		}

		function Despatch( $quantity )
		{
			$product = Product::Retrieve( $this->id, true );
			$product->ordered -= $quantity;
			$product->sold += $quantity;
			$product->Save();
			$product->FlushCache();
		}

		function UnDespatch( $quantity )
		{
			$product = Product::Retrieve( $this->id, true );
			$product->ordered += $quantity;
			$product->sold -= $quantity;
			$product->Save();
			$product->FlushCache();
		}

		function VariantOrder( $variants, $quantity )
		{
			foreach( $variants as $variant )
			{
				var_dump( 'Finish Variant update in Product.class.php line 138: ', $variant );
			}
		}

		function VariantUnOrder( $variants, $quantity )
		{
			foreach( $variants as $variant )
			{
				var_dump( 'Finish Variant update in Product.class.php line 138: ', $variant );
			}
		}

		function VariantDespatch( $variants, $quantity )
		{
			foreach( $variants as $variant )
			{
				var_dump( 'Finish Variant update in Product.class.php line 138: ', $variant );
			}
		}

		function VariantUnDespatch( $variants, $quantity )
		{
			foreach( $variants as $variant )
			{
				var_dump( 'Finish Variant update in Product.class.php line 138: ', $variant );
			}
		}

		function UpdateStock( $variants, $quantity, $action = 'order' )
		{

			if( $action == 'order' )
			{
				if( !$variants ) // no variant product
				{
					$this->Order( $quantity );
				}
				else // product with variants, quantity must be reduced from each variant
				{
					$this->VariantOrder( $variants, $quantity );
				}
			}
			elseif( $action == 'unorder' )
			{
				if( !$variants ) // no variant product
				{
					$this->UnOrder( $quantity );
				}
				else // product with variants, quantity must be reduced from each variant
				{
					$this->VariantUnOrder( $variants, $quantity );
				}
			}
			elseif( $action == 'despatch' )
			{
				if( !$variants ) // no variant product
				{
					$this->Despatch( $quantity );
				}
				else // product with variants, quantity must be reduced from each variant
				{
					$this->VariantDespatch( $variants, $quantity );
				}
			}
			elseif( $action == 'undespatch' )
			{
				if( !$variants ) // no variant product
				{
					$this->UnDespatch( $quantity );
				}
				else // product with variants, quantity must be reduced from each variant
				{
					$this->VariantUnDespatch( $variants, $quantity );
				}
			}
		}

		static function Enable( $id )
		{
			$product = Product::Retrieve( $id, true );
			if( !$product )
				return false;

			$product->status = true;
			$product->Save();
			$product->FlushCache();

			return $product;
		}

		static function Disable( $id )
		{
			$product = Product::Retrieve( $id, true );
			if( !$product )
				return false;

			$product->status = false;
			$product->Save();
			$product->FlushCache();

			return $product;
		}

		static function Search( $sentence )
		{
			$query = "SELECT * FROM product WHERE name LIKE ? OR description LIKE ? OR keywords LIKE ? OR upc LIKE ?";
			$entity = new Entity();
			return $entity->Collection( $query, array( "%{$sentence}%", "%{$sentence}%", "%{$sentence}%", "%{$sentence}%" ), __CLASS__ );
		}
	}

