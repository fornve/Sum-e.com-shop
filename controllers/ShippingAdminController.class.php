<?php

class ShippingAdminController extends AdminController
{
	public $breadcrumbs = array( array( 'link' => '/ShippingAdmin/', 'name' => 'Shipping Admin' ) );

	function Index()
	{
		$this->ChangeStatus();

		$this->assign( 'shippings', Shipping::GetAll() );
		echo $this->Decorate( 'admin/shipping/index.tpl' );
	}


	function Edit( $id )
	{

		if( $id )
				$shipping = Shipping::Retrieve( $id, true );

		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
		{
			if( !$shipping )
					$shipping = new Shipping();

			$input = Common::Inputs( array( 'name', 'flat_value', 'description', 'limit_price', 'enabled' ), INPUT_POST );

			$shipping->name = $input->name ? $input->name : 'unnamed';
			$shipping->flat_value = $input->flat_value;
			$shipping->limit_price = $input->limit_price;
			$shipping->description = $input->description;
			$shipping->enabled = $input->enabled;
			$shipping->Save();

			Log::Add( "SHIPPING_UPDATED", 'SHIPPING', $_SESSION[ 'admin' ]->id, "{$shipping->name}" );
			$_SESSION['user_notification'][] = array( 'text' => "Shipping {$shipping->name} saved.", 'type' => 'notice'  );
		}

		if( $shipping->name )
			$this->breadcrumbs[] = array( 'name' => "Shipping edit: {$shipping->name}" );
		else
			$this->breadcrumbs[] = array( 'name' => "New Shipping" );

		$this->Assign( 'breadcrumbs', $this->breadcrumbs );
		$this->Assign( 'shipping', $shipping );
		echo $this->Decorate( 'admin/shipping/edit.tpl' );
	}

	private function ChangeStatus()
	{
		$input = Common::Inputs( array( 'enable', 'disable' ) );
		
		if( $input )
		{
			if( $input->enable )
			{
				$shipping = Shipping::Enable( $input->enable );
				$_SESSION[ 'user_notification' ][] = array( "type" => 'notice', 'text' => "Shipping methd `{$shipping->name}` enabled" );
			}

			if( $input->disable )
			{
				$shipping = Shipping::Disable( $input->disable );
				$_SESSION[ 'user_notification' ][] = array( "type" => 'notice', 'text' => "Shipping methd `{$shipping->name}` disabled" );
			}
		}
	}
}
