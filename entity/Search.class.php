<?php

class Search extends Entity
{
	protected $schema = array( 'id', 'sentence', 'count', 'results' );

	static function Retrieve( $id )
	{
		if( !$id )
			return null;

		$query = "SELECT * FROM search WHERE id = ?";
		$entity = new Entity();
		$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			if( !$object )
				return null;

		return $object;
	}

	function RetrieveBySentence( $sentence )
	{
		$query = "SELECT * FROM search WHERE sentence = ?";
		$entity = new Entity();
		return $entity->GetFirstResult( $query, $sentence, __CLASS__ );
	}

	public static function GetAll( $order = 'sentence' )
	{
		$query = "SELECT * FROM search ORDER BY {$order}";
		$entity = new Entity();
		return $entity->Collection( $query, null, __CLASS__ );
	}

	public static function PerformSearch( $sentence, $search_vars )
	{
		$search = Search::RetrieveBySentence( $sentence );

		if( !$search )
		{
			$search = new Search();
			$search->sentence = $sentence;
		}

		$search->search_vars = $search_vars;

		if( !$search_vars->type || $search_vars->type == 'products' )
			$result[ 'products' ] = $search->SearchProducts();

		if( !$search_vars->type || $search_vars->type == 'categories' )
			$result[ 'categories' ] = $search->SearchCategories();
		
		$search->results = count( $result[ 'products' ] ) + count( $result[ 'categories' ] );
		$search->count++;
		$search->Save();
		
		if( $search->results )
			return $result;
		else
			return null;
	}

	public function SearchProducts()
	{
		return Product::Search( $this->sentence, $this->search_vars );
	}

	public function SearchCategories()
	{
		return Category::Search( $this->sentence, $this->search_vars );
	}

	public static function PriceRanges()
	{
		$array = array(
				array( 'id' => '1', 'name' => 'Up to &pound;100', 'min_value' => '0', 'max_value' => '100' ),
				array( 'id' => '2', 'name' => '&pound;101 - &pound;500', 'min_value' => '101', 'max_value' => '500' ),
				array( 'id' => '3', 'name' => '&pound;501 - &pound;1000', 'min_value' => '501', 'max_value' => '1000' ),
				array( 'id' => '4', 'name' => 'More than &pound;1000', 'min_value' => '1001', 'max_value' => '999999999' )
			);

		return $array;
	}

	public static function PriceRangeGetByID( $id )
	{
		$array = Search::PriceRanges();

		foreach( $array as $price_range )
		{
			if( $price_range[ 'id' ] == htmlentities( $id ) )
			{
				return $price_range;
			}
		}
	}
}
