<?php

    class Tax extends Entity
    {
        protected $schema = array( 'id', 'name', 'vendor', 'value' );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

            $query = "SELECT * FROM tax WHERE id = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
        }
    }