<?php

    class Admin extends Entity
    {
        protected $schema = array( 'id', 'username', 'password', 'last_login_time', 'last_login_ip', 'vendor' );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

            $query = "SELECT * FROM admin WHERE id = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
        }

        static function Login( $username, $password )
        {
            $entity = new Entity();
            $query = "SELECT * FROM admin WHERE username = ? AND password = md5( ? )";
            $object = $entity->GetFirstResult( $query, array( $username, $password ), __CLASS__ );

            if( $object )
			{
				$object->last_login_ip = $_SERVER[ 'REMOTE_ADDR' ];
				$object->last_login_time = date( "Y-m-d H:i:s" );
				
				$object->Save();
				
                $object->vendor = Vendor::Retrieve( $object->vendor );

			}

            return $object;
        }

        static function VendorCollection( $vendor )
        {
            if( !$vendor )
                return false;

            $query = "SELECT * FROM admin WHERE ?";

            if( $vendor > 1 )
                $query = "SELECT * FROM admin WHERE vendor = ?";

            $entity = new Entity();
            return $entity->Collection( $query, $vendor );
        }

		static function GetAll()
		{
			$query = "SELECT * FROM admin";
			$entity = new Entity();
			return $entity->Collection( $query, null, __CLASS__ );
		}

		static function CheckIfAny()
		{
			if( Admin::GetAll() )
				return true;
			else
				return false;
		}
    }