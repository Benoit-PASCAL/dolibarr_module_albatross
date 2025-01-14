<?php

namespace Albatross;

require_once __DIR__ . '/InvoiceLineDTO.class.php';

use Albatross\models\InvoiceLineDTO;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical;

class InvoiceStatus
{
	const DRAFT = 0;
	const VALIDATED = 1;
	const SENT = 2;
	const PAID = 3;
	const CANCELLED = -1;
}

class InvoiceDTO
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
	public function setDate($date): InvoiceDTO
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
	public function setStatus($status): InvoiceDTO
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
	public function setCustomerId($customerId): InvoiceDTO
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
	public function setSupplierId($supplierId): InvoiceDTO
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
	 * @param \Albatross\models\InvoiceLineDTO $invoiceLine
	 */
	public function addInvoiceLine($invoiceLine): InvoiceDTO
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
	public function setProject($project): InvoiceDTO
	{
		$this->project = $project;
		return $this;
	}
}
