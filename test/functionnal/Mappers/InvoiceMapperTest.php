<?php

namespace test\functional;


// Require tested class
require_once MODULE_ROOT . '/inc/mappers/InvoiceDTOMapper.class.php';


use Albatross\InvoiceDTO;
use Albatross\InvoiceDTOMapper;
use Albatross\InvoiceLineDTO;
use DateTime;
use Facture;
use FactureFournisseur;
use PHPUnit\Framework\TestCase;

class InvoiceMapperTest extends TestCase
{
	public function testInvoiceDTOMapperConvertsToInvoiceDTO()
	{
		global $db;
		$invoice = new Facture($db);
		$invoice->lines = [
			(object) ['qty' => 10, 'product' => (object) ['id' => 1]],
			(object) ['qty' => 5, 'product' => (object) ['id' => 2]]
		];
		$invoice->socid = 200;
		$invoice->date = time();

		$mapper = new InvoiceDTOMapper();
		$invoiceDTO = $mapper->toInvoiceDTO($invoice);

		$this->assertEquals(200, $invoiceDTO->getCustomerId());
		$this->assertEquals((new DateTime())->setTimestamp($invoice->date), $invoiceDTO->getDate());

		// Line 1
		$this->assertEquals(10, $invoiceDTO->getInvoiceLines()[0]->getQuantity());
		$this->assertEquals(1, $invoiceDTO->getInvoiceLines()[0]->getProductId());
		// Line 2
		$this->assertEquals(5, $invoiceDTO->getInvoiceLines()[1]->getQuantity());
		$this->assertEquals(2, $invoiceDTO->getInvoiceLines()[1]->getProductId());
	}

	public function testInvoiceDTOMapperConvertsToSupplierInvoiceDTO()
	{
		global $db;
		$invoice = new FactureFournisseur($db);
		$invoice->lines = [
			(object) ['qty' => 10, 'product' => (object) ['id' => 1]],
			(object) ['qty' => 5, 'product' => (object) ['id' => 2]]
		];
		$invoice->socid = 200;
		$invoice->date = time();
		$invoice->ref_supplier = 'FAC-2025-0001';

		$mapper = new InvoiceDTOMapper();
		$invoiceDTO = $mapper->toInvoiceDTO($invoice);

		$this->assertEquals(200, $invoiceDTO->getSupplierId());
		$this->assertEquals((new DateTime())->setTimestamp($invoice->date), $invoiceDTO->getDate());
		$this->assertEquals('FAC-2025-0001', $invoiceDTO->getNumber());

		// Line 1
		$this->assertEquals(10, $invoiceDTO->getInvoiceLines()[0]->getQuantity());
		$this->assertEquals(1, $invoiceDTO->getInvoiceLines()[0]->getProductId());
		// Line 2
		$this->assertEquals(5, $invoiceDTO->getInvoiceLines()[1]->getQuantity());
		$this->assertEquals(2, $invoiceDTO->getInvoiceLines()[1]->getProductId());
	}

	public function testInvoiceDTOMapperConvertsToInvoice()
	{
		$date = new DateTime();
		$invoiceDTO = new InvoiceDTO();
		$invoiceDTO
			->setCustomerId(100)
			->setDate($date);

		$invoiceLineDTO1 = new InvoiceLineDTO();
		$invoiceLineDTO1
			->setQuantity(10)
			->setProductId(1)
			->setDescription('Desc')
			->setUnitprice(12.5);

		$invoiceLineDTO2 = new InvoiceLineDTO();
		$invoiceLineDTO2
			->setQuantity(5)
			->setProductId(2)
			->setDescription('Desc')
			->setUnitprice(12.5)
			->setDiscount(10);

		$invoiceDTO
			->addInvoiceLine($invoiceLineDTO1)
			->addInvoiceLine($invoiceLineDTO2);

		$mapper = new InvoiceDTOMapper();
		$invoice = $mapper->toInvoice($invoiceDTO);

		$this->assertEquals(100, $invoice->socid);
		$this->assertEquals($date->getTimestamp(), $invoice->date);

		// Line 1
		$this->assertEquals(1, $invoice->lines[0]->fk_product);
		$this->assertEquals('Desc', $invoice->lines[0]->desc);
		$this->assertEquals(12.500, $invoice->lines[0]->subprice);
		$this->assertEquals(10, $invoice->lines[0]->qty);
		$this->assertEquals(0, $invoice->lines[0]->remise_percent);
		// Line 2
		$this->assertEquals(2, $invoice->lines[1]->fk_product);
		$this->assertEquals('Desc', $invoice->lines[1]->desc);
		$this->assertEquals(12.500, $invoice->lines[1]->subprice);
		$this->assertEquals(5, $invoice->lines[1]->qty);
		$this->assertEquals(10, $invoice->lines[1]->remise_percent);
	}

	public function testInvoiceDTOMapperConvertsToSupplierInvoice()
	{
		$date = new DateTime();
		$invoiceDTO = new InvoiceDTO();
		$invoiceDTO
			->setNumber('FAC-2025-0001')
			->setSupplierId(200)
			->setDate($date);

		$invoiceLineDTO1 = new InvoiceLineDTO();
		$invoiceLineDTO1
			->setQuantity(10)
			->setProductId(1)
			->setDescription('Desc')
			->setUnitprice(12.5);

		$invoiceLineDTO2 = new InvoiceLineDTO();
		$invoiceLineDTO2
			->setQuantity(5)
			->setProductId(2)
			->setDescription('Desc')
			->setUnitprice(12.5)
			->setDiscount(10);

		$invoiceDTO
			->addInvoiceLine($invoiceLineDTO1)
			->addInvoiceLine($invoiceLineDTO2);

		$mapper = new InvoiceDTOMapper();
		$supplierInvoice = $mapper->toSupplierInvoice($invoiceDTO);

		$this->assertEquals($date->getTimestamp(), $supplierInvoice->date);
		$this->assertEquals(200, $supplierInvoice->socid);
		$this->assertEquals('FAC-2025-0001', $supplierInvoice->ref_supplier);

		// Line 1
		$this->assertEquals(1, $supplierInvoice->lines[0]->fk_product);
		$this->assertEquals('Desc', $supplierInvoice->lines[0]->desc);
		$this->assertEquals(12.500, $supplierInvoice->lines[0]->subprice);
		$this->assertEquals(10, $supplierInvoice->lines[0]->qty);
		$this->assertEquals(0, $supplierInvoice->lines[0]->remise_percent);
		// Line 2
		$this->assertEquals(2, $supplierInvoice->lines[1]->fk_product);
		$this->assertEquals('Desc', $supplierInvoice->lines[1]->desc);
		$this->assertEquals(12.500, $supplierInvoice->lines[1]->subprice);
		$this->assertEquals(5, $supplierInvoice->lines[1]->qty);
		$this->assertEquals(10, $supplierInvoice->lines[1]->remise_percent);
	}
}
