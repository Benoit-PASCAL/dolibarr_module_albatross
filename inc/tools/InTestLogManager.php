<?php

namespace Albatross\tools;

require_once __DIR__ . '/LogManager.php';

class InTestLogManager implements LogManager
{
	public static function log(string $message): void
	{
		echo $message . PHP_EOL;
	}

	public static function error(string $message): void
	{
		echo $message . PHP_EOL;
	}

	public static function warning(string $message): void
	{
		echo $message . PHP_EOL;
	}

	public static function info(string $message): void
	{
		echo $message . PHP_EOL;
	}

	public static function debug(string $message): void
	{
		echo $message . PHP_EOL;
	}

	public static function critical(string $message): void
	{
		echo $message . PHP_EOL;
	}
}



