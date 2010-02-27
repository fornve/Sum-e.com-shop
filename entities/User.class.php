<?php

    class User extends Entity
    {
        protected $schema = array( 'id', 'email', 'username', 'password', 'logins', 'last_login' );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

			$query = "SELECT * FROM user WHERE id = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			if( !$object )
				return null;

			if( $object->HasRole( 'vendor' ) )
				$object->vendor = Vendor::Retrieve( $object->vendor );

            return $object;
        }

		function RetrieveForComments( $id )
		{
            if( !$id )
                return null;

			$memcache = new Memcache();
			$memcache->connect( MEMCACHE_HOST, MEMCACHE_PORT );

            if( $nocache )
                $memcache->delete( MEMCACHE_PREFIX ."CommentUserRetrieve{$id}" );

			if( $nocache || !$object = $memcache->get( MEMCACHE_PREFIX ."CommentUserRetrieve{$id}" ) )
			{
				$query = "SELECT * FROM user WHERE id = ?";
				$entity = new Entity();
				$object = $entity->GetFirstResult( $query, $id, __CLASS__ );
				$object->password = null;
				$memcache->set( MEMCACHE_PREFIX ."CommentUserRetrieve{$id}", $object, false, MEMCACHE_LIFETIME );
			}

			$memcache->close();

			return $object;
		}

		function HasRole( $role )
		{
			return User_Role::Retrieve( $this->id, $role );
		}

		function AddRole( $role )
		{
			if( !$this->HasRole( $role ) )
				return User_Role::Add( $this->id, $role ) ? true : false;
		}

		function RemoveRole( $role )
		{
			if( $this->HasRole( $role ) )
				return User_Role::Remove( $this->id, $role ) ? true : false;
		}

		static function Logout()
		{
/*			if ( $token = Cookie::get( 'authautologin' ) )
			{
				// Delete the autologin cookie to prevent re-login
				Cookie::delete('authautologin');

				// Clear the autologin token from the database
				$token = User_Token::RetrieveByToken( $token );

				if ( $token )
				{
					$token->delete();
				}
			}
 */			
			unset( $_SESSION[ 'logged_user' ] );
		}

		static function Autologin()
		{
			// to do
			//
			//$input = new Input();
			//$token = $input->cookie( 'authautologin', null, true );
			//$user_agent = sha1( $input->server( 'HTTP_USER_AGENT', 'unknown', true ) );
			//$user_token = User_Token::RetrieveByTokenAndUserAgent( $token, $user_agent );
			//var_dump( $user_token );
		}

		function PreDelete()
		{
			$user_roles = User_Role::UserCollection( $user );
			if( $user_roles ) foreach( $user_roles as $user_role )
			{
				$user_role->Delete();
			}
		}

		static function Login( $username, $password, $remember = false )
		{
			$query = "SELECT * FROM user WHERE username = ? AND password = ?";
			$entity = new Entity();
			$user = $entity->GetFirstResult( $query, array( $username, $password ), __CLASS__ );
			
			$_SESSION[ 'logged_user' ] = User::Retrieve( $user->id );
			
			if( $user )
			{
				$user->logins++;
				$user->last_login = time();
				$user->Save();

				if ( $remember === TRUE )
				{
					// Delete any existing token
					User_Token::DeleteUser( $user->id );

					// Create a new autologin token
					$token = new User_Token();

					$input = new Input();

					// Set token data
					$token->user = $user->id;
					$token->token = sha1( $user->password . time() . PROJECT_NAME . $user->email );
					$token->user_agent = sha1( $input->server( 'HTTP_USER_AGENT', 'unknown', true ) );
					$token->created = time();
					$token->expires = time() + COOKIE_LIFETIME;
					$token->Save();

					// Set the autologin cookie
					Cookie::set( 'authautologin', $token->token, time() + COOKIE_LIFETIME, '/' );
				}
			}

			return $user;
		}

		static function CheckIfEntryExisis( $column, $value )
		{
			$query = "SELECT * FROM user WHERE `{$column}` = ?";
			$entity = new Entity();
			$user = $entity->GetFirstResult( $query, array( $value ), __CLASS__ );
			return $user ? true : false;
		}
	}
