<?php

class CheckoutController extends Controller
{
	function Index()
	{
		self::Redirect( "/Checkout/YourDetails" );
	}

	function Review()
	{
		$this->assign( 'basket', $_SESSION[ 'basket' ] );
		$this->assign( 'shipping', Shipping::Retrieve( $_SESSION[ 'shipping' ] ) );
		echo $this->Decorate( 'checkout/order_review.tpl' );
	}

	function YourDetails()
	{
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
		{
			$input = Common::Inputs( array( 'title', 'firstname', 'lastname', 'address1', 'address2', 'postcode', 'city', 'country', 'email', 'confirm_email', 'phone', 'note', 'tnc' ), INPUT_POST );

			$error = CheckoutController::YourDetailsValidate( $input );

			if( !$error )
			{
				$_SESSION[ 'customer_details' ] = $input;
				self::Redirect( "/Payment/" );
			}

			$this->assign( 'error', $error );
		}
		elseif( $_SESSION[ 'customer_details' ] )
			$input = $_SESSION[ 'customer_details' ];

		$this->assign( 'customer_details', $input );
		$this->Assign( 'countries', Country::GetAll() );
		echo $this->Decorate( 'checkout/customer_details.tpl' );
	}

	function Success( $order )
	{
		unset( $_SESSION[ 'basket' ] );
		$this->assign( 'order', $order );
		echo $this->Decorate( "basket/success.tpl" );
	}

	function Failure()
	{
		echo $this->Decorate( "basket/failure.tpl" );
	}

	function Cancel()
	{
		self::Redirect( "/Checkout/Failure" );
	}

	// pivate emethods
	private function YourDetailsValidate( $input )
	{
		$required_fields = array( 'firstname', 'lastname', 'address1', 'postcode', 'city', 'country', 'email', 'confirm_email' );

		foreach( $required_fields as $field )
		{
			if( strlen( $input->$field ) < 1 )
			{
				$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'Please fill all required fields (marked with *)' );
				return $field;
			}
		}

		$pattern = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])' .'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';

		if( !preg_match ( $pattern, $input->email ) )
		{
			$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'Please enter valid email address.' );
			return 'email';
		}

		if( $input->email !== $input->confirm_email )
		{
			$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'Confirmed email does not match.' );
			return 'confirm_email';
		}

		if( !$input->tnc )
		{
			$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'You must accept our Terms and Conditions to continue.' );
			return 'tnc';
		}
	}
}
