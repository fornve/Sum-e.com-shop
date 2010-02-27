<?php

    class User_Role extends Entity
    {
        protected $schema = array( 'user', 'role' );

        static function Retrieve( $user, $role )
        {
            if( !$user && !$role )
                return null;

			$query = "SELECT user_role.* FROM user_role JOIN role ON user_role.role = role.id WHERE user_role.user = ? AND role.name = ?";
			$entity = new Entity();
			$result = $entity->GetFirstResult( $query, array( $user, $role ), __CLASS__ );

			if( $result )
			{
				return Role::Retrieve( $result->role );
			}

			return false;
        }

		static function Add( $user, $role_name )
		{
			$role = Role::RetrieveByName( $role_name );

			if( $role )
			{
				$query = "INSERT INTO user_role ( user, role ) VALUES ( ?, ? )";
				$entity = new Entity();
				$entity->Query( $query, array( $user, $role->id ) );
			}
			else
			{
				//Log::Add( 'high', 'Role name not found.', $role_name );
			}
		}

		static function UserCollection( $user_id )
		{
			$query = "SELECT * FROM user_role WHERE user = ?";
			$entity = new Entity();
			return $entity->Collection( $query, array( $user_id ), __CLASS__ );
		}

		function Delete()
		{
			$query = "DELETE FROM user_role WHERE user = ? AND role = ?";
			$this->Query( $query, array( $this->user, $this->role ) );
		}
	}
