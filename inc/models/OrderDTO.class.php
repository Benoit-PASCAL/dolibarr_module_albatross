<?php

namespace Albatross;

require_once __DIR__ . '/OrderLineDTO.class.php';
use Albatross\OrderLineDTO;

class OrderDTO
{
	/**
	 * @var \DateTime
	 */
	private $date;

	/**
	 * @var int
	*/
	private $customerId;

	/**
	 * @var int
	 */
	private $supplierId;

	/**
	 * @var \OrderLineDTO[]
	 */
	private $orderLines;

	public function __construct()
	{
		$this->date = new \DateTime();
		$this->customerId = 0;
		$this->supplierId = 0;
		$this->orderLines = [];
	}

	public function getDate(): \DateTime
	{
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDate($date): OrderDTO
	{
		$this->date = $date;
		return $this;
	}

	public function getCustomerId(): int
	{
		return $this->customerId;
	}

	/**
	 * @param int $customerId
	 */
	public function setCustomerId($customerId): OrderDTO
	{
		$this->customerId = $customerId;
		return $this;
	}

	public function getSupplierId(): int
	{
		return $this->supplierId;
	}

	/**
	 * @param int $supplierId
	 */
	public function setSupplierId($supplierId): OrderDTO
	{
		$this->supplierId = $supplierId;
		return $this;
	}

	/**
	 * @return OrderLineDTO[]
	 */
	public function getOrderLines(): array
	{
		return $this->orderLines;
	}

	/**
	 * @param \Albatross\OrderLineDTO $orderLine
	 */
	public function addOrderLine($orderLine): OrderDTO
	{
		$this->orderLines[] = $orderLine;
		return $this;
	}
}
