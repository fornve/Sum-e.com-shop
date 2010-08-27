<?php

class Product_Category extends Entity
{
	static function Retrieve( $product_id, $category_id, $nocache = false )
	{
		if( !$product_id || !$category_id )
			return null;

		$query = "SELECT * FROM product_category WHERE product = ? AND category = ?";
		$entity = Entity::getInstance();
		return $entity->GetFirstResult( $query, array( $product_id, $category_id ), __CLASS__ );
	}

	static function LinkDelete( $product, $category )
	{
		$query = "DELETE FROM product_category WHERE product = ? AND category = ?";
		$entity = Entity::getInstance();
		$entity->Query( $query, array( $product, $category ) );
	}

	function Create()
	{
		$query = "INSERT INTO product_category ( `product`, `category`, `order` ) VALUES ( ?, ?, ? )";
		$this->Query( $query, array( $this->product, $this->category, $this->order ), __CLASS__ );
	}

	static function Flush( $product )
	{
		$entity = Entity::getInstance();
		$query = "DELETE FROM product_category WHERE product = ?";
		$entity->Query( $query, $product->id );
	}

	static function ProductCollection( $product_id, $nocache = false )
	{
		$query = "SELECT * FROM product_category WHERE product = ?";
		$entity = Entity::getInstance();
		$result = $entity->Collection( $query, $product_id, __CLASS__ );

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

		/*
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
		*/

		if( $products ) foreach( $products as $product )
		{
			$collection[ $product->id ] = $product;
		}

		if( isset( $collection ) )
			return $collection;
	}

	private function CategoryProductCollectionItem( $category_id, $sentence, $limit = null, $offset = null, $nocache = false )
	{
		$entity = Entity::getInstance();
		$cache_hash = md5("{$category_id}{$sentence}{$limit}{$offset}");

		$cache = Cache::getInstance();

		if( $nocache )
		{
			$cache->delete( CACHE_PREFIX ."CategoryProductCollectionItem{$cache_hash}" );
		}

		if( $nocache || !$result = $cache->get( CACHE_PREFIX ."CategoryProductCollectionItem{$cache_hash}" ) )
		{
			if( $sentence )
			{
				$query = "SELECT
								product_category.*
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

				$result = $entity->Collection( $query, array( $category_id, "%{$sentence}%", "%{$sentence}%", "%{$sentence}%", "%{$sentence}%" ), __CLASS__, $limit, $offset );
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

				$result = $entity->Collection( $query, array( $category_id ), __CLASS__, $limit, $offset );
			}

			$cache->set( CACHE_PREFIX ."CategoryProductCollectionItem{$cache_hash}", $result, false, CACHE_LIFETIME );
		}

		if( $result ) foreach( $result as $item )
		{
			$products[] = Product::Retrieve( $item->product );
		}

		if( isset( $products ) )
			return $products;
	}

	public static function GetLastOrder( $category_id )
	{
		$query = "SELECT * FROM product_category WHERE category = ? ORDER BY `order` DESC LIMIT 1";
		$entity = Entity::getInstance();
		$assignment = $entity->GetFirstResult( $query, array( $category_id ), __CLASS__ );
		return $assignment->order;
	}
}
