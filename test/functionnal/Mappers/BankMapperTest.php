<?php

namespace test\functional;


// Require tested class
require_once MODULE_ROOT . '/inc/mappers/BankDTOMapper.class.php';


use Albatross\BankDTO;
use Albatross\BankDTOMapper;
use DateTime;
use Facture;
use PHPUnit\Framework\TestCase;

class BankMapperTest extends TestCase
{
	public function testBankDTOMapperConvertsToBankDTO()
	{
		global $db;
		$bank = new \Account($db);

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
