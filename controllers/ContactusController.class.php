<?php
/**
 * @package anadvert
 * @subpackage controller
 */
class ContactusController extends Controller
{
	function Index()
	{
		echo $this->decorate( 'contactus/form.tpl' );
	}

	function Send()
	{
		$input = Common::Inputs( array( 'from', 'subject', 'message' ), INPUT_POST );

		if( ContactusController::ValidateSend( $input ) )
		{
			$email = new Mailer();
			$email->from_name = "Shop user";
			$email->from_email = $input->from;
			$email->to_name = 'Shop stuff';
			$email->to_email = ADMIN_EMAIL;
			$email->subject = $input->subject;
			$email->message = $input->message;
			$email->Send();

			echo $this->decorate( 'contactus/success.tpl' );	
		}
		else
			echo $this->decorate( 'contactus/form.tpl' );

	}

	function ValidateSend($input  )
	{
		if( $_SERVER['REQUEST_METHOD'] == 'POST' )
		{
			if( !$input->from )
			{
				$_SESSION['user_notification'][] = array( 'type' => 'error', 'text' => 'Please provide your email' );
				return false;
			}

			if( !$input->subject )
			{
				$_SESSION['user_notification'][] = array( 'type' => 'error', 'text' => 'Please type subject' );
				return false;
			}

			if( !$input->message )
			{
				$_SESSION['user_notification'][] = array( 'type' => 'error', 'text' => 'Please write message' );
				return false;
			}

			return true;

		}
		else
			return false;
	}
}
