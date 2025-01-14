<?php

namespace Albatross;

class BankDTO
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $rib;

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

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): BankDTO
	{
		$this->name = $name;
		return $this;
	}

	public function getRib(): string
	{
		return $this->rib;
	}

	public function setRib(string $rib): BankDTO
	{
		$this->rib = $rib;
		return $this;
	}

	public function getAddress(): string
	{
		return $this->address;
	}

	public function setAddress(string $address): BankDTO
	{
		$this->address = $address;
		return $this;
	}

	public function getZipCode(): string
	{
		return $this->zipCode;
	}

	public function setZipCode(string $zipCode): BankDTO
	{
		$this->zipCode = $zipCode;
		return $this;
	}

	public function getCity(): string
	{
		return $this->city;
	}

	public function setCity(string $city): BankDTO
	{
		$this->city = $city;
		return $this;
	}


}
