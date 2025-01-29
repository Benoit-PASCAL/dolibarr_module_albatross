<?php

namespace test\functional;

require_once MODULE_ROOT . '/inc/mappers/QuotationDTOMapper.class.php';

use Albatross\QuotationDTO;
use Albatross\QuotationDTOMapper;
use Albatross\QuotationLineDTO;
use DateTime;
use Propal;
use PHPUnit\Framework\TestCase;

class QuotationMapperTest extends TestCase
{
	public function testQuotationDTOMapperConvertsToQuotationDTO()
	{
		global $db;
		$quotation = new Propal($db);
		$quotation->lines = [
			(object) ['qty' => 10, 'product' => (object) ['id' => 1]],
			(object) ['qty' => 5, 'product' => (object) ['id' => 2]]
		];
		$quotation->ref_customer = 100;
		$quotation->socid = 200;
		$quotation->date = time();

		$mapper = new QuotationDTOMapper();
		$quotationDTO = $mapper->toQuotationDTO($quotation);

		$this->assertEquals(100, $quotationDTO->getCustomerId());
		$this->assertEquals(200, $quotationDTO->getSupplierId());
		$this->assertEquals((new DateTime())->setTimestamp($quotation->date), $quotationDTO->getDate());

		// Line 1
		$this->assertEquals(10, $quotationDTO->getQuotationLines()[0]->getQuantity());
		$this->assertEquals(1, $quotationDTO->getQuotationLines()[0]->getProductId());
		// Line 2
		$this->assertEquals(5, $quotationDTO->getQuotationLines()[1]->getQuantity());
		$this->assertEquals(2, $quotationDTO->getQuotationLines()[1]->getProductId());
	}

	public function testQuotationDTOMapperConvertsToQuotation()
	{
		$date = new DateTime();
		$quotationDTO = new QuotationDTO();
		$quotationDTO
			->setCustomerId(100)
			->setSupplierId(200)
			->setDate($date);

		$quotationLineDTO1 = new QuotationLineDTO();
		$quotationLineDTO1
			->setQuantity(10)
			->setProductId(1)
			->setDescription('Desc')
			->setUnitprice(12.5);

		$quotationLineDTO2 = new QuotationLineDTO();
		$quotationLineDTO2
			->setQuantity(5)
			->setProductId(2)
			->setDescription('Desc')
			->setUnitprice(12.5)
			->setDiscount(10);

		$quotationDTO
			->addQuotationLine($quotationLineDTO1)
			->addQuotationLine($quotationLineDTO2);

		$mapper = new QuotationDTOMapper();
		$quotation = $mapper->toQuotation($quotationDTO);

		$this->assertEquals(100, $quotation->ref_customer);
		$this->assertEquals(200, $quotation->socid);
		$this->assertEquals($date->getTimestamp(), $quotation->date);

		// Line 1
		$this->assertEquals(1, $quotation->lines[0]->fk_product);
		$this->assertEquals('Desc', $quotation->lines[0]->desc);
		$this->assertEquals(12.500, $quotation->lines[0]->subprice);
		$this->assertEquals(10, $quotation->lines[0]->qty);
		$this->assertEquals(0, $quotation->lines[0]->remise_percent);
		// Line 2
		$this->assertEquals(2, $quotation->lines[1]->fk_product);
		$this->assertEquals('Desc', $quotation->lines[1]->desc);
		$this->assertEquals(12.500, $quotation->lines[1]->subprice);
		$this->assertEquals(5, $quotation->lines[1]->qty);
		$this->assertEquals(10, $quotation->lines[1]->remise_percent);
	}

	public function testQuotationDTOMapperHandlesNullQuotation()
	{
		global $db;
		$quotation = new Propal($db);
		$quotation->lines = null;
		$quotation->ref_customer = null;
		$quotation->socid = null;
		$quotation->date = null;

		$mapper = new QuotationDTOMapper();
		$quotationDTO = $mapper->toQuotationDTO($quotation);

		$this->assertEquals(0, $quotationDTO->getCustomerId());
		$this->assertEquals(0, $quotationDTO->getSupplierId());
		$this->assertEmpty($quotationDTO->getQuotationLines());
		//$this->assertNull($quotationDTO->getDate());
	}

	public function testQuotationDTOMapperHandlesNullQuotationDTO()
	{
		$date = new DateTime();
		$quotationDTO = new QuotationDTO();

		$mapper = new QuotationDTOMapper();
		$quotation = $mapper->toQuotation($quotationDTO);

		$this->assertEquals(0, $quotation->ref_customer);
		$this->assertEquals(0, $quotation->socid);
		$this->assertEquals($date->getTimestamp(), $quotation->date);
		$this->assertEmpty($quotation->lines);
	}

	public function testQuotationDTOMapperHandlesInvalidQuotationLine()
	{
		global $db;
		$quotation = new Propal($db);
		$quotation->lines = [(object) ['qty' => null, 'product' => null]];
		$quotation->ref_customer = 100;
		$quotation->socid = 200;
		$quotation->date = time();

		$mapper = new QuotationDTOMapper();
		$quotationDTO = $mapper->toQuotationDTO($quotation);

		$this->assertEquals(100, $quotationDTO->getCustomerId());
		$this->assertEquals(200, $quotationDTO->getSupplierId());
		$this->assertEquals((new DateTime())->setTimestamp($quotation->date), $quotationDTO->getDate());

		$this->assertEquals(0, $quotationDTO->getQuotationLines()[0]->getUnitprice());
		$this->assertEquals(1, $quotationDTO->getQuotationLines()[0]->getQuantity());
		$this->assertNull($quotationDTO->getQuotationLines()[0]->getProductId());
		$this->assertEquals(0, $quotationDTO->getQuotationLines()[0]->getDiscount());
		$this->assertEquals('', $quotationDTO->getQuotationLines()[0]->getDescription());
	}
}
