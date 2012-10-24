<?php
/*
 * This class allow to get variables from Config class in non static way
 */
class ConfigWrapper
{
	public function get( $variable )
	{
		return Config::get( $variable );
	}
}
