<?php

namespace Albatross;

class TaskDTO
{
	/** @var string $title */
	private $title;

	/** @var string $description */
	private $description;

	/** @var int $projectID */
	private $projectID;

	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title): TaskDTO
	{
		$this->title = $title;
		return $this;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description): TaskDTO
	{
		$this->description = $description;
		return $this;
	}

	public function getProjectID(): int
	{
		return $this->projectID ?? 0;
	}

	/**
	 * @param int $projectID
	 */
	public function setProjectID($projectID): TaskDTO
	{
		$this->projectID = $projectID;
		return $this;
	}
}
