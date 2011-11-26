<?php

class Category extends Entity
{
	protected $schema = array( 'id', 'name', 'parent', 'image', 'sort_order' );

	static function retrieve( $id, $nocache = false, $entity = null )
	{
		if( !$id )
		{
			return null;
		}

		$cache = Cache::getInstance();

		if( $nocache )
		{
			$cache->delete( CACHE_PREFIX ."Category{$id}" );
		}

		$object = $cache->get( CACHE_PREFIX ."Category{$id}" );

		if( $nocache || !$object )
		{
			$query = "SELECT * FROM category WHERE id = ?";

			$entity = Entity::getInstance();

			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			if( !$object )
			{
				return null;
			}

			if( $object->parent )
			{
				$object->parent = Category::retrieve( $object->parent, $nocache );
			}

			$object->description = Category_Description::retrieve( $object->id );

			$cache->set( CACHE_PREFIX ."Category{$id}", $object, false, CACHE_LIFETIME );
		}

		return $object;
	}

	function getDescription()
	{
		return Category_Description::retrieve( $object->id );
	}

	function flushCache()
	{
		$cache = Cache::getInstance();
		$cache->delete( CACHE_PREFIX ."Category{$this->id}" );
		$cache->delete( CACHE_PREFIX ."CategoryTree{$this->id}" );
		$cache->delete( CACHE_PREFIX ."CategoryTree{$this->parent}" );
		$cache->delete( CACHE_PREFIX ."CategoryLevel{$this->id}" );
		$cache->delete( CACHE_PREFIX ."CategoryLevel{$this->parent}" );
	}

	function imageBasename()
	{
		return basename( $this->image );
	}

	static function levelCollection( $parent = 0, $nocache = false, $entity = null )
	{
		$cache = Cache::getInstance();

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."CategoryLevel{$parent}" );

		$objects = $cache->get( CACHE_PREFIX ."CategoryLevel{$parent}" );
		// avoid overwritting object with garbage
		/*if( get_class( $objects[ 0 ] ) != 'Category' )
		{
			$cache->delete( CACHE_PREFIX ."CategoryLevel{$parent}" );
			unset( $objects );
		}*/

		if( $nocache || $objects === false )
		{
			$objects = array();
			$query = "SELECT id FROM category WHERE parent = ? ORDER BY name";
			$entity = new Entity();
			$collection = $entity->Collection( $query, $parent, __CLASS__ );

			if( $collection ) foreach( $collection as $item )
			{
				$object = Category::retrieve( $item->id, $nocache, $entity );
				$objects[] = $object;
			}

			$cache->set( CACHE_PREFIX ."CategoryLevel{$parent}", $objects, false, CACHE_LIFETIME * 10 );
		}

		return $objects;
	}

	static function getAll()
	{
		return parent::getAll( __CLASS__ );
	}

	static function getTree( $nocache = false )
	{
		$cache = Cache::getInstance();

		if( $nocache )
			$cache->delete( CACHE_PREFIX ."CategoryTree" );

		if( $nocache || !$root = $cache->get( CACHE_PREFIX ."CategoryTree" ) )
		{
			$first_level = Category::LevelCollection( 0, $nocache );

			if( $first_level ) foreach( $first_level as $parent )
			{
				$parent->kids = Category::LevelCollection( $parent->id );
				$root[] = $parent;
			}

			$cache->set( CACHE_PREFIX ."CategoryTree", $root, false, CACHE_LIFETIME );
		}

		return $root;
	}

	static function search( $sentence, $search_vars )
	{
		$query = "SELECT
						category.*
					FROM
						category
					JOIN
						category_description ON category.id = category_description.category
					JOIN
						product_category ON category.id = product_category.category
					WHERE
						( category_description.description LIKE ? OR category.name LIKE ? )
						";

		$attributes = array( "%{$sentence}%", "%{$sentence}%" );

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


		$query .= " GROUP BY category.id";
		$entity = Entity::getInstance();

		return $entity->collection( $query, $attributes, __CLASS__ );
	}
}
