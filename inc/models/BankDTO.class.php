<?php

namespace Albatross;

class BankDTO
{
	/**
	 * @var ?string
	 */
	private $name;

	/**
	 * @var ?string
	 */
	private $rib;

	/**
	 * @var ?string
	 */
	private $address;


	/**
	 * @var ?string
	 */
	private $zipCode;

	/**
	 * @var ?string
	 */
	private $city;

	public function getName(): ?string
	{
		return $this->name;
	}

	/**
  * @param string $name
  */
	public function setName($name): BankDTO
	{
		$this->name = $name;
		return $this;
	}

	public function getRib(): ?string
	{
		return $this->rib;
	}

	/**
  * @param string $rib
  */
	public function setRib($rib): BankDTO
	{
		$this->rib = $rib;
		return $this;
	}

	public function getAddress(): ?string
	{
		return $this->address;
	}

	/**
  * @param string $address
  */
	public function setAddress($address): BankDTO
	{
		$this->address = $address;
		return $this;
	}

	public function getZipCode(): ?string
	{
		return $this->zipCode;
	}

	/**
  * @param string $zipCode
  */
	public function setZipCode($zipCode): BankDTO
	{
		$this->zipCode = $zipCode;
		return $this;
	}

	public function getCity(): ?string
	{
		return $this->city;
	}

	/**
  * @param string $city
  */
	public function setCity($city): BankDTO
	{
		$this->city = $city;
		return $this;
	}
}
