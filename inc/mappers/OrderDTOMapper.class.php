<?php

namespace Albatross;


use FactureLigne;
use Commande;
use CommandeFournisseur;
use CommandeFournisseurLigne;

include_once dirname(__DIR__) . '/models/OrderDTO.class.php';
require_once dirname(__DIR__, 4) . '/commande/class/commande.class.php';
require_once dirname(__DIR__, 4) . '/fourn/class/fournisseur.commande.class.php';


class OrderDTOMapper
{
	/**
	 * @param Commande|CommandeFournisseur $order
	 */
	public function toOrderDTO($order): OrderDTO
	{
		$orderDTO = new OrderDTO();
		$orderDTO
			->setDate((new \DateTime())->setTimestamp($order->date));

		if ($order instanceof CommandeFournisseur) {
			$orderDTO->setSupplierId($order->socid);
		}

		if ($order instanceof Commande) {
			$orderDTO->setCustomerId($order->socid);
		}

		foreach ($order->lines ?? [] as $line) {
			$orderLineDTO = new OrderLineDTO();
			$orderLineDTO
				->setUnitprice($line->subprice ?? 0)
				->setQuantity($line->qty ?? 1)
				->setDescription($line->desc ?? '')
				->setDiscount($line->remise_percent ?? 0);

			if (!is_null($line->product) && !is_null($line->product->id)) {
				$orderLineDTO->setProductId($line->product->id);
			}

			$orderDTO->addOrderLine($orderLineDTO);
		}

		return $orderDTO;
	}

	/**
	 * @param \Albatross\OrderDTO $orderDTO
	 */
	public function toOrder($orderDTO): \Commande
	{
		global $db, $user;

		$order = new \Commande($db);

		$order->date = $orderDTO->getDate()->getTimestamp();
		$order->socid = $orderDTO->getCustomerId();

		foreach ($orderDTO->getOrderLines() as $orderLineDTO) {
			$orderLine = new \OrderLine($db);

			$orderLine->fk_product = $orderLineDTO->getProductId();
			$orderLine->desc = $orderLineDTO->getDescription();
			$orderLine->subprice = $orderLineDTO->getUnitprice();
			$orderLine->remise_percent = $orderLineDTO->getDiscount();
			$orderLine->qty = $orderLineDTO->getQuantity();

			$order->lines[] = $orderLine;
		}

		return $order;
	}


	/**
	 * @param OrderDTO $orderDTO
	 */
	public function toSupplierOrder($orderDTO): \CommandeFournisseur
	{
		global $db;

		$order = new \CommandeFournisseur($db);

		$order->date = $orderDTO->getDate()->getTimestamp();
		$order->ref_customer = $orderDTO->getCustomerId();
		$order->socid = $orderDTO->getSupplierId();

		foreach ($orderDTO->getOrderLines() as $orderLineDTO) {
			$orderLine = new \CommandeFournisseurLigne($db);

			$orderLine->fk_product = $orderLineDTO->getProductId();
			$orderLine->desc = $orderLineDTO->getDescription();
			$orderLine->subprice = $orderLineDTO->getUnitprice();
			$orderLine->remise_percent = $orderLineDTO->getDiscount();
			$orderLine->qty = $orderLineDTO->getQuantity();

			$order->lines[] = $orderLine;
		}

		return $order;
	}
}
