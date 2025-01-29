<?php

namespace test\functional;


require_once MODULE_ROOT . '/inc/mappers/OrderDTOMapper.class.php';


use Albatross\OrderDTO;
use Albatross\OrderDTOMapper;
use Albatross\OrderLineDTO;
use Commande;
use CommandeFournisseur;
use DateTime;
use PHPUnit\Framework\TestCase;

class OrderMapperTest extends TestCase
{
	public function testOrderDTOMapperConvertsToOrderDTO()
	{
		global $db;
		$order = new Commande($db);
		$order->lines = [
			(object) ['qty' => 10, 'product' => (object) ['id' => 1]],
			(object) ['qty' => 5, 'product' => (object) ['id' => 2]]
		];
		$order->socid = 200;
		$order->date = time();

		$mapper = new OrderDTOMapper();
		$orderDTO = $mapper->toOrderDTO($order);

		$this->assertEquals(200, $orderDTO->getCustomerId());
		$this->assertEquals((new DateTime())->setTimestamp($order->date), $orderDTO->getDate());

		// Line 1
		$this->assertEquals(10, $orderDTO->getOrderLines()[0]->getQuantity());
		$this->assertEquals(1, $orderDTO->getOrderLines()[0]->getProductId());
		// Line 2
		$this->assertEquals(5, $orderDTO->getOrderLines()[1]->getQuantity());
		$this->assertEquals(2, $orderDTO->getOrderLines()[1]->getProductId());
	}

	public function testOrderDTOMapperConvertsToSupplierOrderDTO()
	{
		global $db;
		$order = new CommandeFournisseur($db);
		$order->lines = [
			(object) ['qty' => 10, 'product' => (object) ['id' => 1]],
			(object) ['qty' => 5, 'product' => (object) ['id' => 2]]
		];
		$order->socid = 200;
		$order->date = time();

		$mapper = new OrderDTOMapper();
		$orderDTO = $mapper->toOrderDTO($order);

		$this->assertEquals(200, $orderDTO->getSupplierId());
		$this->assertEquals((new DateTime())->setTimestamp($order->date), $orderDTO->getDate());

		// Line 1
		$this->assertEquals(10, $orderDTO->getOrderLines()[0]->getQuantity());
		$this->assertEquals(1, $orderDTO->getOrderLines()[0]->getProductId());
		// Line 2
		$this->assertEquals(5, $orderDTO->getOrderLines()[1]->getQuantity());
		$this->assertEquals(2, $orderDTO->getOrderLines()[1]->getProductId());
	}

	public function testOrderDTOMapperConvertsToOrder()
	{
		$date = new DateTime();
		$orderDTO = new OrderDTO();
		$orderDTO
			->setCustomerId(100)
			->setDate($date);

		$orderLineDTO1 = new OrderLineDTO();
		$orderLineDTO1
			->setQuantity(10)
			->setProductId(1)
			->setDescription('Desc')
			->setUnitprice(12.5);

		$orderLineDTO2 = new OrderLineDTO();
		$orderLineDTO2
			->setQuantity(5)
			->setProductId(2)
			->setDescription('Desc')
			->setUnitprice(12.5)
			->setDiscount(10);

		$orderDTO
			->addOrderLine($orderLineDTO1)
			->addOrderLine($orderLineDTO2);

		$mapper = new OrderDTOMapper();
		$order = $mapper->toOrder($orderDTO);

		$this->assertEquals(100, $order->socid);
		$this->assertEquals($date->getTimestamp(), $order->date);

		// Line 1
		$this->assertEquals(1, $order->lines[0]->fk_product);
		$this->assertEquals('Desc', $order->lines[0]->desc);
		$this->assertEquals(12.500, $order->lines[0]->subprice);
		$this->assertEquals(10, $order->lines[0]->qty);
		$this->assertEquals(0, $order->lines[0]->remise_percent);
		// Line 2
		$this->assertEquals(2, $order->lines[1]->fk_product);
		$this->assertEquals('Desc', $order->lines[1]->desc);
		$this->assertEquals(12.500, $order->lines[1]->subprice);
		$this->assertEquals(5, $order->lines[1]->qty);
		$this->assertEquals(10, $order->lines[1]->remise_percent);
	}

	public function testOrderDTOMapperConvertsToSupplierOrder()
	{
		$date = new DateTime();
		$orderDTO = new OrderDTO();
		$orderDTO
			->setSupplierId(200)
			->setDate($date);

		$orderLineDTO1 = new OrderLineDTO();
		$orderLineDTO1
			->setQuantity(10)
			->setProductId(1)
			->setDescription('Desc')
			->setUnitprice(12.5);

		$orderLineDTO2 = new OrderLineDTO();
		$orderLineDTO2
			->setQuantity(5)
			->setProductId(2)
			->setDescription('Desc')
			->setUnitprice(12.5)
			->setDiscount(10);

		$orderDTO
			->addOrderLine($orderLineDTO1)
			->addOrderLine($orderLineDTO2);

		$mapper = new OrderDTOMapper();
		$order = $mapper->toSupplierOrder($orderDTO);

		$this->assertEquals(200, $order->socid);
		$this->assertEquals($date->getTimestamp(), $order->date);

		// Line 1
		$this->assertEquals(1, $order->lines[0]->fk_product);
		$this->assertEquals('Desc', $order->lines[0]->desc);
		$this->assertEquals(12.500, $order->lines[0]->subprice);
		$this->assertEquals(10, $order->lines[0]->qty);
		$this->assertEquals(0, $order->lines[0]->remise_percent);
		// Line 2
		$this->assertEquals(2, $order->lines[1]->fk_product);
		$this->assertEquals('Desc', $order->lines[1]->desc);
		$this->assertEquals(12.500, $order->lines[1]->subprice);
		$this->assertEquals(5, $order->lines[1]->qty);
		$this->assertEquals(10, $order->lines[1]->remise_percent);
	}
}
