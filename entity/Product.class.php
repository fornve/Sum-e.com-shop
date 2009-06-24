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

        static function AdminCollection( $limit, $offset )
        {
            $query = "SELECT id FROM product WHERE deleted = false ORDER BY name";
            $entity = new Entity();
            $result = $entity->Collection( $query, null, __CLASS__, $limit, $offset );

            if( $result ) foreach ( $result as $item )
            {
				$product = Product::Retrieve( $item->id );
                $products[] = $product;
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

		function CategoryCollection( $category_id )
		{
			$query = "SELECT 
							product.* 
						FROM 
							product 
						JOIN 
								product_category
							ON
								product.id = product_category.product
						WHERE 
									product_category.category = ?
								AND
									product.deleted = 0
						ORDER BY
								product_category.order";

			return $this->Collection( $query, array( $category_id ), __CLASS__ );
		}

		function MoveInCategory( $category_id, $iteration )
		{
			$products = Product::CategoryCollection( $category_id );
			
			$i = 0;
			
			if( $products ) foreach ( $products as $product )
			{
				$product_category = Product_Category::Retrieve( $product->id, $category_id );

				$i += 10;

				if( $product->id == $this->id )
				{
					if( $iteration > 0 )
						$product_category->order = $i - 15;
					else
						$product_category->order = $i + 15;

					$product_category->Save();
				}
				elseif( $product_category->order != $i )
				{
					$product_category->order = $i;
					$product_category->Save(); 
				}
			}
		}

		function MoveUp( $category_id )
		{
			$this->MoveInCategory( $category_id, 1 );
		}

		function MoveDown( $category_id )
		{
			$this->MoveInCategory( $category_id, -1 );
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

		static function Search( $sentence, $search_vars )
		{
			$query = "SELECT  product.* FROM product JOIN product_category ON product.id = product_category.product WHERE ( product.name LIKE ? OR product.description LIKE ? OR product.keywords LIKE ? OR product.upc LIKE ? ) AND product.status = 1";
			$attributes = array( "%{$sentence}%", "%{$sentence}%", "%{$sentence}%", "%{$sentence}%"  );

			//price range 
			if( $search_vars->price_ranges ) 
			{
				foreach( $search_vars->price_ranges as $price_range )
				{
					$price_range = Search::GetPriceRangeByName( $price_range );
					$price_range_query[] = " ( product.price BETWEEN ? AND ? ) ";
					$attributes[] = $price_range->min_value;
					$attributes[] = $price_range->max_value;
				}
				
				$query .= " AND (". implode( 'OR', $price_range_query ) ." ) ";
			}

			// category
			if( $search_vars->categories ) 
			{
				foreach( $search_vars->categories as $category )
				{
					$category_query[] = " product_category.category = ? ";
					$attributes[] = $category;
				}
				
				$query .= " AND (". implode( 'OR', $category_query ) ." ) ";
			}

			// product type
			if( $search_vars->condition )
			{
                foreach( $search_vars->conditions as $condition )
				{
					$price_range_query[] = " ( product.condition =  ? ) ";
					$attributes[] = $condition;
				}
				                
				$query .= " AND (". implode( 'OR', $price_range_query ) ." ) ";

			}

			$query .= " GROUP BY product.id";

			$entity = new Entity();
			return $entity->Collection( $query, $attributes, __CLASS__ );
		}

		public function GetCategory( $category_id )
		{
			return Category::Retrieve( $category_id );
		}

		public function RelatedCollection()
		{
			$query = "SELECT product.id FROM product JOIN product_category ON product.id = product_category.product WHERE product_category.category = ? AND NOT product.id = ?";
			$product_list = $this->Collection( $query, array( $this->categories[ 0 ], $this->id ), __CLASS__, 3 );

			if( $product_list ) foreach( $product_list as $element )
			{
				$products[] = Product::Retrieve( $element->id );
			}
			
			return $products;
		}

	}

