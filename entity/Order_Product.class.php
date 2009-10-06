<?php
	class Order_Product extends Entity
	{
		protected $schema = array( 'id', 'order', 'product', 'quantity', 'item_value', 'tax', 'variant' );

		static function Retrieve( $id, $nocache = false )
		{
			$query = "SELECT * FROM order_product WHERE id = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			$object->product = Product::Retrieve( $object->product, $nocache );

			return $object;
		}

		static function Add( $order_id, $product_id, $quantity = null, $item_value = null, $tax = null, $variant = null )
		{
			$query = "INSERT INTO order_product ( `order`, `product`, `quantity`, `item_value`, `tax`, `variant` ) VALUES ( ?, ?, ?, ?, ?, ? )";
			$entity = new Entity();
			$entity->Query( $query, array( $order_id, $product_id, $quantity, $item_value, $tax, $variant ) );
		}

		static function OrderCollection( $order_id )
		{
            $query = "SELECT * FROM order_product WHERE `order` = ?";
            $entity = new Entity();
            $result = $entity->Collection( $query, $order_id );

            if( $result ) 
			{
				$basket = new Basket();

				foreach( $result as $item )
				{
					$basket->Add( $item->product, $item->quantity, $item->item_value, $item->tax, $item->variant );
				}
			}

            return $basket;
		}

		static function OrderCollectionObjects( $order_id )
		{
            $query = "SELECT * FROM order_product WHERE `order` = ?";
            $entity = new Entity();
            $result = $entity->Collection( $query, $order_id );

            if( $result )
			{
				foreach( $result as $item )
				{
					$products[] = Product::Retrieve( $item->product );
				}
			}

            return $products;
		}
	}
