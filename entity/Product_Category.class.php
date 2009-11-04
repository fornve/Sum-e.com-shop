<?php

class Product_Category extends Entity
{
	protected $schema = array( 'id', 'product', 'category', 'order' );

	static function Retrieve( $product_id, $category_id, $nocache = false )
	{
		if( !$product_id || !$category_id )
			return null;

		$query = "SELECT * FROM product_category WHERE product = ? AND category = ?";
		$entity = new Entity();
		return $entity->GetFirstResult( $query, array( $product_id, $category_id ), __CLASS__ );
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

	static function ProductCollection( $product_id, $nocache = false )
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

	static function CategoryProductCollection( $category_id, $sentence = null, $limit = null, $offset = null )
	{
		// get categories
		$category = Category::Retrieve( $category_id );

		$products = self::CategoryProductCollectionItem( $category_id, $sentence, $limit, $offset );

		$kids = Category::LevelCollection( $category_id );

		// get and connect product arrays
		if( $kids ) foreach( $kids as $kid )
		{
			$kid_products = self::CategoryProductCollectionItem( $kid->id, $sentence, $limit, $offset );

			if( $kid_products ) foreach( $kid_products as $product )
			{
				$products[] = $product;
			}
		}

		if( $products ) foreach( $products as $product )
		{
			$collection[ $product->id ] = $product;
		}

		return $collection;
	}
	
	private function CategoryProductCollectionItem( $category_id, $sentence, $limit = null, $offset = null )
	{
		$entity = new Entity();

		if( $sentence )
		{
			$query = "SELECT product_category.* 
						
						FROM
								product_category
							JOIN
									product
								ON
									product_category.product = product.id
						WHERE
							category = ?
								AND
									product.status = 1
								AND
									product.deleted = 0
								AND
									( name LIKE ? OR description LIKE ? OR keywords LIKE ? OR upc LIKE ? )
						ORDER BY
								product_category.order";
			
			$result = $entity->Collection( $query, array( $category_id, "%{$sentence}%", "%{$sentence}%", "%{$sentence}%", "%{$sentence}%" ), 'stdClass', $limit, $offset );
		}
		else
		{
			$query = "SELECT product_category.*

						FROM
								product_category
							JOIN
									product
								ON
									product_category.product = product.id
						WHERE
							category = ?
								AND
									product.status = 1
								AND
									product.deleted = 0
						ORDER BY
								product_category.order";
			$result = $entity->Collection( $query, array( $category_id ), 'stdClass', $limit, $offset );
		}

		if( $result ) foreach( $result as $item )
		{
			$products[] = Product::Retrieve( $item->product );
		}

		return $products;
	}
}
