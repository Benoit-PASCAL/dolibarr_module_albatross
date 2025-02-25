<?php

namespace Albatross;

use Albatross\InvoiceDTO;
use Albatross\InvoiceLineDTO;
use DateTime;
use Facture;
use FactureLigne;
use FactureFournisseur;

include_once dirname(__DIR__) . '/models/InvoiceDTO.class.php';
require_once dirname(__DIR__, 4) . '/compta/facture/class/facture.class.php';
require_once dirname(__DIR__, 4) . '/fourn/class/fournisseur.facture.class.php';

class InvoiceDTOMapper
{
	/**
	 * @param Facture|FactureFournisseur $invoice
	 */
	public function toInvoiceDTO($invoice): InvoiceDTO
	{
		$invoiceDTO = new InvoiceDTO();
		$invoiceDTO
			->setDate((new DateTime())->setTimestamp($invoice->date))
			->setStatus($invoice->statut ?? InvoiceStatus::DRAFT);

		if ($invoice instanceof FactureFournisseur) {
			$invoiceDTO
				->setSupplierId($invoice->socid)
				->setNumber($invoice->ref_supplier);
		}

		if ($invoice instanceof Facture) {
			$invoiceDTO->setCustomerId($invoice->socid);
		}

		// optional
		if ($invoice->fk_project > 0) {
			$invoiceDTO->setProject($invoice->fk_project);
		}

		foreach ($invoice->lines ?? [] as $line) {
			$invoiceLineDTO = new InvoiceLineDTO();
			$invoiceLineDTO
				->setUnitprice($line->subprice ?? 0)
				->setQuantity($line->qty ?? 1)
				->setDescription($line->desc ?? '')
				->setDiscount($line->remise_percent ?? 0);

			if (!is_null($line->product) && !is_null($line->product->id)) {
				$invoiceLineDTO->setProductId($line->product->id);
			}

			$invoiceDTO->addInvoiceLine($invoiceLineDTO);
		}

		return $invoiceDTO;
	}

	/**
	 * @param \Albatross\InvoiceDTO $invoiceDTO
	 */
	public function toInvoice($invoiceDTO): Facture
	{
		global $db;

		$invoice = new Facture($db);

		$invoice->date = $invoiceDTO->getDate()->getTimestamp();
		$invoice->socid = $invoiceDTO->getCustomerId();
		$invoice->ref_customer = $invoiceDTO->getCustomerId();
		$invoice->statut = $invoiceDTO->getStatus();
		$invoice->entity = 1;

		// optional
		$invoice->fk_project = $invoiceDTO->getProject();
		foreach ($invoiceDTO->getInvoiceLines() as $invoiceLineDTO) {
			$invoiceLine = new FactureLigne($db);

			$invoiceLine->fk_product = $invoiceLineDTO->getProductId();
			$invoiceLine->desc = $invoiceLineDTO->getDescription();
			$invoiceLine->subprice = $invoiceLineDTO->getUnitprice();
			$invoiceLine->remise_percent = $invoiceLineDTO->getDiscount();
			$invoiceLine->qty = $invoiceLineDTO->getQuantity();

			// FIXME: avoid hardcoded values
			$invoiceLine->tva_tx = 0.2;
			$invoiceLine->total_ht = $invoiceLine->subprice * $invoiceLine->qty;
			$invoiceLine->total_tva = $invoiceLine->total_ht * $invoiceLine->tva_tx;
			$invoiceLine->total_ttc = $invoiceLine->total_ht + $invoiceLine->total_tva;

			$invoice->lines[] = $invoiceLine;
		}

		return $invoice;
	}

	/**
	 * @param InvoiceDTO $invoiceDTO
	 * @return FactureFournisseur
	 */
	public function toSupplierInvoice($invoiceDTO): FactureFournisseur
	{
		global $db;

		$invoice = new FactureFournisseur($db);

		$invoice->date = $invoiceDTO->getDate()->getTimestamp();
		$invoice->statut = $invoiceDTO->getStatus();
		$invoice->socid = $invoiceDTO->getSupplierId();
		$invoice->ref_supplier = $invoiceDTO->getNumber();


		foreach ($invoiceDTO->getInvoiceLines() as $invoiceLineDTO) {
			$invoiceLine = new FactureLigne($db);

			$invoiceLine->fk_product = $invoiceLineDTO->getProductId();
			$invoiceLine->desc = $invoiceLineDTO->getDescription();
			$invoiceLine->subprice = $invoiceLineDTO->getUnitprice();
			$invoiceLine->remise_percent = $invoiceLineDTO->getDiscount();
			$invoiceLine->qty = $invoiceLineDTO->getQuantity();
			$invoiceLine->ref_supplier = $invoiceDTO->getNumber();

			$invoice->lines[] = $invoiceLine;
		}

		return $invoice;
	}
}
