<?php

namespace Albatross;

class ProjectDTO
{
	/**
	 * @var string $label
	 */
	private $label;

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
