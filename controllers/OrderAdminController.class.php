<?php

class OrderAdminController extends AdminController
{
	public $breadcrumbs = array( array( 'link' => '/Admin/', 'name' => 'Admin' ), array( 'link' => '/OrderAdmin/', 'name' => 'Orders' ) );

	function Index( $page = 0, $order = null, $filter = null )
	{
		$input = Common::Inputs( array( 'date_from', 'date_to', 'page' ) );

		if( $input->date_from  )
			$date_from = strtotime( $input->date_from );

		if( $input->date_to )
			$date_to = strtotime( $input->date_to );


		if( $date_from )
			$_SESSION[ 'orders_date_from' ] = $date_from;
		elseif( !$date_from && $_SESSION[ 'orders_date_from' ] )
			$date_from = $_SESSION[ 'orders_date_from' ];
		else
			$date_from = $_SESSION[ 'orders_date_from' ] = strtotime( 'yesterday' );

		if( $date_to )
			$_SESSION[ 'orders_date_to' ] = $date_to;
		elseif( !$date_to && $_SESSION[ 'orders_date_to' ] )
			$date_to = $_SESSION[ 'orders_date_to' ];
		else
			$date_to = time();

		$orders = Order::AdminDateRangeCollection( $page, $order, $date_from, $date_to, 50, $input->page );
		$this->assign( 'breadcrumbs', $this->breadcrumbs );
		$this->assign( 'sales', Order::Sales( $orders ) );
		$this->assign( 'date_from', $date_from );
		$this->assign( 'date_to', $date_to );
		$this->assign( 'orders', $orders );
			
		echo $this->Decorate( "admin/order/index.tpl" );
	}

	function Sales( $page, $order = null, $filter = null  )
	{
		$this->breadcrumbs[] = array( 'link' => '/OrderAdmin/Sales/', 'name' => 'Sales' );
		$display_options = array( 'status' => 'Completed' );
		$this->assign( 'display_options', $display_options );
		$this->Index(  $page, $order, $filter );
	}

	function View( $id )
	{
		$order = Order::Retrieve( $id );
		$this->breadcrumbs[] = array( 'link' => "", 'name' => "Order {$order->id} Preview" );
		$this->assign( 'customer_country', Country::Retrieve( $order->customer_country ) );
		$this->assign( 'order', $order );
		$this->assign( 'basket', $order->products );
		$this->assign( 'shipping', Shipping::Retrieve( $order->shipping ) );
		$this->assign( 'breadcrumbs', $this->breadcrumbs );
		echo $this->Decorate( "admin/order/view.tpl" );
	}

	function FullReport()
	{
		$this->breadcrumbs[] = array( 'link' => '/OrderAdmin/Sales/', 'name' => 'Sales' );
		$display_options = array( 'status' => 'Completed' );
		$this->assign( 'display_options', $display_options );
		$this->assign( 'breadcrumbs', $this->breadcrumbs );
		$this->Index(  $page, $order, $filter );
	}

	function Despatch( $id )
	{
		$this->breadcrumbs[] = array( 'link' => "", 'name' => "Order {$order->id} Preview" );
		$order = Order::Retrieve( $id );
		$order->UpdateStock( 'despatch' );

		if( $order->GetStatus() != 'Despatched' )
		{
			$order->Despatch();
			$order = Order::Retrieve( $id );
		}
		
		$this->assign( 'order', $order );
		$this->assign( 'basket', $order->products );
		$this->assign( 'breadcrumbs', $this->breadcrumbs );
		echo $this->Decorate( "admin/order/view.tpl" );
	}

	function UnComplete( $id )
	{
		$this->breadcrumbs[] = array( 'link' => "", 'name' => "Order {$order->id} Preview" );
		$order = Order::Retrieve( $id );

		if( $order->GetStatus() != 'Uncompleted' )
		{
			$order->Uncomplete( $message );
			$order = Order::Retrieve( $id );
		}
		
		$this->assign( 'order', $order );
		$this->assign( 'basket', $order->products );
		$this->assign( 'breadcrumbs', $this->breadcrumbs );
		echo $this->Decorate( "admin/order/view.tpl" );
	}

	function UnDespatch( $id )
	{
		$this->breadcrumbs[] = array( 'link' => "", 'name' => "Order {$order->id} Preview" );
		$order = Order::Retrieve( $id );
		
		if( $order->GetStatus() != 'Undespatched' )
		{
			$order->Undespatch( $message );
			$order = Order::Retrieve( $id );
		}
		
		$order = Order::Retrieve( $id );
		$this->assign( 'order', $order );
		$this->assign( 'basket', $order->products );
		$this->assign( 'breadcrumbs', $this->breadcrumbs );
		echo $this->Decorate( "admin/order/view.tpl" );
	}

	function Update( $id )
	{
		$this->breadcrumbs[] = array( 'link' => "", 'name' => "Order {$order->id} Preview" );
		$order = Order::Retrieve( $id );

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
		{
			$input = Common::Inputs( array( 'status', 'payer_id', 'transaction_id' ), INPUT_POST );

			if( $input->status != $order->GetStatus() )
			{
				Order_Status_History::Add( $order->id, $input->status, $input->transaction_id, $input->payer_id, $order->products->GetTotals() );
				
				if( $input->status == 'Cancelled' )
				{
					// do nothing with stock
				}
				elseif( $input->status == 'Completed' )
				{
					$order->UpdateStock( 'order' );
				}
				
				$order = Order::Retrieve( $id );
			}
			else
			{
				$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'Order status remains the same.' );
			}
		}
		else
			$this->assign( 'update', true );

		$this->assign( 'order', $order );
		$this->assign( 'breadcrumbs', $this->breadcrumbs );
		$this->assign( 'basket', $order->products );
		echo $this->Decorate( "admin/order/view.tpl" );
	}

}
