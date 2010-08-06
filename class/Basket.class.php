<?php

class Basket
{
	public $items; // it is array of ( item, value, quantity, tax )
	public $tax = 0;
	public $shipping;

	function GetTotals()
	{
		$total = array( "weight" => 0, "quantity" => 0, "value" => 0, "tax" => 0 );

		if( $this->items ) foreach( $this->items as $product_id => $item )
		{

			if( $item ) foreach( $item as $variant )
			{
				$product = Product::Retrieve( $product_id );
				$total[ "weight" ] += $variant[ 'quantity' ] * $product->weight;
				$total[ "quantity" ] += $variant[ 'quantity' ];
				$total[ "value" ] += $variant[ 'quantity' ] * $variant[ 'item_value' ];

				if( $this->tax )
				{
					$total[ "tax" ] += round( $this->tax * $variant[ 'quantity' ] * $variant[ 'item_value' ] / 100, 2 );
				}
			}
		}

		return $total;
	}

	function Add( $id, $quantity, $item_value, $item_tax = null, $variants = 'base', $shipping_id = null )
	{
		$product = Product::Retrieve( $id, true );
		$quantity_in_basket = $this->items[ $id ][ $variants ][ 'quantity' ];

		if( $product->quantity != -1 && !$product->variants && $product->quantity < $quantity + $quantity_in_basket  )
		{
			$quantity = $product->quantity - $quantity_in_basket;
			$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => "Product {$product->name} stock is {$product->quantity}."  );
		}

		if( $quantity > 0 )
		{
			$this->items[ $id ][ $variants ][ 'quantity' ] += $quantity;
			$this->items[ $id ][ $variants ][ 'item_value' ] = round( $item_value, 2 );
			$this->items[ $id ][ $variants ][ 'item_tax' ] = $item_tax;
			$this->items[ $id ][ $variants ][ 'shipping' ] = $shipping_id;

			$this->SortBySeller();

			return true;
		}
	}

	function Set( $id, $quantity, $variants = 'base', $shipping_id = null )
	{
		$product = Product::Retrieve( $id, true );
		$quantity_in_basket = $this->items[ $id ][ $variants ][ 'quantity' ];

		if( $product->quantity != -1 && !$product->variants && $product->quantity < $quantity + $quantity_in_basket  )
		{
			$quantity = $product->quantity - $quantity_in_basket;
			$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => "Product {$product->name} stock is {$product->quantity}."  );
		}

		if( $quantity > 0 )
		{
			$this->items[ $id ][ $variants ][ 'quantity' ] = $quantity;
			$this->items[ $id ][ $variants ][ 'shipping' ] = $shipping_id;

			if( $this->items[ $id ][ $variants ][ 'quantity' ] < 1 )
			{
				$this->Delete( $id, $variants );
			}

			$this->SelfCleaning();
		}
	}

	function Remove( $id, $quantity, $variants = 'base' )
	{
		$this->items[ $id ][ $variants ][ 'quantity' ] -= $quantity;

		if( $this->items[ $id ][ $variants ][ 'quantity' ] < 1 )
			unset( $this->items[ $id ][ $variants ] );
		$this->SelfCleaning();
	}

	function Delete( $id, $variants = 'base' )
	{
		unset( $this->items[ $id ][ $variants ] );
		$this->SelfCleaning();
	}

	function GetProduct( $id )
	{
		return Product::Retrieve( $id );
	}

	function GetVariant( $variant_array )
	{
		$variants = unserialize( $variant_array );

		if( $variants ) foreach( $variants as $variant_type => $variant )
		{
			if( is_numeric( $variant_type ) )
			{
				$items[] = Variant::Retrieve( $variant );
			}
			else
			{
				$custom = new stdClass();
				$custom->type = 'custom_text';
				$custom->value = $variant;
				$items[] = $custom;
			}
		}

		return $items;
	}

	function SelfCleaning()
	{
		if( $this->items )
		{
			foreach( $this->items as $key => $item )
			{
				if( count( $item ) < 1 )
				{
					unset( $this->items[ $key ] );
				}
			}
		}
	}

	function UpdateStock( $action )
	{
		if( $this->items )
		{
			foreach( $this->items as $product_id => $variants )
			{

				if( $variants ) foreach( $variants as $variant => $item )
				{
					$variants = unserialize( $variant );

					$product = Product::Retrieve( $product_id );
					$product->UpdateStock( $variants, $item[ 'quantity' ], $action );
				}
			}
		}
	}

	function SerializeItem( $product_id, $variant )
	{
		if( unserialize( $variant ) != null )
		{
			return serialize( array( $product_id, $variant ) );
		}
		else
		{
			return $product_id;
		}
	}

	function SortBySeller()
	{
		$new_items = array();

		foreach( $this->items as $product_id => $item )
		{
			$product = Product::Retrieve( $product_id );
			$new_items[ $product->vendor->id ][ $product_id ] = $item;
		}

		$this->items = array();

		foreach( $new_items as $vendor )
		{
			foreach( $vendor as $product_id => $item )
			{
				$this->items[ $product_id ] = $item;
			}
		}
	}
}
