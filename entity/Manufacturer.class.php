<?php

    class Manufacturer extends Entity
    {
        protected $schema = array( 'id', 'name', 'image' );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

            $query = "SELECT * FROM manufacturer WHERE id = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
        }
    }