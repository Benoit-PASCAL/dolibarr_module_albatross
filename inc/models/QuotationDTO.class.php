<?php

namespace Albatross;

require_once __DIR__ . '/QuotationLineDTO.class.php';

use Albatross\QuotationLineDTO;

class QuotationDTO
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
	 * @var \QuotationLineDTO[]
	 */
	private $quotationLines;

	public function __construct()
	{
		$this->date = new \DateTime();
		$this->customerId = 0;
		$this->supplierId = 0;
		$this->quotationLines = [];
	}

	public function getDate(): \DateTime
	{
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDate($date): QuotationDTO
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
	public function setCustomerId($customerId): QuotationDTO
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
	public function setSupplierId($supplierId): QuotationDTO
	{
		$this->supplierId = $supplierId;
		return $this;
	}

	/**
	 * @return QuotationLineDTO[]
	 */
	public function getQuotationLines(): array
	{
		return $this->quotationLines;
	}

	/**
	 * @param \Albatross\QuotationLineDTO $quotationLine
	 */
	public function addQuotationLine($quotationLine): QuotationDTO
	{
		$this->quotationLines[] = $quotationLine;
		return $this;
	}
}
