<?php

namespace Albatross;

use Albatross\QuotationDTO;
use Albatross\InvoiceLineDTO;
use DateTime;
use FactureLigne;
use Propal;


include_once dirname(__DIR__) . "/models/QuotationDTO.class.php";
require_once dirname(__DIR__, 4) . "/comm/propal/class/propal.class.php";
require_once dirname(__DIR__, 4) . '/fourn/class/fournisseur.facture.class.php';
require_once dirname(__DIR__, 4) . '/compta/facture/class/factureligne.class.php';


class QuotationDTOMapper
{
	/**
	 * @param \Albatross\QuotationDTO $quotationDTO
	 */

	public function toQuotation($quotationDTO): Propal
	{
		global $db;

		$quotation = new Propal($db);

		$quotation->date = $quotationDTO->getDate()->getTimestamp();
		$quotation->socid = $quotationDTO->getSupplierId();
		$quotation->ref_customer = $quotationDTO->getCustomerId();
		$quotation->statut = $quotationDTO->getStatus();
		$quotation->entity = 1;

		// optional
		$quotation->fk_project = $quotationDTO->getProject();
		foreach ($quotationDTO->getInvoiceLines() as $invoiceLineDTO) {
			$quotationLine = new FactureLigne($db);

			$quotationLine->fk_product = $invoiceLineDTO->getProductId();
			$quotationLine->desc = $invoiceLineDTO->getDescription();
			$quotationLine->subprice = $invoiceLineDTO->getUnitprice();
			$quotationLine->remise_percent = $invoiceLineDTO->getDiscount();
			$quotationLine->qty = $invoiceLineDTO->getQuantity();

			$quotation->lines[] = $quotationLine;
		}


		return $quotation;
	}

	public function toSupplierQuotation(\Albatross\QuotationDTO $quotationDTO): propal
	{
		global $db;

		$quotation = new propal($db);

		$quotation->date = $quotationDTO->getDate()->getTimestamp();
		$quotation->statut = $quotationDTO->getStatus();
		$quotation->ref_customer = $quotationDTO->getCustomerId();
		$quotation->socid = $quotationDTO->getSupplierId();

		foreach ($quotationDTO->getInvoiceLines() as $quotationLineDTO) {
			$quotationLine = new FactureLigne($db);

			$quotationLine->fk_product = $quotationLineDTO->getProductId();
			$quotationLine->desc = $quotationLineDTO->getDescription();
			$quotationLine->subprice = $quotationLineDTO->getUnitprice();
			$quotationLine->remise_percent = $quotationLineDTO->getDiscount();
			$quotationLine->qty = $quotationLineDTO->getQuantity();

			$quotation->lines[] = $quotationLine;
		}

		return $quotation;
	}

}
