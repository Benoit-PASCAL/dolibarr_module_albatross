<?php

namespace Albatross;


include_once dirname(__DIR__) . '/models/BankDTO.class.php';


class BankDTOMapper
{
	/**
	 * @param \Albatross\models\BankDTO $bankDTO
	 */
	public function toBank($bankDTO): Bank
	{
		global $db;

		$bank = new Bank($db);

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
	public function toBankDTO($bankDTO): BankDTO
	{
		$requiredFiles = ['name', 'rib', 'address', 'zipCode', 'city'];
		foreach ($requiredFiles as $field) {
			if (!isset($bank->$field)) {
				throw new \Exception("Missing required field: $field");
			}
		}

		$bankDTO = new BankDTO();
		$bankDTO
			->setName($bank->name)
			->setRib($bank->rib)
			->setAddress($bank->address)
			->setZipCode($bank->zipCode)
			->setCity($bank->city);

		return $bankDTO;
	}
}



