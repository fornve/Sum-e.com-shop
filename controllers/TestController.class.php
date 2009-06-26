<?php

	class TestController extends Controller
	{
		function Paypal()
		{
			$paypal = new Paypal( array( 'test_mode' => true ) );

			var_dump( $paypal );

		}

		function DummyIpn()
		{

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://shop.sunforum.co.uk/Payment/PaypalIPN/50');
			curl_setopt($ch, CURLOPT_VERBOSE, 1);

			// turning off the server and peer verification(TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);

			//$data = array ( 'mc_gross' => '400.00', 'protection_eligibility' => 'Ineligible', 'address_status' => 'confirmed', 'item_number1' => '', 'payer_id' => '4S74MMR9ULVQJ', 'tax' => '0.00', 'address_street' => '33 Tooker Road', 'payment_date' => '11:55:00 Apr 15, 2009 PDT', 'payment_status' => 'Pending', 'charset' => 'windows-1252', 'address_zip' => 'S60 2PS', 'mc_shipping' => '0.00', 'mc_handling' => '0.00', 'first_name' => 'Test', 'mc_fee' => '13.80', 'address_country_code' => 'GB', 'address_name' => 'Marek Dajnowski', 'notify_version' => '2.8', 'custom' => '', 'payer_status' => 'verified', 'business' => 'dummy_1227518617_biz@dajnowski.net', 'address_country' => 'United Kingdom', 'num_cart_items' => '1', 'mc_handling1' => '0.00', 'address_city' => 'Rotherham', 'verify_sign' => 'AC1pkemjMoOoKHCsg0VIaBF5YdGOAllxcnsISxnDfIsGUuvAzokQ4Knr', 'payer_email' => 'dummy_1227518590_per@dajnowski.net', 'mc_shipping1' => '0.00', 'tax1' => '0.00', 'txn_id' => '1S7714175M662390K', 'payment_type' => 'instant', 'last_name' => 'User', 'address_state' => 'SD', 'item_name1' => 'Nokia 3250', 'receiver_email' => 'dummy_1227518617_biz@dajnowski.net', 'payment_fee' => '', 'quantity1' => '2', 'receiver_id' => 'BTUTAPNJSLFGG', 'pending_reason' => 'paymentreview', 'txn_type' => 'cart', 'mc_gross_1' => '400.00', 'mc_currency' => 'GBP', 'residence_country' => 'GB', 'test_ipn' => '1', 'transaction_subject' => 'Shopping Cart', 'payment_gross' => '' );
			$data = array (  'mc_gross' => '20.00',  'protection_eligibility' => 'Eligible',  'address_status' => 'confirmed',  'item_number1' => '',  'payer_id' => '4S74MMR9ULVQJ',  'tax' => '0.00',  'address_street' => '33 Tooker Road',  'payment_date' => '10:51:36 Apr 18, 2009 PDT',  'payment_status' => 'Completed',  'charset' => 'windows-1252',  'address_zip' => 'S60 2PS',  'mc_shipping' => '0.00',  'mc_handling' => '0.00',  'first_name' => 'Test',  'mc_fee' => '0.88',  'address_country_code' => 'GB',  'address_name' => 'Marek Dajnowski',  'notify_version' => '2.8',  'custom' => '',  'payer_status' => 'verified',  'business' => 'dummy_1227518617_biz@dajnowski.net',  'address_country' => 'United Kingdom',  'num_cart_items' => '1',  'mc_handling1' => '0.00',  'address_city' => 'Rotherham',  'verify_sign' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AmoxGWr0tNk18g5ckZ0MXg-Dir.V',  'payer_email' => 'dummy_1227518590_per@dajnowski.net',  'mc_shipping1' => '0.00',  'txn_id' => '1CK418128J798292V',  'payment_type' => 'instant',  'last_name' => 'User',  'address_state' => 'SD',  'item_name1' => 'Wednesday techno',  'receiver_email' => 'dummy_1227518617_biz@dajnowski.net',  'payment_fee' => '',  'quantity1' => '2',  'receiver_id' => 'BTUTAPNJSLFGG',  'txn_type' => 'cart',  'mc_gross_1' => '20.00',  'mc_currency' => 'GBP',  'residence_country' => 'GB',  'test_ipn' => '1',  'transaction_subject' => 'Shopping Cart',  'payment_gross' => '');
			// setting the nvpreq as POST FIELD to curl
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data );

			// getting response from server
			$httpResponse = curl_exec( $ch );

			var_dump( $httpResponse );
		}
	}