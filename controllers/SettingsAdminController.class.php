<?php

    class SettingsAdminController extends AdminController
    {
        public $breadcrumbs = array( array( 'link' => '/Admin/', 'name' => 'Admin' ), array( 'link' => '/SettingsAdmin/', 'name' => 'Settings Admin' ) );

		function Index()
		{
			if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			{
				SettingsAdminController::UpdateConfig();
				SettingsAdminController::UpdateVanedor();
			}

			$this->assign( 'configs', Config::GetAll() );
			$this->assign( 'countries', Country::GetAll() );
			$this->assign( 'vendor', Vendor::Retrieve( $_SESSION[ 'admin' ]->vendor->id ) );
			echo $this->Decorate( 'admin/config/index.tpl' );
		}

		private function UpdateConfig()
		{
			$all_config = Config::GetAll();
			
			if( $all_config ) foreach ( $all_config as $config )
			{
				$input = filter_input( INPUT_POST, "config_{$config->id}" );
				if( $config->value != $input )
				{
					Log::Add( "CONFIGURATION_UPDATED", 'CONFIGURATION', $_SESSION[ 'admin' ]->id, "{$config->title}: {$config->value} -&gt; {$input}" );
					$config->value = $input;
					$config->Save();
					$flush_cache = true;
				}

				if( $flush_cache )
				{
					Config::FlushCache();
				}
			}
		}

		private function UpdateVanedor()
		{
			$vendor = Vendor::Retrieve( $_SESSION[ 'admin' ]->vendor->id );
			$vendor->SetProperties( INPUT_POST );
			$vendor->id = $_SESSION[ 'admin' ]->vendor->id;
			$vendor->Save();
			$_SESSION[ 'admin' ]->vendor = $vendor;
		}
    }
