<?php

namespace Albatross;

use Albatross\InvoiceDTO;
use Albatross\InvoiceLineDTO;
use DateTime;
use Facture;
use FactureLigne;

include_once dirname(__DIR__) . '/models/InvoiceDTO.class.php';
require_once dirname(__DIR__, 4) . '/compta/facture/class/facture.class.php';

class InvoiceDTOMapper
{
    /**
     * @param \Facture $invoice
     */
    public function toInvoiceDTO($invoice): InvoiceDTO
    {
        $invoiceDTO = new InvoiceDTO();
        $invoiceDTO
            ->setDate((new DateTime())->setTimestamp($invoice->date))
            ->setStatus($invoice->statut ?? InvoiceStatus::DRAFT)
            ->setCustomerId($invoice->ref_customer ?? 0)
            ->setSupplierId($invoice->socid ?? 0);

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
        global $db, $user;

        $invoice = new Facture($db);

        $invoice->date = $invoiceDTO->getDate()->getTimestamp();
        $invoice->socid = $invoiceDTO->getSupplierId();
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

            $invoice->lines[] = $invoiceLine;
        }

        return $invoice;
    }
}
