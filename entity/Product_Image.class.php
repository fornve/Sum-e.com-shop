<?php

	class Product_Image extends Entity
	{
		protected $schema = array( 'id', 'product', 'image', 'title', 'main' );

		static function Retrieve( $id )
		{
            if( !$id )
                return null;

			$cache = new Cache();

			if( $nocache || !$object = $cache->get( "ShopProductImageRetrieve{$id}" ) )
			{
                $query = "SELECT * FROM product_image WHERE id = ?";
                $entity = new Entity();
                $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

                $cache->set( "ShopProductImageRetrieve{$id}", $object, false, MEMCACHE_LIFETIME );
            }

            return $object;
		}

        static function ProductCollection( $product )
        {
            $query = "SELECT * FROM product_image WHERE product = ? ORDER BY main DESC";
            $entity = new Entity();
            return $entity->Collection( $query, $product, __CLASS__ );
        }

        static function NewImage( $product, $file )
        {
            $main = 0;
            
            if( count( $product->images ) < 1 )
                $main = 1;

            $query = "INSERT INTO product_image ( product, image, `main` ) VALUES ( ?, ?, ? )";
            $entity = new Entity();
            $entity->Query( $query, array( $product->id, $file, $main ) );
        }

        function GetFilename()
        {
            return basename( $this->image );
        }

        static function SetProductPrimary( $product, $id )
        {
            $entity = new Entity();
            $query = "UPDATE product_image SET main = 0 WHERE product = ?";
            $entity->Query( $query, $product->id );

            $query = "UPDATE product_image SET main = 1 WHERE id = ?";
            $entity->Query( $query, $id );
        }

        function PreDelete()
        {
            unlink( $this->image );
        }
	}
