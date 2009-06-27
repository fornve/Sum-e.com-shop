<?php

/**
 * @package shop 
 * @subpackage framework
 */
class Controller
{
	function __construct()
	{
		Controller::Startup();
		Controller::VisitHandle();

		//$this->entity = new Entity;
		$this->smarty = new Smarty;
		$this->smarty->compile_dir = SMARTY_COMPILE_DIR;
		$this->smarty->template_dir = SMARTY_TEMPLATES_DIR;

		if( !file_exists( $this->smarty->compile_dir ) )
			mkdir( $this->smarty->compile_dir );
	}

	function dispatch( $default = 'Index' )
	{
		$uri = explode( '?', $_SERVER['REQUEST_URI'] );
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

		$this->controller = $input[ 1 ];
		$this->action = $input[ 2 ];
		$controller_name = "{$input[1]}Controller";

		if( class_exists( $controller_name ) )
		{
			$controller = new $controller_name;

			$method = $input[ 2 ];
			
			if( strlen( $method ) == 0 )
				$method = 'Index';

			if( method_exists( get_class( $controller ), $method ) ) // check if property exists
			{
				$controller->$method( $input[ 3 ], $input[ 4 ] );
			}
			else
			{
				$this->NotFound();
			}
		}
		else
		{
			$this->NotFound();
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

		$this->assign( 'vat_multiply', ( 1 + Config::GetVat() ) );

		$content = $this->smarty->fetch( $dir . $template );

		if( !filter_input( INPUT_GET, 'ajax' ) )
		{
			$this->assign( 'content', $content );

			$this->PreDecorate();
			$content = $this->smarty->fetch( $dir .'decoration.tpl' );
			$this->PostDecorate();
		}

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
		$template = 'gray';
		//$template = 'default';
		define( 'TEMPLATE_NAME', $template );
		define( 'SMARTY_TEMPLATES_DIR', PROJECT_PATH ."/templates/{$template}/" );

		Config::DefineAll();

		// clean basket if empty
		if( count( $_SESSION[ 'basket' ]->items ) < 1 )
			unset( $_SESSION[ 'basket' ] );
	}

    function PreDecorate()
    {
		$generated = floor ( 10000 * ( microtime( true ) - TIMER ) ) / 10000;
		$this->smarty->assign( 'generated', $generated );
		$this->smarty->assign( 'entity_query', $_SESSION[ 'entity_query' ] );
		unset( $_SESSION[ 'entity_query' ] );

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
}
  
