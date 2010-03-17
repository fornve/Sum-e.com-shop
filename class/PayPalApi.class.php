<?php

class PayPalApi
{
	const CURRENCYCODE = 'GBP';

	function CreateSubscription( $input )
	{
		if( $input ) foreach( $input as $key => $value )
		{
			$fields[ strtoupper( $key ) ] = $value;
		}

		$fields[ 'CURRENCYCODE' ]		= PayPalApi::CURRENCYCODE;
		$fields[ 'PROFILESTARTDATE' ]	= gmdate('Y-m-d\TH:i:s.00\Z', time() );	// The date when billing for this profile begins.
		$fields[ 'BILLINGPERIOD' ]		= 'Day';												// Day, Week, SemiMonth, Month, Year
		$fields[ 'BILLINGFREQUENCY' ]	= 1;													// See documentation
		$fields[ 'DESC' ]				= $this->billingAgreementDescription;
		
		$result = PayPalApi::PayPalCall( "CreateRecurringPaymentsProfile", $fields );

		if( $result[ "ACK" ] == "Success" )
		{
			$subscription = new Subscription();
			$subscription->profileid = $result[ "PROFILEID" ];
			$subscription->Save();

			$router = new Router();
			$router->profileid = $result[ "PROFILEID" ];
			$router->endpoint = NOTIFY_ENDPOINT;
			$router->Save();
		}

		return $result;
	}

	function GetSubscriptionDetails( $profileid )
	{
		$fields[ "PROFILEID" ] = $profileid;

		$result = PayPalApi::PayPalCall( "GetRecurringPaymentsProfileDetails", $fields );

		if( $result[ "ACK" ] == "Success" )
		{
			$subscription = Subscription::RetrieveFromQuery( "SELECT * FROM subscription WHERE profileid = ?", array( urldecode( $profileid ) ) );
			$subscription->status = $result[ "STATUS" ];
			$subscription->amount = $result[ "AMT" ];
			$subscription->Save();

		}

		return $result;
	}

	// example of use:
	// PayPalApi::TransactionSearch( $result[ "EMAIL" ], gmdate('Y-m-d\TH:i:s.00\Z', strtotime( "yesterday" ) ) );
	function TransactionSearch( $email, $startdate )
	{
		$fields[ "EMAIL" ] = $email;
		$fields[ "STARTDATE" ] = $startdate;
		
		$result = PayPalApi::PayPalCall( "TransactionSearch", $fields );
		ini_set('xdebug.var_display_max_children', 3000 );
		var_dump( $result );
	}

	function ManageSubscription( $profileid, $action )
	{
		$fields[ "PROFILEID" ] = $profileid;
		$fields[ "ACTION" ] = $action;

		$result = PayPalApi::PayPalCall( 'ManageRecurringPaymentsProfileStatus', $fields );
		
		if( $result[ "ACK" ] == 'Success' )
		{
		}

		return $result;
	}

	// private methods

	private function PayPalCall( $method, $args )
	{
		foreach( $args as $key => $value )
		{
			$nvpStr .= "&{$key}=". urlencode( $value );
		}

		$httpParsedResponseAr = PayPalApi::PPHttpPost( $method, $nvpStr );

		return PayPalApi::ResponseHandler( $httpParsedResponseAr, $method );
	}

	private static function PPHttpPost( $methodName_, $nvpStr_)
	{
		$environment = "sandbox";

		$API_UserName = urlencode('dummy_apitest_biz_api1.dajnowski.net');
		$API_Password = urlencode('UBB4JSQN8W4AXNBZ');
		$API_Signature = urlencode('Ajk6WSpRsPj7q.kTdfsixpRieDMpAmbAxUCzAOySNc5izhPIGhb.oXbT');
		$API_Endpoint = "https://api-3t.paypal.com/nvp";

		if("sandbox" === $environment || "beta-sandbox" === $environment)
		{
			$API_Endpoint = "https://api-3t.{$environment}.paypal.com/nvp";
		}
		
		$version = urlencode( '51.0' );

		// setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);

		// NVPRequest for submitting to server
		$nvpreq = "METHOD={$methodName_}&VERSION={$version}&PWD={$API_Password}&USER={$API_UserName}&SIGNATURE={$API_Signature}{$nvpStr_}";

		// setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq );

		// getting response from server
		$httpResponse = curl_exec( $ch );

		if( !$httpResponse )
		{
			exit( "{$methodName_} failed: ". curl_error( $ch ).'('. curl_errno( $ch ).')');
		}

		// Extract the RefundTransaction response details
		$httpResponseAr = explode( "&", $httpResponse);

		$httpParsedResponseAr = array();
		foreach ( $httpResponseAr as $i => $value )
		{
			$tmpAr = explode("=", $value );
			if( sizeof( $tmpAr ) > 1)
			{
				$httpParsedResponseAr[ $tmpAr[ 0 ] ] = $tmpAr[ 1 ];
			}
		}

		if( ( 0 == sizeof( $httpParsedResponseAr ) ) || !array_key_exists('ACK', $httpParsedResponseAr ) ) 
		{
			exit( "Invalid HTTP Response for POST request ({$nvpreq}) to {$API_Endpoint}." );
		}

		return $httpParsedResponseAr;
	}

	private static function ResponseHandler( $httpParsedResponseAr, $method = null )
	{
		if( "Success" == $httpParsedResponseAr[ "ACK" ] )
		{
			return PayPalApi::DecodeResult( $httpParsedResponseAr );
		}
		else
		{
			exit( "{$method} failed:  <pre>" . print_r( $httpParsedResponseAr, true )."</pre>");
		}

	}

	private static function DecodeResult( $input )
	{
		if( $input ) foreach( $input as $key => $value )
		{
			$result[ $key ] = urldecode( $value );
		}

		return $result;
	}
}
