<?php

namespace Albatross;

include_once dirname(__DIR__) . '/models/TicketDTO.class.php';
require_once dirname(__DIR__, 4) . '/ticket/class/ticket.class.php';


use Albatross\models\TicketDTO;

class TicketDTOMapper
{
	/**
	 * @param \Albatross\models\TicketDTO $ticketDTO
	 */
	public function toTicket($ticketDTO): \Ticket
	{
		global $db;

		$ticket = new \Ticket($db);

		$ticket->ref = uniqid();
		$ticket->subject = $ticketDTO->getSubject();
		$ticket->description = $ticketDTO->getDescription();

		return $ticket;
	}
	/**
	 * @param \Ticket $ticket
	 */
	public function toTicketDTO($ticket): TicketDTO
	{
		$requiredFields = ['subject', 'description'];
		foreach ($requiredFields as $field) {
			if (!isset($ticket->$field)) {
				throw new \Exception("Missing required field: $field");
			}
		}

		$ticketDTO = new TicketDTO();
		$ticketDTO
			->setSubject($ticket->subject)
			->setDescription($ticket->description)
			->setCreationDate((new \DateTime())->setTimestamp($ticket->datec));

		return $ticketDTO;
	}
}
