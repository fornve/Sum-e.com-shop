<?php

/**
 * @package shop 
 * @subpackage framework
 */
class Controller
{
	public $decoration = 'decoration.tpl'; // decoration file
	public $uri = null;
	public $controller = 'IndexController';
	public $action = 'Index';
	public $params = null;
	function __construct()
	{
		try
		{
			Controller::VisitHandle();
		}
		catch( EntityException $e )
		{
			die( "Site is down: ". $e->getMessage() );
		}

		//$this->entity = new Entity;
		$this->smarty = new Smarty;
		$this->smarty->compile_dir = SMARTY_COMPILE_DIR;
		$this->smarty->template_dir = SMARTY_TEMPLATES_DIR;
		
		if( !file_exists( $this->smarty->compile_dir ) )
		{
			mkdir( $this->smarty->compile_dir );
		}
	}

	function Dispatch( $default = 'Index', $second_chance = false, $uri = null )
	{
		if( !$second_chance )
			$uri = $_SERVER['REQUEST_URI'];

		$this->uri = $uri;

		$uri = explode( '?', $uri );
		$input = explode( '/', $uri[ 0 ] );

		// rewrite rule for numeric ads
		if( is_numeric( $input[ 1 ] ) )
		{
			$input[ 3 ] = $input[ 1 ];
			$input[ 1 ] = 'Todo';
			$input[ 2 ] = 'View';
		}
		elseif( $input[ 1 ] == 'robots.txt' )
		{
			$input[ 1 ] = 'Page';
			$input[ 2 ] = 'robots';
		}

		if( strlen( $input[ 1 ] ) < 1 ) // default Controller
			$input[ 1 ] = 'Index';

		if( strlen( $input[ 2 ] ) < 1 ) // default function
			$input[ 2 ] = 'Index';

		$method = $input[ 2 ];
		$controller_name = "{$input[1]}Controller";

		if( class_exists( $controller_name ) )
		{
			$controller = new $controller_name;
			
			if( strlen( $method ) == 0 )
				$method = 'Index';

			if( method_exists( get_class( $controller ), $method ) ) // check if property exists
			{
				try
				{
					$controller->$method( $input[ 3 ], $input[ 4 ] );
				}
				catch( EntityException $e )
				{
					$this->error = $e;
					$this->CatchableError();
				}
				catch( Exception $e )
				{
					$this->error = $e;
					$this->CatchableError();
				}

				
				exit;
			}
		}
		elseif( !$second_chance )
		{
			$uri = Url::Decode( $_SERVER['REQUEST_URI'] );

			if( $uri )
			{
				$this->Dispatch( 'Index', true, $uri );
				exit;
			}
		}
		
		$this->NotFound();
	}

	function CatchableError()
	{
		Filelog::Write( "[Catchable error]: ". $this->error->getMessage() ."\n\n" );

		$this->assign( 'breadcrumbs', array( array( 'name' => 'Error' ) ) );
		$this->assign( 'error', $this->error->getMessage() );
		$this->assign( 'e', $this->error );
		echo $this->Decorate( "catchable-error.tpl" );
		exit;
	}

	function NotFound()
	{
		header( "HTTP/1.0 404 Not Found" );

		$this->assign( 'breadcrumbs', array( array( 'name' => 'Not found' ) ) );

		if( PRODUCTION )
		{
			echo $this->Decorate( "404.tpl" );
		}
		else
		{
			echo $this->Decorate( "404-development.tpl" );
		}
	}
	function assign( $variable, $value )
	{
		$this->smarty->assign( $variable, $value );
	}

	function fetch( $template, $dir = null )
	{
		if( !$dir ) $dir = SMARTY_TEMPLATES_DIR;
		$output = $this->smarty->fetch( $dir . $template );
		return $output;
	}

	function decorate( $template, $dir = null )
	{
		if( !$dir ) $dir = SMARTY_TEMPLATES_DIR;

		$this->assign( 'vat_multiply', ( 1 + Site_Config::GetVat() ) );

		if( file_exists( $dir . $template ) )
		{
			$content = $this->smarty->fetch( $dir . $template );
		}
		else
		{
			$content = $this->smarty->fetch( SMARTY_DEFAULT_TEMPLATES_DIR . $template );
		}

		if( !filter_input( INPUT_GET, 'ajax' ) )
		{
			$this->assign( 'content', $content );

			$this->PreDecorate();
			if( !PRODUCTION )
			{
				$this->smarty->assign( 'memory_peak', round( memory_get_peak_usage() / 1024, 2 ) );
			}
			$content = $this->smarty->fetch( $dir . $this->decoration );
			$this->PostDecorate();
		}

		$this->PageCacheSet( $content );

		return $content;
	}

	static function GetInput( $input_name, $input_type = INPUT_GET )
	{
		$input = Controller::Inputs( array( $input_name ), $input_type );
		if( $input->$input_name )
		{
			return $input->$input_name;
		}
	}

	static function Startup()
	{
		//$template = 'gray';
		//$template = 'default';
		//define( 'TEMPLATE_NAME', $template );
		//define( 'SMARTY_TEMPLATES_DIR', PROJECT_PATH ."/templates/{$template}/" );

		Site_Config::DefineAll();

		// clean basket if empty
		if( isset( $_SESSION[ 'basket' ]->items ) && count( $_SESSION[ 'basket' ]->items ) < 1 )
			unset( $_SESSION[ 'basket' ] );
	}

	function PreDecorate()
	{
		$generated = floor ( 10000 * ( microtime( true ) - TIMER ) ) / 10000;
		$this->smarty->assign( 'generated', $generated );
		$this->smarty->assign( 'entity_query', $_SESSION[ 'entity_query' ] );
		$this->smarty->Assign( 'cache_query', $_SESSION[ 'cache_query' ] );
		unset( $_SESSION[ 'entity_query' ] );
		unset( $_SESSION[ 'cache_query' ] );

		$categories = Category::LevelCollection( 0, false, $this->entity );
		$this->assign( 'categories', $categories );
	}

	function PostDecorate()
	{
		unset( $_SESSION[ 'user_notification' ] );
	}

	static function Redirect( $url )
	{
		header( "Location: $url" );
		exit;
	}

	static function RedirectReferer()
	{
		self::Redirect( $_SERVER[ 'HTTP_REFERER' ] );
	}

	static function VisitHandle()
	{
		if( !$_SESSION['visitor'] )
		{
			$user_agent = User_Agent::GetByName( $_SERVER[ 'HTTP_USER_AGENT' ] );
			if( !$user_agent )
			{
				$user_agent = new User_Agent();
				$user_agent->name = $_SERVER[ 'HTTP_USER_AGENT' ];

			}
			$user_agent->count++;
			$user_agent->Save();
			$_SESSION['visitor'] = true; // to be finished with something more sensible
		}
	}

	private function PageCacheGet( $controller, $method, $uri )
	{
		if( Site_Config::PageCache() && Page_Cache_Config::Get( $controller, $method ) )
		{
			return PageCache::Get( $uri );
		}
	}

	private function PageCacheSet( $content )
	{
		if( Site_Config::PageCache() && $expires = Page_Cache_Config::Get( $this->controller, $this->method ) )
		{
			return PageCache::Set( $this->uri, $content, $expires );
		}
	}

	static function UserError( $text )
	{
		$_SESSION[ 'user_notification' ][] = array( 'type' => 'error', 'text' => $text );
	}

	static function UserNotice( $text )
	{
		$_SESSION[ 'user_notification' ][] = array( 'type' => 'notice', 'text' => $text );
	}

}
  
