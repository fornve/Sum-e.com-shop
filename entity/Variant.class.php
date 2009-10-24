<?php

class Variant extends Entity
{
	protected $schema = array( 'id', 'product', 'type', 'name', 'price_change', 'quantity' );

	static function Retrieve( $id )
	{
		 if( !$id )
			return null;

		$query = "SELECT * FROM variant WHERE id = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

		return $object;
	}

	static function ProductCollection( $product_id )
	{
		$query = "SELECT * FROM variant WHERE product = ?";
		$entity = new Entity();
		$object =  $entity->Collection( $query, $product_id, 'Variant' );
	
		return $object;
	}
	
}
