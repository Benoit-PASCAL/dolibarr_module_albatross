<?php

namespace Albatross;

class ProductDTO
{
	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var int
	 */
	private $taxFreePrice;

	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label): ProductDTO
	{
		$this->label = $label;
		return $this;
	}

	public function getTaxFreePrice(): float
	{
		return (float) $this->taxFreePrice / 100;
	}

	/**
	 * @param float $taxFreePrice
	 */
	public function setTaxFreePrice($taxFreePrice): ProductDTO
	{
		$this->taxFreePrice = (int) $taxFreePrice * 100;
		return $this;
	}
}
