<?php

class TermsAndConditionsController extends Controller
{
	function Index()
	{
		$page = Page::RetrieveByType( 'tnc' );
		$this->assign( 'page', $page );
		$this->assign( 'breadcrumbs', array( array( 'name' => $page->title ) ) );
		echo $this->Decorate( 'page/tnc.tpl' );
	}
}
