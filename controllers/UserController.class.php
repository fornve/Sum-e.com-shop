<?php

	class UserController extends Controller
	{
		function Index()
		{
			self::Redirect( "/User/Login" );
		}

		function Login()
		{
			if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			{
				$input = Common::Inputs( array( 'username', 'password', 'rememberme' ), INPUT_POST );
				$user = User::Login( $input->username, md5( $input->password ), $input->rememberme ? TRUE : FALSE );

				if( $user )
				{
                    $_SESSION[ 'user_notification' ][] = array( 'text' => "User {$admin->username} logged in.", 'type' => 'notice' );
					$_SESSION[ 'logged_user' ] = $user;
					self::Redirect( "/" );
				}
				else
				{
					$_SESSION[ 'user_notification' ][] = array( 'text' => "Login failed.", 'type' => 'error' );
				}
			}
			else
			{
				$_SESSION[ 'user_notification' ][] = array( 'text' => "Please log in.", 'type' => 'notice' );
			}

			$this->breadcrumbs[] = array( 'link' => null, 'name' => 'User login' );
			$this->assign( 'breadcrumbs', $this->breadcrumbs );
			echo $this->Decorate( 'user/login.tpl' );

		}

		function Logout()
		{
			User::Logout();
			$_SESSION[ 'user_notification' ][] = array( 'text' => "You are logged out.", 'type' => 'notice' );
			self::Redirect( "/" );
		}

		function Register()
		{
			$form = new Form( '/User/Register', 'post' );

			$form->fields['register_username']			= new FormField( 'register_username', 'Username' );
			$form->fields['register_password']			= new FormField( 'register_password', 'Password', 'password' );
			$form->fields['register_confirm_password']	= new FormField( 'register_confirm_password', 'Confirm password', 'password' );
			$form->fields['register_email']				= new FormField( 'register_email', 'Your email' );

			$form->submit = array( 'type' => 'button', 'value' => 'Submit', 'class' => 'button' );

			if( $form->posted )
			{
				$form->fields['register_username']->Validation( 'Required' );
				$form->fields['register_email']->Validation( 'Required' );
				$form->fields['register_email']->Validation( 'Email' );
				$form->fields['register_email']->Validation( 'InDatabase', null, 'user', 'email' );
				$form->fields['register_password']->Validation( 'Length', null, 5 );
				$form->fields['register_password']->Validation( 'Match', null, $form->fields['register_confirm_password']->value );
				$form->fields['register_confirm_password']->Validation( 'Match', null, $form->fields['register_password']->value );

				if( $form->Validate() )
				{
					$user = new User();
					$user->username = $form->fields['register_username']->value;
					$user->password = md5( $form->fields['register_password']->value );
					$user->email = $form->fields['register_email']->value;
					$user->Save();

					User_Role::Add( $user->id, 'user' );
					$_SESSION[ 'user_notification' ][] = array( 'text' => "You are successfully registered.", 'type' => 'notice' );
					User::Login( $user->username, $user->password );
					self::Redirect( '/' );
				}
			}

			$this->breadcrumbs[] = array( 'link' => null, 'name' => 'User registration' );
			$this->assign( 'breadcrumbs', $this->breadcrumbs );
			$this->assign( 'form', $form );
			echo $this->Decorate( 'user/registration.tpl' );
		}

	}
