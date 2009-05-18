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

		public static function PerformSearch( $sentence )
		{
			$search = Search::RetrieveBySentence( $sentence );

			if( !$search )
			{
				$search = new Search();
				$search->sentence = $sentence;
			}

			$result[ 'products' ] = $search->SearchProducts();
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
			return Product::Search( $this->sentence );
		}

		public function SearchCategories()
		{
			return Category::Search( $this->sentence );
		}
	}
