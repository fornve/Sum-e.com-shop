<?php

class TermsAndConditionsController extends Controller
{
	function Index()
	{
		$this->assign( 'page', Page::RetrieveByType( 'tnc' ) );
		echo $this->Decorate( 'page/tnc.tpl' );
	}
}
