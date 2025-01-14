<?php

namespace Albatross\models\Tools;

//require_once dirname(__DIR__).'/models/index.php';

use Albatross\models\InvoiceDTO;
use Albatross\models\OrderDTO;
use Albatross\models\ProjectDTO;
use Albatross\models\ServiceDTO;
use Albatross\models\TaskDTO;
use Albatross\models\ThirdpartyDTO;
use Albatross\models\TicketDTO;
use Albatross\models\UserDTO;
use Albatross\models\UserGroupDTO;
use Albatross\models\ProductDTO;
use Albatross\models\EntityDTO;
use Albatross\models\Tools\intDBManager;

require_once __DIR__ . '/intDBManager.php';

class dbManagerStub implements intDBManager
{
	/**
	 * @param \Albatross\models\UserDTO $userDTO
	 */
	public function createUser($userDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\UserGroupDTO $userGroupDTO
	 */
	public function createUserGroup($userGroupDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createCustomer($thirdpartyDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createSupplier($thirdpartyDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\ProductDTO $productDTO
	 */
	public function createProduct($productDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\ServiceDTO $serviceDTO
	 */
	public function createService($serviceDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\OrderDTO $orderDTO
	 */
	public function createOrder($orderDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\InvoiceDTO $invoice
	 */
	public function createInvoice($invoice): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\TicketDTO $ticketDTO
	 */
	public function createTicket($ticketDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\EntityDTO $entityDTO
	 */
	public function createEntity($entityDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\ProjectDTO $projectDTO
	 */
	public function createProject($projectDTO): int
	{
		return 1;
	}

	/**
	 * @param int $entityId
	 * @param mixed[] $params
	 */
	public function setupEntity($entityId = 0, $params = []): bool
	{
		return true;
	}

	public function removeFixtures(): bool
	{
		return 1;
	}

	/**
	 * @param \Albatross\models\TaskDTO $taskDTO
	 */
	public function createTask($taskDTO): int
	{
		return 1;
	}
}
