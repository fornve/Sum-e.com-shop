<?php

	class SearchController extends Controller
	{
		function Index()
		{
			echo $this->Decorate( 'search/advanced.tpl' );
		}

		function Advanced()
		{
            $this->Assign( 'category_tree',  Category::GetTree() );
			$this->assign( 'price_ranges', Search::PriceRanges() );
			echo $this->Decorate( 'search/advanced.tpl' );
		}

		function Results()
		{
			$sentence = SearchController::GetSearchedSentence();
			$search_vars = SearchController::GetSearchedVars();

			if( $sentence )
			{
				$result = Search::PerformSearch( $sentence, $search_vars );
			}
			$this->assign( 'sentence', $sentence );
			$this->assign( 'search_vars', $search_vars );
			$this->assign( 'result', $result );
			echo $this->Decorate( 'search/results.tpl' );
		}
		
		static function GetSearchedSentence()
		{
			$input = Common::Inputs( array( 'q' ), INPUT_GET );
			return filter_var( $input->q, FILTER_SANITIZE_STRING );
		}

		static function GetSearchedVars()
		{
			// pricerange
			$input = Common::Inputs( array( 'pricerange_1', 'pricerange_2', 'pricerange_3', 'pricerange_4', 'pricerange_5', 'pricerange_6', 'pricerange_8', 'pricerange_9', 'pricerange_10' ), INPUT_GET );

			if( $input ) foreach( $input as $price_range )
			{
				if( $price_range )
					$search_vars->price_range[] = Search::PriceRangeGetByID( $price_range );
			}

			// categories
			$categories = Category::GetAll();
			if( $categories ) foreach( $categories as $category )
			{
				if( filter_input( INPUT_GET, "category_{$category->id}" ) )
					$category_input_vars[] = $category->id;
			}
			$search_vars->categories = $category_input_vars;

			// type
			$input = Common::Inputs( array( 'type' ) );
			if( $input )
				$search_vars->type = $input->type;

			return $search_vars;
		}
	}
