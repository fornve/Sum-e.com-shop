<?php

    class User_Agent extends Entity
    {
        protected $schema = array( 'id', 'name', 'agent', 'type', 'count' );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

            $query = "SELECT * FROM user_agent WHERE id = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
		}

		static function GetByName( $name )
		{
			$query = "SELECT * FROM user_agent WHERE name = ?";
			$entity = new Entity();
			return $entity->GetFirstResult( $query, $name, __CLASS__ );
		}
    }
