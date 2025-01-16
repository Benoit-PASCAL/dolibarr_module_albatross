<?php

namespace test\functional;

// Prepare the environment
if (!defined('TEST_ENV_SETUP')) {
	require_once dirname(__DIR__) . '/_setup.php';
}

// Require tested class
require_once MODULE_ROOT . '/inc/mappers/BankDTOMapper.class.php';

use Albatross\BankDTOMapper;
use Albatross\BankDTO;

use Exception;
use PHPUnit\Framework\TestCase;
use Account;

class BankMapperTest extends TestCase
{
	private $db;

	protected function setUp(): void
	{
		global $db;
		$this->db = $db;
	}

	public function testBankDTOMapperConvertsToBankDTO()
	{
		$bank = new Account($this->db);
		$bank->label = 'Test Bank';

		$mapper = new BankDTOMapper();
		$bankDTO = $mapper->toBankDTO($bank);

		$this->assertEquals('Test Bank', $bankDTO->getName());
	}

	public function testBankDTOMapperConvertsToBank()
	{
		$bankDTO = new BankDTO();
		$bankDTO
			->setName('Test Bank');

		$mapper = new BankDTOMapper();
		$bank = $mapper->toBank($bankDTO);

		$this->assertEquals('Test Bank', $bank->label);
		$this->assertEquals('TEST_BANK', $bank->ref);
	}
}
