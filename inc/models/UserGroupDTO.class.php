<?php

namespace Albatross;

class UserGroupDTO
{
    /**
     * @var ?int id
     */
    private $id;

    /**
     * @var string label
     */
    private $label;

    /**
     * @var int[] $entities
     */
    private array $entities;

    public function __construct()
    {
        $this->entities = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): UserGroupDTO
    {
        $this->id = $id;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label): UserGroupDTO
    {
        $this->label = $label;
        return $this;
    }

    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param mixed[] $entities
     */
    public function addEntities($entities): UserGroupDTO

    {
        $this->entities = array_unique(array_merge($this->entities, $entities));
        return $this;
    }
}
