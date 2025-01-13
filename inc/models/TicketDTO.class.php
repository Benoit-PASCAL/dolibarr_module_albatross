<?php

namespace Albatross;

class TicketDTO
{
	/**
	 * @var string
	 */
	private $subject;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var \DateTime
	 */
	private $creationDate;

	public function __construct()
	{
		$this->creationDate = new \DateTime();
	}

	public function getSubject(): string
	{
		return $this->subject;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject($subject): TicketDTO
	{
		$this->subject = $subject;
		return $this;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description): TicketDTO
	{
		$this->description = $description;
		return $this;
	}

	public function getCreationDate(): \DateTime
	{
		return $this->creationDate;
	}

	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate($creationDate): TicketDTO
	{
		$this->creationDate = $creationDate;
		return $this;
	}
}
