<?php

namespace fuel;

class views {

	private static $initiated = false;

	public static function init()
	{
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	public static function init_hooks()
	{
		self::$initiated = true;

	}
}
