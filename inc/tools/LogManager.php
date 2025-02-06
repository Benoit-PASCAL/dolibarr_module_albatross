<?php

namespace Albatross\tools;

interface LogManager
{
	public static function log(string $message): void;
	public static function error(string $message): void;
	public static function warning(string $message): void;
	public static function info(string $message): void;
	public static function debug(string $message): void;
	public static function critical(string $message): void;
}
