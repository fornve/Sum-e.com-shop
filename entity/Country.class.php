<?php

    class Country extends Entity
    {
        protected $schema = array( 'code', 'name' );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

			$query = "SELECT * FROM country WHERE code = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
        }

        static function GetAll()
        {
			$query = "SELECT * FROM country ORDER BY name";
			$entity = new Entity();
			$object = $entity->Collection( $query, null, __CLASS__ );

            return $object;
        }
	}