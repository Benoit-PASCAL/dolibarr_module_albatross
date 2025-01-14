<?php

namespace Albatross\models\Tools;

require_once dirname(__DIR__) . '/models/index.php';
//require_once dirname(__DIR__) . '/mappers/index.php';

use Albatross\models\InvoiceDTO;
use Albatross\models\OrderDTO;
use Albatross\models\ProductDTO;
use Albatross\models\ProjectDTO;
use Albatross\models\ServiceDTO;
use Albatross\models\ThirdpartyDTO;
use Albatross\models\TicketDTO;
use Albatross\models\UserDTO;
use Albatross\models\EntityDTO;
use Albatross\models\UserGroupDTO;
use Albatross\models\TaskDTO;

interface intDBManager
{
	/**
	 * @param \Albatross\models\UserDTO $userDTO
	 */
	public function createUser($userDTO): int;

	/**
	 * @param \Albatross\models\UserGroupDTO $userGroupDTO
	 */
	public function createUserGroup($userGroupDTO): int;

	/**
	 * @param \Albatross\models\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createCustomer($thirdpartyDTO): int;

	/**
	 * @param \Albatross\models\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createSupplier($thirdpartyDTO): int;

	/**
	 * @param \Albatross\models\ProductDTO $productDTO
	 */
	public function createProduct($productDTO): int;

	/**
	 * @param \Albatross\models\ServiceDTO $serviceDTO
	 */
	public function createService($serviceDTO): int;

	/**
	 * @param \Albatross\models\OrderDTO $orderDTO
	 */
	public function createOrder($orderDTO): int;

	/**
	 * @param \Albatross\models\InvoiceDTO $invoice
	 */
	public function createInvoice($invoice): int;

	/**
	 * @param \Albatross\models\TicketDTO $ticketDTO
	 */
	public function createTicket($ticketDTO): int;

	/**
	 * @param \Albatross\models\ProjectDTO $projectDTO
	 */
	public function createProject($projectDTO): int;

	/**
	 * @param \Albatross\models\EntityDTO $entityDTO
	 */
	public function createEntity($entityDTO): int;

	/**
	 * @param int $entityId
	 * @param mixed[] $params
	 */
	public function setupEntity($entityId = 0, $params = []): bool;

	/**
	 * @param \Albatross\models\TaskDTO $taskDTO
	 */
	public function createTask($taskDTO): int;

	public function removeFixtures(): bool;
}
