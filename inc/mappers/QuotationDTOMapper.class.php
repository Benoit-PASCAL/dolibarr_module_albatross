<?php

namespace Albatross;

require_once dirname(__DIR__) . "/models/QuotationDTO.class.php";
require_once dirname(__DIR__, 4) . "/comm/propal/class/propal.class.php";
require_once dirname(__DIR__, 4) . '/fourn/class/fournisseur.facture.class.php';
require_once dirname(__DIR__, 4) . '/compta/facture/class/facture.class.php';
require_once dirname(__DIR__, 4) . '/supplier_proposal/class/supplier_proposal.class.php';

use Albatross\QuotationDTO;
use DateTime;
use FactureLigne;
use Propal;
use SupplierProposal;




class QuotationDTOMapper
{

	/**
	 * @param Propal|SupplierProposal $quotation
	 */
	public function toQuotationDTO($quotation): QuotationDTO
	{
		$quotationDTO = new QuotationDTO();
		$quotationDTO
			->setDate((new DateTime())->setTimestamp($quotation->date))
			->setStatus($quotation->statut ?? InvoiceStatus::DRAFT);

		if ($quotation instanceof Propal) {
			$quotationDTO->setCustomerId($quotation->socid);
		}

		if ($quotation instanceof SupplierProposal) {
			$quotationDTO->setSupplierId($quotation->socid);
		}

		// optional
		if ($quotation->fk_project > 0) {
			$quotationDTO->setProject($quotation->fk_project);
		}

		foreach ($quotation->lines ?? [] as $line) {
			$quotationLineDTO = new InvoiceLineDTO();
			$quotationLineDTO
				->setUnitprice($line->subprice ?? 0)
				->setQuantity($line->qty ?? 1)
				->setDescription($line->desc ?? '')
				->setDiscount($line->remise_percent ?? 0);

			if (!is_null($line->product) && !is_null($line->product->id)) {
				$quotationLineDTO->setProductId($line->product->id);
			}

			$quotationDTO->addQuotationLine($quotationLineDTO);
		}

		return $quotationDTO;
	}

	/**
	 * @param QuotationDTO $quotationDTO
	 */
	public function toQuotation($quotationDTO): Propal
	{
		global $db;

		$quotation = new Propal($db);

		$quotation->date = $quotationDTO->getDate()->getTimestamp();
		$quotation->socid = $quotationDTO->getCustomerId();
		$quotation->statut = $quotationDTO->getStatus();
		$quotation->status = $quotationDTO->getStatus();
		$quotation->entity = 1;

		// optional
		foreach ($quotationDTO->getQuotationLines() as $quotationLineDTO) {
			$quotationLine = new \PropaleLigne($db);

			$quotationLine->fk_product = $quotationLineDTO->getProductId();
			$quotationLine->desc = $quotationLineDTO->getDescription();
			$quotationLine->subprice = $quotationLineDTO->getUnitprice();
			$quotationLine->remise_percent = $quotationLineDTO->getDiscount();
			$quotationLine->qty = $quotationLineDTO->getQuantity();

			$quotation->lines[] = $quotationLine;
		}

		return $quotation;
	}

	/**
	 * @param \QuotationDTO $quotationDTO
	 */
	public function toSupplierQuotation($quotationDTO): SupplierProposal
	{
		global $db;

		$quotation = new SupplierProposal($db);

		$quotation->date = $quotationDTO->getDate()->getTimestamp();
		$quotation->socid = $quotationDTO->getSupplierId();
		$quotation->entity = 1;

		foreach ($quotationDTO->getQuotationLines() as $quotationLineDTO) {
			$quotationLine = new \SupplierProposalLine($db);

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
