<?php

	class Product_Manufacturer extends Entity
	{
		protected $schema = array( 'product', 'manufacturer' );

		static function Retrieve( $product )
		{
            if( !$product )
                return null;

            $query = "SELECT * FROM product_manufacturer WHERE product = ?";
            $entity = new Entity();
            return $entity->GetFirstResult( $query, $product, __CLASS__ );
		}
	}