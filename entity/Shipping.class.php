<?php

class Shipping extends Entity
{
	function Value( $order_value )
	{
		if( $order_value > $this->limit_price )
			return 0;
		else
			return $this->flat_value;
	}

	function ValueWithOrder( $order_value )
	{
		if( $order_value > $this->limit_price )
			return $order_value;
		else
			return $order_value + $this->flat_value;
	}

	static function Retrieve( $id )
	{
		if( !$id )
			return null;

		$query = "SELECT * FROM shipping WHERE id = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

		return $object;
	}

	static function GetVendor( $vendor_id )
	{
		$query = "SELECT * FROM shipping WHERE vendor = ?";
		$entity = new Entity();
		$object = $entity->Collection( $query, $vendor_id, __CLASS__ );

		return $object;
	}

	static function ProductCollection( $id )
	{
		$query = "SELECT shipping.* FROM shipping JOIN product_shipping ON shipping.id = product_shipping.shipping WHERE product_shipping.product = ?";
		$entity = new Entity();
		$result = $entity->Collection( $query, $id, __CLASS__ );

		return $result;
	}

	static function GetAll()
	{
		$query = "SELECT * FROM shipping ORDER BY flat_value";
		$entity = new Entity();
		return $entity->Collection( $query, null, __CLASS__ );
	}

	static function GetAllEnabled()
	{
		$query = "SELECT * FROM shipping WHERE enabled = true ORDER BY flat_value";
		$entity = new Entity();
		return $entity->Collection( $query, null, __CLASS__ );
	}

	static function Enable( $id )
	{
		$shipping = Shipping::Retrieve( $id );
		if( !$shipping )
			return false;

		$shipping->enabled = true;
		$shipping->Save();
		return $shipping;
	}

	static function Disable( $id )
	{
		$shipping = Shipping::Retrieve( $id );
		if( !$shipping )
			return false;

		$shipping->enabled = false;
		$shipping->Save();
		return $shipping;
	}
}
