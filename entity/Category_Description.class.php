<?php

class Category_Description extends Entity
{
	static function Retrieve( $category )
	{
		if( !$category )
			return false;

		$query = "SELECT * FROM category_description WHERE category = ?";
		$entity = new Entity();
		return $entity->GetFirstResult( $query, $category, __CLASS__ );
	}
}
