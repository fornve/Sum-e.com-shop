<?php

	/**
	 * @package shop
	 * @subpackage controller
	 */
	class PaymentController extends Controller
	{
		function Index()
		{
			$_SESSION[ 'order' ] = Order::PlaceOrder( $_SESSION[ 'customer_details' ] );// <-- to be finished!
			
			$this->assign( 'customer', $_SESSION[ 'customer_details' ] );
			$this->assign( 'basket', $_SESSION[ 'basket' ] );
			$this->assign( 'customer_country', Country::Retrieve( $_SESSION[ 'customer_details' ]->country ) );
			$this->assign( 'shipping', Shipping::Retrieve( $_SESSION[ 'shipping' ] ) );
			echo $this->Decorate( "payment/choose_payment_method.tpl" );
		}

		function PostalOrder()
		{
			if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			{
				$input = Common::Inputs( array( 'firstname', 'lastname', 'address', 'postcode', 'city', 'country', 'email', 'phone', 'tnc' ), INPUT_POST );

				$error = PaymentController::PostalOrderValidate( $input );

				if( !$error )
				{
					$_SESSION[ 'order' ] = Order::PlaceOrder( $input );

					self::Redirect( "/Checkout/Success/" );
				}

				$this->Assign( 'error', $error );
			}

			$this->Assign( 'countries', Country::GetAll() );
			echo $this->Decorate( 'payment/postal_order.tpl' );

		}

		function PaypalIPN( $order_id )
		{
			error_log( var_export( $_SERVER, true ) );
			error_log( var_export( $_POST, true ) );
			
			//if( ( PRODUCTION && gethostbyaddr( $_SERVER['REMOTE_ADDR'] ) == 'ipn.paypal.com' ) || gethostbyaddr( $_SERVER['REMOTE_ADDR'] ) == 'ipn.sandbox.paypal.com' )
			if( 1 )
			{
				if( $order_id )
				{
					if( $_POST['payment_status'] == 'Completed' )
					{
						$order = Order::Retrieve( $order_id );

						if( $order->status == 'Despatched' )
						{
							$order->Undespatch( 'By Paypal IPN' );
							$order->Despatch();
						}
						elseif( $order->status == 'Completed' || $order->status == 'Undespatched' )
						{
							$order->Uncomplete( 'By Paypal IPN' );
							$order->UpdateStock( 'order' );
						}
						else
							$order->UpdateStock( 'order' );

					}

					Order_Status_History::Add( $order_id, $_POST[ 'payment_status' ], $_POST[ 'txn_id' ], $_POST[ 'payer_email' ], $_POST[ 'mc_gross' ] );


				}

				error_log( var_export( "Paypal ipn for order {$order_id}", true ) );
			}
			else
			{
				error_log( var_export( "Possible hacker attack! {$order_id}", true ) );
			}
			
			error_log( 'Testing notice: not crashed. ;)' );
		}
		
		// private methods

		private static function PostalOrderValidate( $input )
		{
			$required = array( 'firstname', 'lastname', 'address', 'postcode', 'city', 'country', 'email' );

			foreach( $required as $field )
			{
				if( strlen( $input->$field ) < 2 )
				{
					$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'Please fill all required fields marked with *' );
					$error[ $field ] = true;
					return $error;
				}
			}

			if( $input->tnc != 'accepted' )
			{
				$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'You must accept our Terms and Conditions' );
				$error[ 'tnc' ] = true;
				return $error;
			}
			return false;
		}
	}
