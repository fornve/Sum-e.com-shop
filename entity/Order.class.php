<?php

    class Order extends Entity
    {
        protected $schema =
                array( 'id',
                 'customer', 'customer_name', 'customer_company', 'customer_address', 'customer_suburb', 'customer_city', 'customer_postcode', 'customer_county', 'customer_country', 'customer_phone', 'customer_email', 'customer_note', 'customer_ip_address',
                 'delivery_name', 'delivery_company', 'delivery_address', 'delivery_city', 'delivery_postcode', 'delivery_county', 'delivery_country',
                 'billing_name', 'billing_company', 'billing_address', 'billing_city', 'billing_postcode', 'billing_county', 'billing_country',
                 'payment', 'last_modified', 'purchase_date', 'status', 'finished_date', 'currency', 'value', 'shipping', 'shipping_value', 'vendor', 'despatched'
                );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

            $query = "SELECT * FROM `order` WHERE id = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			if( $object->id )
			{
				$object->products = Order_Product::OrderCollection( $object->id ); // this is Basket class
				$object->status_history = Order_Status_History::OrderCollection( $object->id );
			}

            return $object;
        }

		static function PlaceOrder( $input )
		{ 
			$totals = $_SESSION[ 'basket' ]->GetTotals();
			$order = new Order();
			$order->customer_name		= "{$input->title} {$input->firstname} {$input->lastname}";
			$order->customer_address	= $input->address1;

				if( strlen( $input->address1 ) > 0 )
					$order->customer_address .= ', '. $input->address2;

			$order->customer_city		= $input->city;
			$order->customer_postcode	= $input->postcode;
			$order->customer_country	= $input->country;
			$order->customer_email		= $input->email;
			$order->customer_phone		= $input->phone;
			$order->customer_note		= $input->note;
			$order->value				= $totals[ 'value' ];
			$order->purchase_date		= date( "Y-m-d H:i:s" );
			$order->customer_ip_address = $_SERVER[ 'REMOTE_ADDR' ];
			
			if( $_SESSION[ 'shipping' ] )
			{
				$shipping = Shipping::Retrieve( $_SESSION[ 'shipping' ] );
				$order->shipping			= $shipping->id;
				$order->shipping_value		= $shipping->Value( $totals[ 'value' ] );
			}
			
			$order->Save();

			foreach( $_SESSION[ 'basket' ]->items as $product_id => $group )
			{
				foreach( $group as $variant => $product )
					$order_product = Order_Product::Add( $order->id, $product_id, $product[ 'quantity' ], $product[ 'item_value' ], $product[ 'tax' ], $variant );
			}

			//unset( $_SESSION[ 'basket' ] );

			return $order->id;
		}

		static function AdminCollection( $page, $order )
		{
			return Order::AdminDateRangeCollection( $page, $order, date( 'Y-m-d H:i:s', 0 ), date( 'Y-m-d H:i:s', time() ) );
		}

		static function AdminDateRangeCollection( $page, $order, $date_from, $date_to )
		{
			$query = "SELECT * FROM `order` WHERE value > 0 AND purchase_date > ? AND purchase_date < ? ";

			if( is_integer( $date_from ) )
				$date_from = date( "Y-m-d H:i:s", $date_from );

			if( is_integer( $date_to ) )
				$date_to = date( "Y-m-d H:i:s", $date_to );

			$args[] = $date_from;
			$args[] = $date_to;

			if( $order )
			{
				$args[] = $order;
				$query .= "ORDER BY `?` DESC ";
			}
			else
			{
				$query .= "ORDER BY id DESC ";
			}

			$query .= " LIMIT 50";

			if( $page )
			{
				$args[] = $page;
				$query .= ", ?";
			}

			$entity = new Entity();

			$result = $entity->Collection( $query, $args, __CLASS__ );
	
			return $result;

		}

		function GetStatus()
		{
			$status = Order_Status_History::GetLastForOrder( $this->id );
			return $status->status;
		}

		function UpdateStock( $action )
		{
			if( $this->products )
				$this->products->UpdateStock( $action );
		}

		function Despatch()
		{
			Order_Status_History::Add( $this->id, 'Despatched', '', '', $this->value );
			$this->finished_date = date( "Y-m-d H:i:s" );
			$this->Save();
		}

		function Uncomplete( $message = null )
		{
			Order_Status_History::Add( $this->id, 'Uncomplete', $message, '', $this->value );
			$this->UpdateStock( 'unorder' );
		}

		function Undespatch( $message )
		{
			$this->UpdateStock( 'undespatch' );
			Order_Status_History::Add( $this->id, 'Undespatched', $message, '', $this->value );
		}

		static function Sales( $orders )
		{
			$sales = new stdClass();

			if( $orders ) foreach( $orders as $order )
			{
				$order_status = $order->GetStatus();
				
				if( $order_status == 'Completed' || $order_status == 'Despatched' )
				{
					$sales->count++;
					$sales->value += $order->value;
				}
			}

			return $sales;
		}
    }
