<?php

namespace Albatross;

include_once dirname(__DIR__) . '/models/TaskDTO.class.php';
require_once dirname(__DIR__, 4) . '/projet/class/task.class.php';

use InvalidArgumentException;
use Task;

class TaskDTOMapper
{
	/**
	 * @param \Albatross\models\TaskDTO $taskDTO
	 */
	public function toTask($taskDTO): Task
	{
		global $db;

		$task = new Task($db);

		$task->label = $taskDTO->getTitle();
		$task->description = $taskDTO->getDescription();
		$task->fk_project = $taskDTO->getProjectID();

		return $task;
	}

	/**
	 * @param \Task $task
	 */
	public function toTaskDTO($task): TaskDTO
	{
		$requiredFields = ['label'];
		foreach ($requiredFields as $field) {
			if (empty($task->$field)) {
				throw new InvalidArgumentException('Task ' . $field . ' is required');
			}
		}

		$taskDTO = new TaskDTO();
		$taskDTO
			->setTitle($task->label)
			->setDescription($task->description ?? '')
			->setProjectID($task->fk_project ?? 0);

		return $taskDTO;
	}
}
