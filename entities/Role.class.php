<?php

    class Role extends Entity
    {
        protected $schema = array( 'id', 'name', 'description' );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

			$query = "SELECT * FROM role WHERE id = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
        }

		static function RetrieveByName( $name )
		{
            if( !$name )
                return null;

			$entity = new Entity();
			$query = "SELECT * FROM role WHERE name = ?";
			return  $entity->GetFirstResult( $query, $name, __CLASS__ );
		}

	}