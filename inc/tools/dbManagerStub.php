<?php

namespace Albatross\Tools;

//require_once dirname(__DIR__).'/models/index.php';

use Albatross\BankDTO;
use Albatross\InvoiceDTO;
use Albatross\OrderDTO;
use Albatross\ProjectDTO;
use Albatross\QuotationDTO;
use Albatross\ServiceDTO;
use Albatross\TaskDTO;
use Albatross\ThirdpartyDTO;
use Albatross\TicketDTO;
use Albatross\UserDTO;
use Albatross\UserGroupDTO;
use Albatross\ProductDTO;
use Albatross\EntityDTO;
use Albatross\Tools\intDBManager;

require_once __DIR__ . '/intDBManager.php';

class dbManagerStub implements intDBManager
{
	/**
	 * @param \Albatross\UserDTO $userDTO
	 */
	public function createUser($userDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\UserGroupDTO $userGroupDTO
	 */
	public function createUserGroup($userGroupDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createCustomer($thirdpartyDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createSupplier($thirdpartyDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\ProductDTO $productDTO
	 */
	public function createProduct($productDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\ServiceDTO $serviceDTO
	 */
	public function createService($serviceDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\OrderDTO $orderDTO
	 */
	public function createOrder($orderDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\InvoiceDTO $invoice
	 */
	public function createInvoice($invoice): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\TicketDTO $ticketDTO
	 */
	public function createTicket($ticketDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\EntityDTO $entityDTO
	 */
	public function createEntity($entityDTO): int
	{
		return 1;
	}

	/**
	 * @param \Albatross\ProjectDTO $projectDTO
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
	 * @param \Albatross\TaskDTO $taskDTO
	 */
	public function createTask($taskDTO): int
	{
		return 1;
	}

	/**
	 * @param $bankDTO
	 * @return int
	 */
	public function createBank($bankDTO): int
	{
		return 1;
	}

	public function __construct($log = null)
	{
	}

	/**
	 * @param QuotationDTO $quotationDTO
	 */
	public function createQuotation($quotationDTO): int
	{
		return 1;
	}

	/**
	 * @param QuotationDTO $quotationDTO
	 */
	public function createSupplierQuotation($quotationDTO): int
	{
		return 1;
	}

	/**
	 * @param OrderDTO $orderDTO
	 */
	public function createSupplierOrder($orderDTO): int
	{
		return 1;
	}

	/**
	 * @param InvoiceDTO $invoiceDTO
	 * @throws Exception
	 */
	public function createSupplierInvoice($invoice): int
	{
		return 1;
	}
}
