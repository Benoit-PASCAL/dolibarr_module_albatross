<?php

namespace Albatross\Tools;

require_once dirname(__DIR__) . '/models/index.php';
//require_once dirname(__DIR__) . '/mappers/index.php';

use Albatross\BankDTO;
use Albatross\InvoiceDTO;
use Albatross\OrderDTO;
use Albatross\ProductDTO;
use Albatross\ProjectDTO;
use Albatross\ServiceDTO;
use Albatross\ThirdpartyDTO;
use Albatross\TicketDTO;
use Albatross\UserDTO;
use Albatross\EntityDTO;
use Albatross\UserGroupDTO;
use Albatross\TaskDTO;

interface intDBManager
{
	/**
	 * @param \Albatross\UserDTO $userDTO
	 */
	public function createUser($userDTO): int;

	/**
	 * @param \Albatross\UserGroupDTO $userGroupDTO
	 */
	public function createUserGroup($userGroupDTO): int;

	/**
	 * @param \Albatross\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createCustomer($thirdpartyDTO): int;

	/**
	 * @param \Albatross\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createSupplier($thirdpartyDTO): int;

	/**
	 * @param \Albatross\ProductDTO $productDTO
	 */
	public function createProduct($productDTO): int;

	/**
	 * @param \Albatross\ServiceDTO $serviceDTO
	 */
	public function createService($serviceDTO): int;

	/**
	 * @param \Albatross\OrderDTO $orderDTO
	 */
	public function createOrder($orderDTO): int;

	/**
	 * @param \Albatross\InvoiceDTO $invoice
	 */
	public function createInvoice($invoice): int;

	/**
	 * @param \Albatross\TicketDTO $ticketDTO
	 */
	public function createTicket($ticketDTO): int;

	/**
	 * @param \Albatross\ProjectDTO $projectDTO
	 */
	public function createProject($projectDTO): int;

	/**
	 * @param \Albatross\EntityDTO $entityDTO
	 */
	public function createEntity($entityDTO): int;

	/**
	 * @param int $entityId
	 * @param mixed[] $params
	 */
	public function setupEntity($entityId = 0, $params = []): bool;

	/**
	 * @param \Albatross\TaskDTO $taskDTO
	 */
	public function createTask($taskDTO): int;

	/**
	 * @param BankDTO $bankDTO
	 * @return int
	 */
	public function createBank($bankDTO): int;

	public function removeFixtures(): bool;
}
