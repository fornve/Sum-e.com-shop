<?php

    class ShippingController  extends Controller
    {
        function Index()
        {
			$this->assign( 'basket_total', $_SESSION[ 'basket' ]->GetTotals() );
			$this->assign( 'shippings', Shipping::GetAllEnabled() );
			echo $this->decorate( 'shipping/index.tpl' );

        }

		function View()
		{
			$input = Common::Inputs( array( 'shipping' ), INPUT_POST );
			
			if( $input )
			{
				$_SESSION[ 'shipping' ] = $input->shipping;
				self::Redirect( "/Checkout/YourDetails/" );
			}
			else
			{
				$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => 'Please select shipping method' );
				self::Redirect( "/Shipping/" );
			}
		}
    }
