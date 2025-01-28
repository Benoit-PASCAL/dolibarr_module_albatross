<?php

namespace Albatross;

use Albatross\InvoiceLineDTO;

class QuotationDTO
{
	/**
	 * @var \DateTime
	 */
	private $date;

	/**
	 * @var int $status
	 */
	private $status;

	/**
	 * @var int
	 */
	private $customerId;

	/**
	 * @var int
	 */
	private $supplierId;

	/**
	 * @var \InvoiceLineDTO[]
	 */
	private $invoiceLines;

	/**
	 * @var ?int
	 */
	private $project;

	public function __construct()
	{
		$this->date = new \DateTime();
		$this->status = InvoiceStatus::DRAFT;
		$this->customerId = 0;
		$this->supplierId = 0;
		$this->invoiceLines = [];

		// optional
		$this->project = null;
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

	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status): QuotationDTO
	{
		$this->status = $status;
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
	 * @return InvoiceLineDTO[]
	 */
	public function getInvoiceLines(): array
	{
		return $this->invoiceLines;
	}

	/**
	 * @param \Albatross\InvoiceLineDTO $invoiceLine
	 */
	public function addInvoiceLine($invoiceLine): QuotationDTO
	{
		$this->invoiceLines[] = $invoiceLine;
		return $this;
	}

	public function getProject(): ?int
	{
		return $this->project;
	}

	/**
	 * @param int $project
	 */
	public function setProject($project): QuotationDTO
	{
		$this->project = $project;
		return $this;
	}
}

