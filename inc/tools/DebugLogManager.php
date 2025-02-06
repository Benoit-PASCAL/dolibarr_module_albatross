<?php

namespace Albatross\tools;

require_once __DIR__ . '/LogManager.php';

class DebugLogManager implements LogManager
{
	public function __construct()
	{
		echo '<pre>';
	}

	public function __destruct()
	{
		echo '</pre>';
	}

	public static function log(string $message): void
	{
		echo $message . '<br>';;
	}

	public static function error(string $message): void
	{
		echo $message . '<br>';;
	}

	public static function warning(string $message): void
	{
		echo $message . '<br>';;
	}

	public static function info(string $message): void
	{
		echo $message . '<br>';;
	}

	public static function debug(string $message): void
	{
		echo $message . '<br>';;
	}

	public static function critical(string $message): void
	{
		echo $message . '<br>';;
	}
}



