<?php

class BasketController  extends Controller
{
	function Index()
	{
		$this->View();
	}

	function View()
	{
		$this->assign( 'basket', $_SESSION[ 'basket' ] );
		echo $this->Decorate( 'basket/view.tpl' );
	}

	function Add( $id )
	{
		if( !$_SESSION[ 'basket' ] )
			$_SESSION[ 'basket' ] = new Basket();

		// get product
		$product = Product::Retrieve( $id );
		$product_value = $product->price;

		// prepare inputs
		$parameters = array( 'quantity' );
		if( $product->variants ) foreach( $product->variants as $variant )
		{
			$variant_parameters[] = "variant_{$variant->type}";
			$parameters[] = "variant_{$variant->type}";
		}

		$input = Common::Inputs( $parameters, INPUT_POST );

		if( $variant_parameters ) foreach( $variant_parameters as $key )
		{
			if( $input->$key )
				$variants[ $input->$key ] = $input->$key;

		}

		// Count product variant value
		if( $variants )
		{
			foreach( $variants as $variant )
			{
				$variant = Variant::Retrieve( $variant );
				$price_change += $variant->price_change;
			}
			
			$product_value = $product_value * ( 100 + $price_change ) / 100;
		}

		// add to basket
		if( $product->id && $input->quantity )
		{
			$_SESSION[ 'basket' ]->Add( $product->id, $input->quantity, $product_value, $product->tax->value, serialize( $variants ) );
		}

		if( !filter_input( INPUT_GET, 'ajax' ) )
		{
			self::Redirect( $_SERVER[ 'HTTP_REFERER' ] );
		}
		else
		{
			echo $this->Decorate( 'basket/mini.tpl' );
		}
	}

	function Update()
	{
		$items = filter_input( INPUT_POST, 'items' );

		for( $i=1; $i<=$items; $i++)
		{
			$key_id = "id_{$i}";
			$key_product = "product_{$i}";
			$key_variant = "variant_{$i}";
			$key_quantity = "quantity_{$i}";
			$key_delete = "delete_{$i}";

			$input = Common::Inputs( array( $key_id, $key_product, $key_variant, $key_quantity, $key_delete ), INPUT_POST );

			if( $input->$key_delete )
			{
				$_SESSION[ 'basket' ]->Delete( $input->$key_product, stripslashes( $input->$key_variant ) );
			}
			else
			{
				$_SESSION[ 'basket' ]->Set( $input->$key_product, $input->$key_quantity, stripslashes( $input->$key_variant ) );
			}

		}

		self::Redirect( $_SERVER[ 'HTTP_REFERER' ] );
	}

	function Wipe()
	{
		unset( $_SESSION[ 'basket' ] );
		$_SESSION[ 'user_notification' ][] = array( 'type' => 'notify', 'Basket wiped off.' );
		self::Redirect( $_SERVER[ 'HTTP_REFERER' ] );
	}
}
