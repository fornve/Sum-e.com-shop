<?php

class Vendor extends Entity
{
	protected $schema = array( 'id', 'name', 'shortname', 'email', 'address', 'city', 'zip', 'county', 'country', 'phone', 'fax', 'company_number', 'vat' );

	static function Retrieve( $id )
	{
		if( !$id )
			return null;

		$query = "SELECT * FROM vendor WHERE id = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

		return $object;
	}
}
