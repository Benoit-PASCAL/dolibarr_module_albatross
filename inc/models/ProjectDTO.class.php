<?php

namespace Albatross;

class ProjectDTO
{
    private string $label;

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label): ProjectDTO
    {
        $this->label = $label;
        return $this;
    }
}
