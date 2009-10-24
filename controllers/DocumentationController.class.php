<?php

class DocumentationController extends Controller
{
	function Index()
	{
		echo $this->Decorate( 'documentation/index.tpl' );
	}

	function DB()
	{
		echo $this->Decorate( 'documentation/db.tpl' );
	}
}
