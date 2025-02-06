<?php

namespace Albatross\tools;

require_once __DIR__ . '/LogManager.php';

class DebugLogManager implements LogManager
{
	public static function log(string $message): void
	{
		echo '<script>console.log("' . $message . '")</script>';
	}

	public static function error(string $message): void
	{
		echo '<script>console.error("' . $message . '")</script>';
	}

	public static function warning(string $message): void
	{
		echo '<script>console.warn("' . $message . '")</script>';
	}

	public static function info(string $message): void
	{
		echo '<script>console.info("' . $message . '")</script>';
	}

	public static function debug(string $message): void
	{
		echo '<script>console.debug("' . $message . '")</script>';
	}

	public static function critical(string $message): void
	{
		echo '<script>console.error("' . $message . '")</script>';
	}
}



