<?php

namespace Albatross\Tools;

interface EntityConfiguratorInterface
{
    /**
     * @param int $entityId
     * @param mixed[] $params
     */
    public function setupEntity($entityId = 0, $params = []): bool;

    /**
     * @param mixed[] $neededModules
     */
    public function initModules($neededModules): int;

    public function setSecurity(): void;
}
