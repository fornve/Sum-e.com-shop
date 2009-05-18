<?php

	class Product_Category extends Entity
	{
		protected $schema = array( 'product', 'category' );

		static function Retrieve( $product )
		{
            if( !$product )
                return null;

            $query = "SELECT * FROM product_category WHERE product = ?";
            $entity = new Entity();
            return $entity->GetFirstResult( $query, $product, __CLASS__ );
		}

		static function LinkDelete( $product, $category )
		{
			$query = "DELETE FROM product_category WHERE product = ? AND category = ?";
			$entity = new Entity();
			$entity->Query( $query, array( $product, $category ) );
		}

		function Create()
		{
			$query = "INSERT INTO product_category ( product, category ) VALUES ( ?, ? )";
			$this->Query( $query, array( $this->product, $this->category ) );
		}

		static function Flush( $product )
		{
			$entity = new Entity();
			$query = "DELETE FROM product_category WHERE product = ?";
			$entity->Query( $query, $product->id );
		}

        static function ProductCollection( $product_id )
        {
            $query = "SELECT * FROM product_category WHERE product = ?";
            $entity = new Entity();
            $result = $entity->Collection( $query, $product_id );

            if( $result ) foreach( $result as $category )
            {
                $categories[] = $category->category;
            }

            return $categories;
        }

        static function CategoryProductCollection( $category_id, $sentence )
        {
            $entity = new Entity();

			if( $sentence )
			{
				$query = "SELECT product_category.* FROM product_category JOIN product ON product_category.product = product.id 
							WHERE
								category = ?
									AND
										product.status = 1
									AND 
										( name LIKE ? OR description LIKE ? OR keywords LIKE ? OR upc LIKE ? )";
				
				$result = $entity->Collection( $query, array( $category_id, "%{$sentence}%", "%{$sentence}%", "%{$sentence}%", "%{$sentence}%" ) );
			}
			else
			{
				$query = "SELECT product_category.* FROM product_category JOIN product ON product_category.product = product.id WHERE category = ? AND product.status = 1";
				$result = $entity->Collection( $query, $category_id );
			}

            if( $result ) foreach( $result as $item )
            {
                $products[] = Product::Retrieve( $item->product );
            }

            return $products;
        }
	}
