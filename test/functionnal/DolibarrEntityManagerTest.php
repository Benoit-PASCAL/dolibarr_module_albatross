<?php

// Prepare the environment
if (!defined('TEST_ENV_SETUP')) {
	require_once dirname(__FILE__) . '/_setup.php';
}

require_once ROOT . '/custom/albatross/inc/models/index.php';

// Require tested class
require_once MODULE_ROOT . '/inc/tools/doliDBManager.php';

require_once MODULE_ROOT . '/test/tools/RandomFactory.php';


use Albatross\Tools\DoliDBManager;
use PHPUnit\Framework\TestCase;

class DolibarrEntityManagerTest extends TestCase
{
	private $entityManager;

	protected function setUp(): void
	{
		$this->entityManager = new DoliDBManager();
		$this->entityManager->removeFixtures();
	}

	public function testCreateUser()
	{
		// Prepare needed group
		$groupDTO = RandomFactory::getRandomUserGroup();
		$groupID = $this->entityManager->createUserGroup($groupDTO);
		$groupDTO->setId($groupID);

		// Test
		$userDTO = RandomFactory::getRandomUser();
		$userDTO->addGroup($groupDTO);
		$userID = $this->entityManager->createUser($userDTO);

		$this->assertGreaterThan(0, $userID);
	}

	public function testCreateUserGroup()
	{
		$userGroupDTO = RandomFactory::getRandomUserGroup();
		$groupID = $this->entityManager->createUserGroup($userGroupDTO);

		$this->assertGreaterThan(0, $groupID);
	}

	public function testCreateCustomer()
	{
		$customerDTO = RandomFactory::getRandomCustomer();
		$customerID = $this->entityManager->createCustomer($customerDTO);
		$this->assertGreaterThan(0, $customerID);
	}

	public function testCreateSupplier()
	{
		$supplierDTO = RandomFactory::getRandomSupplier();
		$supplierID = $this->entityManager->createSupplier($supplierDTO);
		$this->assertGreaterThan(0, $supplierID);
	}

	public function testCreateProduct()
	{
		$productDTO = RandomFactory::getRandomProduct();
		$productID = $this->entityManager->createProduct($productDTO);
		$this->assertGreaterThan(0, $productID);
	}

	public function testCreateService()
	{
		$serviceDTO = RandomFactory::getRandomService();
		$serviceID = $this->entityManager->createService($serviceDTO);
		$this->assertGreaterThan(0, $serviceID);
	}

	public function testCreateOrder()
	{
		// Prepare needed entities
		$customerDTO = RandomFactory::getRandomCustomer();
		$supplierDTO = RandomFactory::getRandomSupplier();
		$productDTO = RandomFactory::getRandomProduct();

		$customerID = $this->entityManager->createCustomer($customerDTO);
		$supplierID = $this->entityManager->createSupplier($supplierDTO);
		$productID = $this->entityManager->createProduct($productDTO);

		// Test
		$orderDTO = RandomFactory::getRandomOrder();
		$orderDTO
			->setCustomerId($customerID)
			->setSupplierId($supplierID);

		$orderID = $this->entityManager->createOrder($orderDTO);
		$this->assertGreaterThan(0, $orderID);
	}

	public function testCreateSupplierOrder()
	{
		$productDTO_1 = RandomFactory::getRandomProduct();
		$productID_1 = $this->entityManager->createProduct($productDTO_1);
		$productDTO_2 = RandomFactory::getRandomProduct();
		$productID_2 = $this->entityManager->createProduct($productDTO_2);

		$supplierDTO = RandomFactory::getRandomSupplier();
		$customerDTO = RandomFactory::getRandomCustomer();

		$supplierID = $this->entityManager->createSupplier($supplierDTO);
		$customerID = $this->entityManager->createCustomer($customerDTO);

		$orderDTO = RandomFactory::getSupplierOrder();
		$orderDTO
			->setSupplierId($supplierID)
			->setCustomerId($customerID);

		$orderDTO
			->getOrderLines()[0]
			->setProductId($productID_1);
		$orderDTO
			->getOrderLines()[1]
			->setProductId($productID_2);

		$orderID = $this->entityManager->createSupplierOrder($orderDTO);
		$this->assertGreaterThan(0, $orderID);
	}

	public function testCreateInvoice()
	{
		$productDTO_1 = RandomFactory::getRandomProduct();
		$productID_1 = $this->entityManager->createProduct($productDTO_1);
		$productDTO_2 = RandomFactory::getRandomProduct();
		$productID_2 = $this->entityManager->createProduct($productDTO_2);

		$supplierDTO = RandomFactory::getRandomSupplier();
		$customerDTO = RandomFactory::getRandomCustomer();
		$supplierID = $this->entityManager->createSupplier($supplierDTO);
		$customerID = $this->entityManager->createCustomer($customerDTO);

		$invoiceDTO = RandomFactory::getRandomInvoice();
		$invoiceDTO
			->setSupplierId($supplierID)
			->setCustomerId($customerID);

		$invoiceDTO
			->getInvoiceLines()[0]
			->setProductId($productID_1);
		$invoiceDTO
			->getInvoiceLines()[1]
			->setProductId($productID_2);

		$invoiceID = $this->entityManager->createInvoice($invoiceDTO);
		$this->assertGreaterThan(0, $invoiceID);
	}


	public function testCreateSupplierInvoice()
	{
		$productDTO_1 = RandomFactory::getRandomProduct();
		$productID_1 = $this->entityManager->createProduct($productDTO_1);
		$productDTO_2 = RandomFactory::getRandomProduct();
		$productID_2 = $this->entityManager->createProduct($productDTO_2);

		$supplierDTO = RandomFactory::getRandomSupplier();
		$customerDTO = RandomFactory::getRandomCustomer();

		$supplierID = $this->entityManager->createSupplier($supplierDTO);
		$customerID = $this->entityManager->createCustomer($customerDTO);

		$invoiceDTO = RandomFactory::getSupplierInvoice();
		$invoiceDTO
			->setSupplierId($supplierID)
			->setCustomerId($customerID);

		$invoiceDTO
			->getInvoiceLines()[0]
			->setProductId($productID_1);
		$invoiceDTO
			->getInvoiceLines()[1]
			->setProductId($productID_2);

		$invoiceID = $this->entityManager->createSupplierInvoice($invoiceDTO);
		$this->assertGreaterThan(0, $invoiceID);
	}

	public function testCreateQuotation()
	{
		// Prepare needed entities
		$customerDTO = RandomFactory::getRandomCustomer();
		$supplierDTO = RandomFactory::getRandomSupplier();
		$productDTO = RandomFactory::getRandomProduct();

		$customerID = $this->entityManager->createCustomer($customerDTO);
		$supplierID = $this->entityManager->createSupplier($supplierDTO);
		$productID = $this->entityManager->createProduct($productDTO);

		// Test
		$quotationDTO = RandomFactory::getRandomQuotation();
		$quotationDTO
			->setCustomerId($customerID)
			->setSupplierId($supplierID);

		$orderID = $this->entityManager->createQuotation($quotationDTO);
		$this->assertGreaterThan(0, $orderID);
	}

	public function testCreateSupplierQuotation()
	{
		$productDTO_1 = RandomFactory::getRandomProduct();
		$productID_1 = $this->entityManager->createProduct($productDTO_1);
		$productDTO_2 = RandomFactory::getRandomProduct();
		$productID_2 = $this->entityManager->createProduct($productDTO_2);

		$supplierDTO = RandomFactory::getRandomSupplier();
		$customerDTO = RandomFactory::getRandomCustomer();

		$supplierID = $this->entityManager->createSupplier($supplierDTO);
		$customerID = $this->entityManager->createCustomer($customerDTO);

		$quotationDTO = RandomFactory::getSupplierQuotation();
		$quotationDTO
			->setSupplierId($supplierID)
			->setCustomerId($customerID);

		$quotationDTO
			->getQuotationLines()[0]
			->setProductId($productID_1);
		$quotationDTO
			->getQuotationLines()[1]
			->setProductId($productID_2);

		$quotationDTO = $this->entityManager->createSupplierQuotation($quotationDTO);
		$this->assertGreaterThan(0, $quotationDTO);
	}

	public function testCreateProject()
	{
		$projectDTO = RandomFactory::getRandomProject();
		$projectID = $this->entityManager->createProject($projectDTO);

		$this->assertGreaterThan(0, $projectID);
	}

	public function testCreateTask()
	{
		$projectDTO = RandomFactory::getRandomProject();
		$projectID = $this->entityManager->createProject($projectDTO);

		$taskDTO = RandomFactory::getRandomTask();
		$taskDTO->setProjectId($projectID);

		$taskID = $this->entityManager->createTask($taskDTO);

		$this->assertGreaterThan(0, $taskID);
	}

	public function testCreateTicket()
	{
		$ticketDTO = RandomFactory::getRandomTicket();
		$ticketID = $this->entityManager->createTicket($ticketDTO);
		$this->assertGreaterThan(0, $ticketID);
	}


	public function testCreateBank()
	{
		// Prepare needed group
		$bankDTO = RandomFactory::getBank();
		$bankID = $this->entityManager->createBank($bankDTO);

		$this->assertGreaterThan(0, $bankID);
	}

	public function testCreateEntity()
	{
		$entityDTO = RandomFactory::getRandomEntity();
		$entityID = $this->entityManager->createEntity($entityDTO);
		$this->assertGreaterThan(0, $entityID);
	}


}
