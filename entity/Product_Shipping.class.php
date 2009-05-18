<?php

    class Product_Shipping extends Entity
    {
        protected $schema = array( 'product', 'shipping' );

        static function Retrieve( $product )
        {
            if( !$product )
                return null;

            $query = "SELECT * FROM product_shipping WHERE id = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $product, __CLASS__ );

            return $object;
        }