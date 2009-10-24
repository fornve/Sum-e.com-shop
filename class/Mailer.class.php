<?php
/**
 * @package shop
 * @subpackage framework
 */
class Mailer
{
	public $from_name;
	public $from_email;
	public $to_name;
	public $to_email;
	public $subject;
	public $message;

	function Send()
	{
		if( $this->to_email && $this->subject && $this->message )
		{
			$headers = "From: {$this->from_name} <{$this->from_email}>";
			return mail( $this->to_email, $this->subject, $this->message, $headers );
		}
	}

	function FilteredSend( $advert )
	{
		$forbidden_words = array( 'a href=', 'viagra' );

		foreach( $forbidden_words as $word )
		{
			if( strpos( $this->message, $word ) !== FALSE )
			{
				$stop = true;
				break;
			}
		}

		if( !$stop )
		{
			$this->Send();

			$email = new Mailer();
			$email->from_name = 'Anadvert user';
			$email->from_email = $this->from_email;
			$email->to_name = '';
			$email->to_email = ADMIN_EMAIL;
			$email->subject =  "Control message: [{$advert->id}] ". $advert->title;
			$email->message = "From: {$this->from_email} \nTo: {$advert->email}\n\n". $this->message;
			$email->Send();

		}
	}
}
