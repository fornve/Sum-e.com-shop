<?php

	class Order_Status_History extends Entity
	{
		protected $schema = array( 'id', 'order', 'status', 'created', 'customer_notified', 'comments', 'payer_id', 'transaction_id', 'total' );

		static function Retrieve( $id )
		{
			$query = "SELECT * FROM order_status_history WHERE id = ?";
			$entity = new Entity();
			$object = $entity->GetFirstResult( $query, $id, __CLASS__ );

			return $object;
		}

		static function OrderCollection( $order_id )
		{
            $query = "SELECT * FROM order_status_history WHERE `order` = ? ORDER BY id DESC";
            $entity = new Entity();
            $result = $entity->Collection( $query, $order_id, __CLASS__ );

            return $result;
		}

		static function Add( $order_id, $payment_status, $transaction_id, $payer_email, $total = 0 )
		{
			$query = "INSERT INTO order_status_history ( `order`, `status`, created, transaction_id, payer_id, total ) VALUES ( ?, ?, now(), ?, ?, ? )";
			$entity = new Entity();
			$entity->Query( $query, array( $order_id, $payment_status, $transaction_id, $payer_email, $total ) );
		}

		static function GetLastForOrder( $order_id )
		{
			$query = "SELECT * FROM order_status_history WHERE `order` = ? ORDER BY id DESC LIMIT 1";
			$entity = new Entity();
			return $entity->GetFirstResult( $query, array( $order_id ), __CLASS__ );
		}
	}