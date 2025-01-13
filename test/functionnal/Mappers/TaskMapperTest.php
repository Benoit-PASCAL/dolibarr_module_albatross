<?php

namespace test\functional;

// Prepare the environment
if (!defined('TEST_ENV_SETUP')) {
	require_once dirname(__DIR__) . '/_setup.php';
}

require_once MODULE_ROOT . '/inc/mappers/TaskDTOMapper.class.php';

use Albatross\TaskDTOMapper;
use Albatross\TaskDTO;

use Exception;
use PHPUnit\Framework\TestCase;
use Task;

class TaskMapperTest extends TestCase
{
	private $db;

	protected function setUp(): void
	{
		global $db;
		$this->db = $db;
	}

	public function testTaskDTOMapperConvertsToTaskDTO()
	{
		$task = new Task($this->db);
		$task->label = 'Test Task';
		$task->description = 'Test Task Description';

		$mapper = new TaskDTOMapper();
		$taskDTO = $mapper->toTaskDTO($task);

		$this->assertEquals('Test Task', $taskDTO->getTitle());
		$this->assertEquals('Test Task Description', $taskDTO->getDescription());
	}

	public function testTaskDTOMapperConvertsToTask()
	{
		$taskDTO = new TaskDTO();
		$taskDTO->setTitle('Test Task');
		$taskDTO->setDescription('Test Task Description');

		$mapper = new TaskDTOMapper();
		$task = $mapper->toTask($taskDTO);

		$this->assertEquals('Test Task', $task->label);
		$this->assertEquals('Test Task Description', $task->description);
	}

	public function testTaskDTOMapperHandlesEmptyTask()
	{
		$task = new Task($this->db);

		$mapper = new TaskDTOMapper();

		$this->expectException(Exception::class);
		$mapper->toTaskDTO($task);
	}
}
