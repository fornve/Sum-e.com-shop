<?php

class TestController extends Controller
{
	function __construct()
	{
		if( Admin::CheckIfAny() )
			self::Redirect( '/' );

		parent::__construct();
	}

	function FirstTime()
	{
		$assets_test = @file_put_contents( PROJECT_PATH . '/assets/test.txt', 'test' );

		if( $assets_test )
			unlink( PROJECT_PATH . '/assets/test.txt' );

		$form = new Form( '/Test/FirstTime', 'post' );

		$form->fields['register_username']			= new FormField( 'register_username', 'Admin username' );
		$form->fields['register_password']			= new FormField( 'register_password', 'Password', 'password' );
		$form->fields['register_confirm_password']	= new FormField( 'register_confirm_password', 'Confirm password', 'password' );

		$form->submit = array( 'type' => 'button', 'value' => 'Create' );

		if( $form->posted )
		{
			$form->fields['register_username']->Validation( 'Required' );
			$form->fields['register_password']->Validation( 'Length', null, 5 );
			$form->fields['register_password']->Validation( 'Match', null, $form->fields['register_confirm_password']->value );
			$form->fields['register_confirm_password']->Validation( 'Match', null, $form->fields['register_password']->value );

			if( $form->Validate() )
			{
				$user = new Admin();
				$user->username = $form->fields['register_username']->value;
				$user->password = md5( $form->fields['register_password']->value );
				$user->vendor = 1;
				$user->Save();

				//User_Role::Add( $user->id, 'login' );
				$_SESSION[ 'user_notification' ][] = array( 'text' => "Admin successfully created.", 'type' => 'notice' );
				Admin::Login( $user->username, $user->password );
				self::Redirect( '/' );
			}
		}

		$this->assign( 'assets_test', $assets_test );
		$this->assign( 'form', $form );
		echo $this->Decorate( 'admin/tests.tpl' );
	}
}
