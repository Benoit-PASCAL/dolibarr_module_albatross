<?php

namespace Albatross\tools;

require_once __DIR__ . '/LogManager.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions.lib.php';

class DoliLogManager implements LogManager
{

	public static function log(string $message): void
	{
		dol_syslog($message, LOG_NOTICE);
	}

	public static function error(string $message): void
	{
		dol_syslog($message, LOG_ERR);
	}

	public static function warning(string $message): void
	{
		dol_syslog($message, LOG_WARNING);
	}

	public static function info(string $message): void
	{
		dol_syslog($message, LOG_INFO);
	}

	public static function debug(string $message): void
	{
		dol_syslog($message, LOG_DEBUG);
	}

	public static function critical(string $message): void
	{
		dol_syslog($message, LOG_ALERT);
	}
}
