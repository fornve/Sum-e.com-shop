<?php

	class SearchController extends Controller
	{
		function Index()
		{
			echo $this->Decorate( 'search/advanced.tpl' );
		}

		function Results()
		{
			$sentence = SearchController::GetSearchedSentence();
			
			if( $sentence )
			{
				$result = Search::PerformSearch( $sentence );
			}
			$this->assign( 'sentence', $sentence );
			$this->assign( 'result', $result );
			echo $this->Decorate( 'search/results.tpl' );
		}
		
		static function GetSearchedSentence()
		{
			$input = Common::Inputs( array( 'q' ), INPUT_GET );
			return filter_var( $input->q, FILTER_SANITIZE_STRING );
		}
	}