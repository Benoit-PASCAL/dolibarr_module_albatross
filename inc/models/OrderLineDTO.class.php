<?php

namespace Albatross;

class OrderLineDTO
{
	/**
	 * @var ?int
	 */
	private $productId;

	/**
	 * @var int
	 */
	private $quantity;

	/**
	 * @var string description
	 */
	private $description;

	/**
	 * @var int $unitprice
	 */
	private $unitprice;

	/**
	 * @var int discount
	 */
	private $discount;

	public function __construct()
	{
		$this->quantity = 1;
		$this->discount = 0;
	}

	public function getProductId(): ?int
	{
		return $this->productId;
	}

	/**
	 * @param int $productId
	 */
	public function setProductId($productId): OrderLineDTO
	{
		$this->productId = $productId;
		return $this;
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

	/**
	 * @param int $quantity
	 */
	public function setQuantity($quantity): OrderLineDTO
	{
		$this->quantity = $quantity;
		return $this;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description): OrderLineDTO
	{
		$this->description = $description;
		return $this;
	}

	public function getUnitprice(): float
	{
		return $this->unitprice / 1000;
	}

	/**
	 * @param float $unitprice
	 */
	public function setUnitprice($unitprice): OrderLineDTO
	{
		$this->unitprice = $unitprice * 1000;
		return $this;
	}

	public function getDiscount(): int
	{
		return $this->discount;
	}

	/**
	 * @param int $discount
	 */
	public function setDiscount($discount): OrderLineDTO
	{
		$this->discount = $discount;
		return $this;
	}
}
