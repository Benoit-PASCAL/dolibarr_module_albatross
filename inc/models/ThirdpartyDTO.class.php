<?php

namespace Albatross;

class ThirdpartyDTO
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $siret;

	/**
	 * @var string
	 */
	private $address;

	/**
	 * @var string
	 */
	private $zipCode;

	/**
	 * @var string
	 */
	private $city;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $phone;

	/**
	 * @var int
	 */
	private $entity;

	/**
	 * @var string
	 */
	private $iban;

	/**
	 * string
	 */
	private $bic;

	/**
	 * @var string
	 */
	private $accountOwner;

	/**
	 * @var bool
	 */
	private $vat_used;

	public function __construct()
	{
		$this->name = '';
		$this->address = '';
		$this->zipCode = '';
		$this->city = '';
		$this->email = '';
		$this->phone = '';
		$this->entity = 0;
		$this->vat_used = true;
	}

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name): ThirdpartyDTO
	{
		$this->name = $name;
		return $this;
	}

	public function getSiret(): string
	{
		return $this->siret ?? '';
	}

	/**
	 * @param string $siret
	 */
	public function setSiret($siret): ThirdpartyDTO
	{
		$this->siret = $siret;
		return $this;
	}

	public function getAddress(): string
	{
		return $this->address;
	}

	/**
	 * @param string $address
	 */
	public function setAddress($address): ThirdpartyDTO
	{
		$this->address = $address;
		return $this;
	}

	public function getZipCode(): string
	{
		return $this->zipCode;
	}

	/**
	 * @param string $zipCode
	 */
	public function setZipCode($zipCode): ThirdpartyDTO
	{
		$this->zipCode = $zipCode;
		return $this;
	}

	public function getCity(): string
	{
		return $this->city;
	}

	/**
	 * @param string $city
	 */
	public function setCity($city): ThirdpartyDTO
	{
		$this->city = $city;
		return $this;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email): ThirdpartyDTO
	{
		$this->email = $email;
		return $this;
	}

	public function getPhone(): string
	{
		return $this->phone;
	}

	/**
	 * @param string $phone
	 */
	public function setPhone($phone): ThirdpartyDTO
	{
		$this->phone = $phone;
		return $this;
	}

	public function getEntity(): int
	{
		return $this->entity;
	}

	/**
	 * @param int $entity
	 */
	public function setEntity($entity): ThirdpartyDTO
	{
		$this->entity = $entity;
		return $this;
	}

	public function getIban(): string
	{
		return $this->iban ?? '';
	}

	/**
	 * @param string $iban
	 */
	public function setIban($iban): ThirdpartyDTO
	{
		$this->iban = $iban;
		return $this;
	}

	public function getBic()
	{
		return $this->bic ?? '';
	}

	public function setBic($bic)
	{
		$this->bic = $bic;
		return $this;
	}

	public function getAccountOwner(): string
	{
		return $this->accountOwner ?? '';
	}

	/**
	 * @param string $accountOwner
	 */
	public function setAccountOwner($accountOwner): ThirdpartyDTO
	{
		$this->accountOwner = $accountOwner;
		return $this;
	}

	public function isVatUsed(): bool
	{
		return $this->vat_used;
	}

	/**
	 * @param bool $vat_used
	 */
	public function setVatUsed($vat_used = true): ThirdpartyDTO
	{
		$this->vat_used = $vat_used;
		return $this;
	}
}
