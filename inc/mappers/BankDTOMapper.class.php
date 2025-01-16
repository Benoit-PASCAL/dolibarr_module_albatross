<?php

namespace Albatross;

require_once dirname(__DIR__, 4) . '/compta/bank/class/account.class.php';

require_once dirname(__DIR__, 4) . '/compta/bank/class/account.class.php';
include_once dirname(__DIR__) . '/models/BankDTO.class.php';

use Albatross\BankDTO;
use \Account;

class BankDTOMapper
{
	/**
	 * @param BankDTO $bankDTO
	 */
	public function toBank($bankDTO): Account
	{
		global $db;

		$bank = new Account($db);

		$bank->ref = strtoupper(preg_replace('/[^\w]+/', '_', $bankDTO->getName()));
		//label correspond au libelle
		$bank->label = $bankDTO->getName();
		$bank->cle_rib = $bankDTO->getRib();
		$bank->owner_address = $bankDTO->getAddress();
		$bank->owner_zip = $bankDTO->getZipCode();
		$bank->owner_town = $bankDTO->getCity();

		$bank->date_solde = date('Y-m-d');
		$bank->country_id = 1;
		$bank->courant = \Account::TYPE_CURRENT;

		$bank->name = $bankDTO->getName();
		$bank->rib = $bankDTO->getRib();
		$bank->address = $bankDTO->getAddress();
		$bank->zipCode = $bankDTO->getZipCode();
		$bank->city = $bankDTO->getCity();

		return $bank;
	}
	/**
	 * @param \Bank $bank
	 */
	public function toBankDTO($bank): BankDTO
	{
		$bankDTO = new BankDTO();

		$map = [
			'label' => 'setName',
			'cle_rib' => 'setRib',
			'owner_address' => 'setAddress',
			'owner_zip' => 'setZipCode',
			'owner_town' => 'setCity'
		];

		foreach ($map as $field => $method) {
			if (isset($bank->$field)) {
				$bankDTO->$method($bank->$field);
			}
		}

		return $bankDTO;
	}
}



