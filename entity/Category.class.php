<?php

    class Category extends Entity
    {
        protected $schema = array( 'id', 'name', 'parent', 'image', 'sort_order' );

        static function Retrieve( $id, $nocache = false, $entity = null )
        {
            if( !$id )
                return null;

			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );

            if( $nocache )
                $memcache->delete( MEMCACHE_PREFIX ."ShopCategoryRetrieve{$id}" );

			$object = $memcache->get( MEMCACHE_PREFIX ."ShopCategoryRetrieve{$id}" );

			if( $nocache || !$object )
			{
				$query = "SELECT * FROM category WHERE id = ?";

				if( !$entity )
					$entity = new Entity();
					
	            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );
 
                if( !$object )
                    return null;

	            if( $object->parent )
	                $object->parent = Category::Retrieve( $object->parent, $nocache );

    	        $object->description = Category_Description::Retrieve( $object->id );

				$memcache->set( MEMCACHE_PREFIX ."ShopCategoryRetrieve{$id}", $object, false, MEMCACHE_LIFETIME );
			}

			$memcache->close();

            return $object;
        }

		function GetDescription()
		{
			return Category_Description::Retrieve( $object->id );
		}

		function FlushCache()
		{
			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );
			$memcache->delete( MEMCACHE_PREFIX ."ShopCategoryRetrieve{$this->id}" );
			$memcache->close();
			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );
			$memcache->delete( MEMCACHE_PREFIX ."ShopCategoryTree" );
			$memcache->close();
		}

		function ImageBasename()
		{
			return basename( $this->image );
		}

        static function LevelCollection( $parent = 0, $nocache = false, $entity = null )
		{
			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );

			$prefix ? $parent : 'root';

            if( $nocache )
                $memcache->delete( MEMCACHE_PREFIX ."ShopCategoryLevelCollection{$prefix}" );

			$objects = $memcache->get( MEMCACHE_PREFIX ."ShopCategoryLevelCollection{$prefix}" );

			// avoid overwritting object with garbage
			if( get_class( $objects[ 0 ] ) != 'Category' )
			{
				$memcache->delete( MEMCACHE_PREFIX ."ShopCategoryLevelCollection{$prefix}" );
				unset( $objects );
			}

			if( $nocache || !$objects )
			{
            	$query = "SELECT id FROM category WHERE parent = ? ORDER BY name";
            	$entity = new Entity();
				$collection = $entity->Collection( $query, $parent, __CLASS__ );

                if( $collection ) foreach( $collection as $item )
                {
					$object = Category::Retrieve( $item->id, $nocache, $entity );
                    $objects[] = $object;
                }

				$memcache->set( MEMCACHE_PREFIX ."ShopCategoryLevelCollection{$parent}", $objects, false, MEMCACHE_LIFETIME * 10 );
			}

			$memcache->close();

			return $objects;
        }

        static function GetAll()
        {
            $entity = new Entity();
            $query = "SELECT * FROM category";
            return $entity->Collection( $query );
        }

        static function GetTree( $nocache = false )
        {
			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );

            if( $nocache )
                $memcache->delete( MEMCACHE_PREFIX ."ShopCategoryTree" );

			if( $nocache || !$root = $memcache->get( MEMCACHE_PREFIX ."ShopCategoryTree" ) )
			{
                $first_level = Category::LevelCollection( 0, $nocache );

                if( $first_level ) foreach( $first_level as $parent )
                {
                    $parent->kids = Category::LevelCollection( $parent->id );
                    $root[] = $parent;
                }
				$memcache->set( MEMCACHE_PREFIX ."ShopCategoryTree", $root, false, MEMCACHE_LIFETIME );
			}

			$memcache->close();

            return $root;
        }

		static function Search( $sentence, $search_vars )
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
							( category_description.description LIKE ? OR category.name LIKE ? )";
			
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
			$entity = new Entity();

			return $entity->Collection( $query, $attributes, __CLASS__ );
		}
    }
