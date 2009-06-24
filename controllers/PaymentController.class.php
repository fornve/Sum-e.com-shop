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

			$this->assign( 'tax_rate', 100 * Config::GetVat() );
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

		function PaypalproIPN()
		{
				error_log( "Paypal IPN notification!" );
				error_log( var_export( $_SERVER, true ) );
				error_log( var_export( $_POST, true ) );
		}

		function PayPalPro()
		{
			// paypal sandbox accpunt: dummy_paypalpro_biz@dajnowski.net
			// API Username 	dummy_paypalpro_biz_api1.dajnowski.net
			// API Password 	DAAKZU425K24SJC6
			// Signature		AFcWxV21C7fd0v3bYYYRCpSSRl31A0UyfRVApsgX0FLWdkqUYqsHvLXR
			// Endpoint			http://shop.sunforum.co.uk/Payment/PaypalproIPN/

			$startdate = $expdate = time();
			if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			{
				$input_values = array( 'firstname', 'lastname', 'phone', 'email', 'street', 'city', 'country', 'zip', 'creditcardtype', 'acct', 'cvv2' );
				
				$input_values = $input_values;
				$additional_input_values = array( 'expdate_Month', 'expdate_Year', 'startdate_Month', 'startdate_Year' );
				
				$input = Common::Inputs( array_merge( $input_values, $additional_input_values ), INPUT_POST );

				$startdate = mktime( 0, 0, 0, $input->startdate_Month, 1, $input->startdate_Year );
				$expdate = mktime( 0, 0, 0, $input->expdate_Month, 1, $input->expdate_Year );
				$config = array(
						'test_mode' => TRUE,
						'SANDBOX_USER' => 'dummy_paypalpro_biz_api1.dajnowski.net',
						'SANDBOX_PWD' => 'DAAKZU425K24SJC6',
						'SANDBOX_SIGNATURE' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31A0UyfRVApsgX0FLWdkqUYqsHvLXR',
						'SANDBOX_ENDPOINT' => 'https://api-3t.sandbox.paypal.com/nvp',
						'VERSION' => '3.2',
						'CURRENCYCODE' => 'USD',
					'curl_config'  => array
					(
						CURLOPT_HEADER         => FALSE,
						CURLOPT_SSL_VERIFYPEER => FALSE,
						CURLOPT_SSL_VERIFYHOST => FALSE,
						CURLOPT_VERBOSE        => TRUE,
						CURLOPT_RETURNTRANSFER => TRUE,
						CURLOPT_POST           => TRUE
					)
				);

				$payment = new Paypalpro( $config );

				foreach( $input_values as $key )
				{
						$fields[ strtoupper( $key ) ] = $input->$key;
				}

				$fields[ 'STARTDATE' ] = date( "mY", $startdate );
				$fields[ 'EXPDATE' ] = date( "mY", $expdate );

				$fields[ 'AMT' ] = 10;
				$fields[ 'METHOD' ] = 'DoDirectPayment';
				$fields[ 'PAYMENTACTION' ] = 'Sale';

				$payment->set_fields( $fields );
				var_dump( $payment->process() );
			}
			var_dump( $input );
			$this->assign( 'expdate', $expdate );
			$this->assign( 'startdate', $startdate );
			$this->assign( 'payment_input', $input );
			$this->assign( 'countries', Country::GetAll() );
			echo $this->fetch( 'payment/paypalpro.tpl' );
		}
	}
