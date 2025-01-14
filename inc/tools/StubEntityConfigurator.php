<?php

namespace Albatross\models\Tools;

require_once __DIR__ . '/EntityConfiguratorInterface.php';

class StubEntityConfigurator implements EntityConfiguratorInterface
{
	/**
	 * @param int $entityId
	 * @param mixed[] $params
	 */
	public function setupEntity($entityId = 0, $params = []): bool
	{
		return 1;
	}

	/**
	 * @param mixed[] $neededModules
	 */
	public function initModules($neededModules): int
	{
		return 1;
	}

	public function setSecurity(): void
	{
		// TODO: Implement setSecurity() method.
	}
}
