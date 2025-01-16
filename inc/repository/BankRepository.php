<?php

namespace Albatross\repository;

use DoliDB;
use Exception;


class BankRepository
{
	private DoliDB $db;
	//global $db; ??

	/**
	 * @param DoliDB $db Instance de la base de données.
	 */
	public function __construct(DoliDB $db)
	{
		$this->db = $db;
	}

	/**
	 * Récupérer une banque par son ID.
	 *
	 * @param int $id ID de la banque.
	 * @return object|null Retourne un objet représentant la banque ou null si non trouvé.
	 * @throws Exception Si une erreur SQL survient.
	 */
	public function find(int $id): ?object
	{
		$sql = "SELECT rowid, name, rib, address, zip, city
                FROM ".MAIN_DB_PREFIX."bank_account
                WHERE rowid = ".(int)$id;

		$resql = $this->db->query($sql);

		if (!$resql) {
			throw new Exception("Erreur SQL : " . $this->db->lasterror());
		}

		if ($this->db->num_rows($resql) > 0) {
			return $this->db->fetch_object($resql);
		}

		return null;
	}

	/**
	 * Ajouter une nouvelle banque.
	 *
	 * @param array $data Données de la banque (name, rib, address, zip, city).
	 * @return int ID de la banque créée.
	 * @throws Exception Si une erreur SQL survient.
	 */
	public function create(array $data): int
	{
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."bank_account (name, rib, address, zip, city)
                VALUES (
                    '".$this->db->escape($data['name'])."',
                    '".$this->db->escape($data['rib'])."',
                    '".$this->db->escape($data['address'])."',
                    '".$this->db->escape($data['zip'])."',
                    '".$this->db->escape($data['city'])."'
                )";

		if (!$this->db->query($sql)) {
			throw new Exception("Erreur lors de la création de la banque : " . $this->db->lasterror());
		}

		return (int)$this->db->last_insert_id(MAIN_DB_PREFIX."bank_account");
	}

	/**
	 * Mettre à jour une banque existante.
	 *
	 * @param int $id ID de la banque.
	 * @param array $data Données mises à jour.
	 * @return bool True si la mise à jour réussit.
	 * @throws Exception Si une erreur SQL survient.
	 */
	public function update(int $id, array $data): bool
	{
		$sql = "UPDATE ".MAIN_DB_PREFIX."bank_account
                SET name = '".$this->db->escape($data['name'])."',
                    rib = '".$this->db->escape($data['rib'])."',
                    address = '".$this->db->escape($data['address'])."',
                    zip = '".$this->db->escape($data['zip'])."',
                    city = '".$this->db->escape($data['city'])."'
                WHERE rowid = ".(int)$id;

		if (!$this->db->query($sql)) {
			throw new Exception("Erreur lors de la mise à jour : " . $this->db->lasterror());
		}

		return true;
	}

	/**
	 * Supprimer une banque.
	 *
	 * @param int $id ID de la banque à supprimer.
	 * @return bool True si la suppression réussit.
	 * @throws Exception Si une erreur SQL survient.
	 */
	public function delete(int $id): bool
	{
		$sql = "DELETE FROM ".MAIN_DB_PREFIX."bank_account WHERE rowid = ".(int)$id;

		if (!$this->db->query($sql)) {
			throw new Exception("Erreur lors de la suppression : " . $this->db->lasterror());
		}

		return true;
	}
}

