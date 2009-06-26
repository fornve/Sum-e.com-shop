<?php

    class MiscAdminController extends AdminController
    {
        public $breadcrumbs = array( array( 'link' => '/Admin/', 'name' => 'Other Admin' ) );
		
        function FlushCache()
        {
 			Log::Add( "CACHE_FLUSH", 'CACHE', $_SESSION[ 'admin' ]->id );
           	$_SESSION[ 'user_notification' ][] = array( 'type' => 'notice', 'text' => 'Cache flushed!' );
            Config::FlushCache();
            self::Redirect( $_SERVER[ "HTTP_REFERER" ] );
        }

		function UserAgents()
		{
			$this->breadcrumbs[] = array( 'link' => "/MiscAdmin/UserAgents/", 'name' => "User Agents" );
			$this->assign( 'user_agents', User_Agent::GetAll() );
			$this->assign( 'breadcrumbs', $this->breadcrumbs );
			echo $this->Decorate( 'admin/misc/user_agent.tpl' );
		}

		function EditUserAgent( $id )
		{
			$this->breadcrumbs[] = array( 'link' => "/MiscAdmin/UserAgents/", 'name' => "User Agents" );
			$this->breadcrumbs[] = array( 'link' => null, 'name' => "User Agent Edit" );
			$user_agent = User_Agent::Retrieve( $id );

			if( !$user_agent )
				self::Redirect( '/Error/NotFound' );

			if( $_SERVER['REQUEST_METHOD'] == 'POST' )
			{
				$input = Common::Inputs( array( 'agent', 'new_agent', 'type', 'new_type', 'os', 'new_os' ), INPUT_POST );

				if( $input->new_agent )
					$user_agent->agent = $input->new_agent;
				else
					$user_agent->agent = $input->agent;

				if( $input->new_type )
					$user_agent->type = $input->new_type;
				else
					$user_agent->type = $input->type;

				if( $input->new_os )
					$user_agent->os = $input->new_os;
				else
					$user_agent->os = $input->os;
					
				$user_agent->Save();
				$_SESSION[ 'user_notification' ][] = array( 'type' => 'notice', 'text' => "User agent updated." );

			}

			$this->assign( 'user_agent', $user_agent );
			$this->assign( 'oss', $user_agent->TypeCollection( 'os' ) );
			$this->assign( 'agents', $user_agent->TypeCollection( 'agent' ) );
			$this->assign( 'agent_types', $user_agent->TypeCollection( 'type' ) );
			$this->assign( 'breadcrumbs', $this->breadcrumbs );
			echo $this->Decorate( 'admin/misc/user_agent_edit.tpl' );
		}
    }
