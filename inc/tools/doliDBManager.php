<?php

namespace Albatross\Tools;

require_once __DIR__ . '/intDBManager.php';
require_once __DIR__ . '/LogManager.php';
require_once __DIR__ . '/DoliLogManager.php';
require_once __DIR__ . '/DebugLogManager.php';
require_once DOL_DOCUMENT_ROOT . '/user/class/user.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/custom/albatross/inc/models/index.php';
require_once DOL_DOCUMENT_ROOT . '/custom/albatross/inc/mappers/index.php';
require_once DOL_DOCUMENT_ROOT . '/custom/multicompany/class/dao_multicompany.class.php';
require_once DOL_DOCUMENT_ROOT . '/custom/multicompany/class/actions_multicompany.class.php';


use ActionsMulticompany;
use Albatross\BankDTOMapper;
use Albatross\EntityDTO;
use Albatross\EntityDTOMapper;
use Albatross\InvoiceDTOMapper;
use Albatross\InvoiceStatus;
use Albatross\QuotationDTO;
use Albatross\OrderDTO;
use Albatross\OrderDTOMapper;
use Albatross\ProductDTOMapper;
use Albatross\ProjectDTOMapper;
use Albatross\QuotationDTOMapper;
use Albatross\TaskDTOMapper;
use Albatross\ThirdpartyDTOMapper;
use Albatross\TicketDTOMapper;
use Albatross\UserDTOMapper;
use Albatross\UserGroupDTOMapper;
use Exception;
use modBanque;
use modCommande;
use modPropale;
use modFacture;
use modProjet;
use modTicket;
use User;

class DoliDBManager implements intDBManager
{
	/**
	 * @var int $currentEntityId
	 */
	private $currentEntityId;

	/**
	 * @var LogManager $log
	 */
	private $log;

	/**
	 * @param ?LogManager $log
	 */
	public function __construct($log = null)
	{
		global $dolibarr_main_prod;
		switch ($dolibarr_main_prod) {
			case 1:
				$defaultLog = new DoliLogManager();
				break;
			case 0:
				$defaultLog = new DebugLogManager();
				break;
		}

		$this->currentEntityId = 0;
		$this->log = $log ?? $defaultLog;
	}

	/**
	 * @param \Albatross\UserDTO $userDTO
	 */
	public function createUser($userDTO): int
	{
		$this->log::log(__METHOD__ . ' lastname:' . $userDTO->getLastname(), LOG_INFO);
		global $db, $user;

		$userDTOMapper = new UserDTOMapper();
		$tmpuser = $userDTOMapper->toUser($userDTO);
		$res = $tmpuser->create($user);

		foreach ($tmpuser->user_group_list as $groupId) {
			$tmpuser->SetInGroup($groupId, $userDTO->getEntity());
		}

		return $res;
	}

	/**
	 * @param \Albatross\UserGroupDTO $userGroupDTO
	 */
	public function createUserGroup($userGroupDTO): int
	{
		$this->log::log(__METHOD__ . ' label:' . $userGroupDTO->getLabel(), LOG_INFO);
		global $user;

		$userGroupDTOMapper = new UserGroupDTOMapper();
		$tmpUserGroup = $userGroupDTOMapper->toUserGroup($userGroupDTO);
		$res = $tmpUserGroup->create($user);

		if ($res <= 0) {
			throw new Exception($res . $tmpUserGroup->errors[0]);
		}

		foreach ($userGroupDTO->getEntities() as $entity) {
			$tmpUserGroup->addrights('', 'allmodules', '', $entity);
		}

		return $res;
	}

	/**
	 * @param ThirdpartyDTO $thirdpartyDTO
	 */
	public function createCustomer($thirdpartyDTO): int
	{
		$this->log::log(__METHOD__);

		global $db, $user;

		$thirdpartyDTOMapper = new ThirdpartyDTOMapper();
		$tmpCustomer = $thirdpartyDTOMapper->toCustomer($thirdpartyDTO);
		$res = $tmpCustomer->create($user);

		if ($res <= 0) {
			throw new Exception($res . $tmpCustomer->error);
		}

		return $tmpCustomer->id;
	}

	/**
	 * @param ThirdpartyDTO $thirdpartyDTO
	 */
	public function createSupplier($thirdpartyDTO): int
	{
		$this->log::log(__METHOD__);

		global $db, $user;

		$thirdpartyDTOMapper = new ThirdpartyDTOMapper();
		$tmpSupplier = $thirdpartyDTOMapper->toSupplier($thirdpartyDTO);
		$res = $tmpSupplier->create($user);

		if ($res <= 0) {
			throw new Exception($res . $tmpSupplier->error);
		}

		return $tmpSupplier->id;
	}


	/**
	 * @param ProductDTO $productDTO
	 */
	public function createProduct($productDTO): int
	{
		$this->log::log(__METHOD__);
		global $db, $user;

		$productDTOMapper = new ProductDTOMapper();
		$product = $productDTOMapper->toProduct($productDTO);
		$res = $product->create($user);

		return $product->id ?? 0;
	}

	/**
	 * @param ServiceDTO $serviceDTO
	 */
	public function createService($serviceDTO): int
	{
		$this->log::log(__METHOD__);
		global $db, $user;

		$productDTOMapper = new ProductDTOMapper();
		$product = $productDTOMapper->toService($serviceDTO);
		$product->create($user);

		return $product->id ?? 0;
	}

	/**
	 * @param QuotationDTO $quotationDTO
	 */
	public function createQuotation($quotationDTO): int
	{
		$this->log::log(__METHOD__);
		global $conf, $db, $user;

		$this->enableModule('modPropale');

		$quotationDTOMapper = new QuotationDTOMapper();
		$quotation = $quotationDTOMapper->toQuotation($quotationDTO);

		$quotation->statut = $quotation->status = 0;
		$res = $quotation->create($user);

		$this->controlException($res, $db, $quotation);

		if ($quotationDTO->getStatus() == InvoiceStatus::VALIDATED) {
			$this->log::log('Validating document');
			$quotation->statut = $quotation->status = 0;
			$res = $quotation->valid($user);
			$this->controlException($res, $db, $quotation);
		}

		return $quotation->id;
	}

	/**
	 * @param QuotationDTO $quotationDTO
	 */
	public function createSupplierQuotation($quotationDTO): int
	{
		$this->log::log(__METHOD__);
		global $conf, $db, $user;

		$this->enableModule('modFournisseur');
		$quotationDTOMapper = new QuotationDTOMapper();
		$quotation = $quotationDTOMapper->toSupplierQuotation($quotationDTO);
		$res = $quotation->create($user);

		$this->controlException($res, $db, $quotation);

		if ($quotationDTO->getStatus() == InvoiceStatus::VALIDATED) {
			$this->log::log('Validating document');
			$quotation->statut = $quotation->status = 0;
			$res = $quotation->valid($user);
			$this->controlException($res, $db, $quotation);
		}

		return $quotation->id;
	}


	/**
	 * @param OrderDTO $orderDTO
	 */
	public function createOrder($orderDTO): int
	{
		$this->log::log(__METHOD__);
		global $conf, $db, $user;
		$user->id = 1;

		$this->enableModule('modCommande');

		$orderDTOMapper = new OrderDTOMapper();
		$order = $orderDTOMapper->toOrder($orderDTO);
		$res = $order->create($user);

		if ($res <= 0) {
			throw new Exception($res . $order->error);
		}
		$this->controlException($res, $db, $order);

		if ($orderDTO->getStatus() == InvoiceStatus::VALIDATED) {
			$this->log::log('Validating document');
			$order->statut = $order->status = 0;
			$res = $order->valid($user);
			$this->controlException($res, $db, $order);
		}

		return $order->id;
	}

	/**
	 * @param OrderDTO $orderDTO
	 */
	public function createSupplierOrder($orderDTO): int
	{
		$this->log::log(__METHOD__);
		global $conf, $db, $user;

		$this->enableModule('modFournisseur');
		$orderDTOMapper = new OrderDTOMapper();
		$order = $orderDTOMapper->toSupplierOrder($orderDTO);
		$res = $order->create($user);

		$this->controlException($res, $db, $order);

		if ($orderDTO->getStatus() == InvoiceStatus::VALIDATED) {
			$this->log::log('Validating document');
			$order->statut = $order->status = 0;
			$res = $order->valid($user);
			$this->controlException($res, $db, $order);
		}

		return $order->id;
	}

	/**
	 * @param InvoiceDTO $invoiceDTO
	 */
	public function createInvoice($invoiceDTO): int
	{
		$this->log::log(__METHOD__);
		global $conf, $db, $user;
		$user->id = 1;

		$this->enableModule('modFacture');

		$invoiceDTOMapper = new InvoiceDTOMapper();
		$invoice = $invoiceDTOMapper->toInvoice($invoiceDTO);
		$res = $invoice->create($user);

		$this->controlException($res, $db, $invoice);

		if ($invoiceDTO->getStatus() == InvoiceStatus::VALIDATED) {
			$this->log::log('Validating document');
			$invoice->statut = 0;
			$res = $invoice->validate($user);
			$this->controlException($res, $db, $invoice);
		}

		// TODO: Move to fixtures
		return $invoice->id ?? 0;
	}

	/**
	 * @param InvoiceDTO $invoiceDTO
	 * @throws Exception
	 */
	public function createSupplierInvoice($invoiceDTO): int
	{
		$this->log::log(__METHOD__);
		global $conf, $db, $user;

		$this->enableModule('modFournisseur');
		$invoiceDTOMapper = new InvoiceDTOMapper();
		$invoice = $invoiceDTOMapper->toSupplierInvoice($invoiceDTO);
		$res = $invoice->create($user);

		$this->controlException($res, $db, $invoice);

		if ($invoiceDTO->getStatus() == InvoiceStatus::VALIDATED) {
			$this->log::log('Validating document');
			$invoice->statut = 0;
			$res = $invoice->validate($user);
			$this->controlException($res, $db, $invoice);
		}

		return $invoice->id;
	}


	/**
	 * @param TicketDTO $ticketDTO
	 */
	public function createTicket($ticketDTO): int
	{
		$this->log::log(__METHOD__);
		global $conf, $db, $user;

		$isModEnabled = (int) DOL_VERSION >= 16 ? isModEnabled('ticket') : $conf->ticket->enabled;
		if (!$isModEnabled) {
			// We enable the module
			require_once DOL_DOCUMENT_ROOT . '/core/modules/modTicket.class.php';
			$mod = new modTicket($db);
			$mod->init();
		}

		$ticketDTOMapper = new TicketDTOMapper();
		$ticket = $ticketDTOMapper->toTicket($ticketDTO);
		$res = $ticket->create($user);

		return $ticket->id ?? $res;
	}

	/**
	 * @param BankDTO $bankDTO
	 */
	public function createBank($bankDTO): int
	{
		$this->log::log(__METHOD__);
		global $conf, $db, $user;

		$isModEnabled = (int) DOL_VERSION >= 16 ? isModEnabled('bank') : $conf->bank->enabled;
		if (!$isModEnabled) {
			// We enable the module
			require_once DOL_DOCUMENT_ROOT . '/core/modules/modBanque.class.php';
			$mod = new modBanque($db);
			$mod->init();
		}

		$bankDTOMapper = new BankDTOMapper();
		$bank = $bankDTOMapper->toBank($bankDTO);
		$res = $bank->create($user);

		if ($res <= 0) {
			throw new Exception($res . $bank->error);
		}

		return $bank->id;
	}

	public function createBankAccount($bankAccountDTO): int
	{
		//modSociete.class.php
		return 0;
	}

	public function createPayment($paymentDTO): int
	{
		return 0;
	}

	/**
	 * @param ProjectDTO $projectDTO
	 */
	public function createProject($projectDTO): int
	{
		$this->log::log(__METHOD__);

		global $conf, $db, $user;

		$isModEnabled = (int) DOL_VERSION >= 16 ? isModEnabled('projet') : $conf->projet->enabled;
		if (!$isModEnabled) {
			// We enable the module
			require_once DOL_DOCUMENT_ROOT . '/core/modules/modProjet.class.php';
			$mod = new modProjet($db);
			$mod->init();
		}

		$projectDTOMapper = new ProjectDTOMapper();
		$project = $projectDTOMapper->toProjectWithTasks($projectDTO);
		$res = $project->create($user);

		if ($res <= 0) {
			throw new Exception($res . $project->error);
		}

		if (rand(0, 1)) {
			$project->setValid($user);
		}

		return $ticket->id ?? $res;
	}

	/**
	 * @param TaskDTO $taskDTO
	 */
	public function createTask($taskDTO): int
	{
		$this->log::log(__METHOD__);

		global $conf, $db, $user;

		$isModEnabled = (int) DOL_VERSION >= 16 ? isModEnabled('projet') : $conf->projet->enabled;
		if (!$isModEnabled) {
			// We enable the module
			require_once DOL_DOCUMENT_ROOT . '/core/modules/modProjet.class.php';
			$mod = new modProjet($db);
			$mod->init();
		}

		$taskDTOMapper = new TaskDTOMapper();
		$task = $taskDTOMapper->toTask($taskDTO);

		$res = $task->create($user);

		if ($res <= 0) {
			throw new Exception($res . $task->error);
		}

		return $task->id;
	}

	/**
	 * @param EntityDTO $entityDTO
	 * @param mixed[] $params
	 */
	public function createEntity($entityDTO, $params = []): int
	{
		$this->log::log(__METHOD__ . ' entity:' . $entityDTO->getName(), LOG_INFO);
		global $db, $user;

		$entityDTOMapper = new EntityDTOMapper();

		if ($params['isModel'] ?? false) {
			$entity = $entityDTOMapper->toModel($entityDTO);
			$entity->template = 1;
			$_POST['template'] = 1;
		} else {
			$entity = $entityDTOMapper->toEntity($entityDTO);
		}

		// We create entity
		$actionsMulticompany = new ActionsMulticompany($db);
		$action = 'add';
		$id = $actionsMulticompany->doAdminActions($action);
		$action = 'view';

		if (is_null($id) || $id <= 1) {
			throw new Exception('Entity ' . $entityDTO->getName() . ' not created');
		}

		if ($entityDTO->isEndPatternsWithId()) {
			$entityDTO->setInvoicePattern($entityDTO->getReplacementInvoicePattern() . $id);
			$entityDTO->setReplacementInvoicePattern($entityDTO->getReplacementInvoicePattern() . $id);
			$entityDTO->setCreditNotePattern($entityDTO->getCreditNotePattern() . $id);
			$entityDTO->setDownPaymentInvoicePattern($entityDTO->getDownPaymentInvoicePattern() . $id);
			$entityDTO->setPropalPattern($entityDTO->getPropalPattern() . $id);
			$entityDTO->setCustomerCodePattern($entityDTO->getCustomerCodePattern() . $id);
			$entityDTO->setSupplierCodePattern($entityDTO->getSupplierCodePattern() . $id);
		}
		$this->configEntity($id, $entityDTO);
		return $id;
	}

	private function configEntity(int $entityId, EntityDTO $entityDTO)
	{
		$this->log::log(__METHOD__ . ' entity:' . $entityDTO->getName());
		global $db;

		if ($entityDTO->getInvoicePattern() !== null) {
			dolibarr_set_const($db, 'FACTURE_ADDON', 'mod_facture_mercure', 'chaine', 0, '', $entityId);
			dolibarr_set_const(
				$db,
				'FACTURE_MERCURE_MASK_INVOICE',
				$entityDTO->getInvoicePattern(),
				'chaine',
				0,
				'',
				$entityId
			);
		}
		if ($entityDTO->getReplacementInvoicePattern() !== null) {
			dolibarr_set_const($db, 'FACTURE_ADDON', 'mod_facture_mercure', 'chaine', 0, '', $entityId);
			dolibarr_set_const(
				$db,
				'FACTURE_MERCURE_MASK_REPLACEMENT',
				$entityDTO->getReplacementInvoicePattern(),
				'chaine',
				0,
				'',
				$entityId
			);
		}
		if ($entityDTO->getCreditNotePattern() !== null) {
			dolibarr_set_const($db, 'FACTURE_ADDON', 'mod_facture_mercure', 'chaine', 0, '', $entityId);
			dolibarr_set_const(
				$db,
				'FACTURE_MERCURE_MASK_CREDIT',
				$entityDTO->getCreditNotePattern(),
				'chaine',
				0,
				'',
				$entityId
			);
		}
		if ($entityDTO->getDownPaymentInvoicePattern() !== null) {
			dolibarr_set_const($db, 'FACTURE_ADDON', 'mod_facture_mercure', 'chaine', 0, '', $entityId);
			dolibarr_set_const(
				$db,
				'FACTURE_MERCURE_MASK_DEPOSIT',
				$entityDTO->getDownPaymentInvoicePattern(),
				'chaine',
				0,
				'',
				$entityId
			);
		}

		if ($entityDTO->getPropalPattern() !== null) {
			dolibarr_set_const($db, 'PROPALE_ADDON', 'mod_propale_saphir', 'chaine', 0, '', $entityId);
			dolibarr_set_const($db, 'PROPALE_SAPHIR_MASK', $entityDTO->getPropalPattern(), 'chaine', 0, '', $entityId);
		}

		if ($entityDTO->getCustomerCodePattern() !== null) {
			dolibarr_set_const($db, 'SOCIETE_CODECLIENT_ADDON', 'mod_codeclient_elephant', 'chaine', 0, '', $entityId);
			dolibarr_set_const(
				$db,
				'COMPANY_ELEPHANT_MASK_CUSTOMER',
				$entityDTO->getCustomerCodePattern(),
				'chaine',
				0,
				'',
				$entityId
			);
		}
		if ($entityDTO->getSupplierCodePattern() !== null) {
			dolibarr_set_const($db, 'SOCIETE_CODECLIENT_ADDON', 'mod_codeclient_elephant', 'chaine', 0, '', $entityId);
			dolibarr_set_const(
				$db,
				'COMPANY_ELEPHANT_MASK_SUPPLIER',
				$entityDTO->getSupplierCodePattern(),
				'chaine',
				0,
				'',
				$entityId
			);
		}
	}

	/**
	 * @param int $entityId
	 * @param mixed[] $params
	 */
	public function setupEntity($entityId = 0, $params = []): bool
	{
		$this->log::log(__METHOD__ . ' $entityId:' . $entityId);
		// TODO: Move to fixtures as it is a specific setup
		return true;
	}

	public function removeFixtures(): bool
	{
		$this->log::log(__METHOD__);
		global $db, $user;

		$tmpUser = new User($db);
		$tmpUser->fetchAll();

		$tmpUser->users = array_filter($tmpUser->users, function ($user) {
			return $user->login !== 'administrator';
		});

		foreach ($tmpUser->users as $inLoopUser) {
			$userId = $inLoopUser->id;
			$toDeleteUser = new User($db);
			$toDeleteUser->fetch($userId);

			$toDeleteUser->delete($user);
		}

		$toDrop = [
			'scrumproject_scrumsprintuser',
			'scrumproject_scrumsprint',
			'usergroup_user',
			'usergroup_rights',
			'usergroup',
			'paiement_facture',
			'facture_fourn_det',
			'facture_fourn',
			'facturedet',
			'facture',
			'commandedet',
			'commande',
			'propaldet',
			'propal',
			'categorie_product',
			'product_fournisseur_price',
			'product_price',
			'product_stock',
			'product',
			'commande_fournisseur',
			'socpeople',
			'expeditiondet',
			'expedition',
			'categorie_societe',
			'societe_extrafields',
			'societe_commerciaux',
			'societe_contacts',
			'societe_prices',
			'societe',
			'ticket',
			'projet_task_extrafields',
			'projet_task',
			'projet_extrafields',
			'projet',
			'bank',
		];

		foreach ($toDrop as $table) {
			$tableName = MAIN_DB_PREFIX . $table;
			$sqlCheck = 'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = "' . $tableName . '"';
			$resqlCheck = $db->query($sqlCheck);
			if ($resqlCheck && $db->num_rows($resqlCheck) > 0) {
				$sqlDelete = 'DELETE FROM ' . $tableName;
				$resqlDelete = $db->query($sqlDelete);
				if (!$resqlDelete) {
					$this->log::error(__METHOD__ . ' ' . $db->lasterror());
					dol_print_error($db);
					return -1;
				}
			}
		}

		$sql = $sql = 'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = "' . MAIN_DB_PREFIX . 'entity_extrafields"';
		$resql = $db->query($sql);
		if ($resql && $db->num_rows($resql) > 0) {
			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . 'entity_extrafields';
			$sql .= ' WHERE fk_object > 1';
			$resql = $db->query($sql);
			if (!$resql) {
				$this->log::error(__METHOD__ . ' ' . $db->lasterror());
				dol_print_error($db);
				return -1;
			}

			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . 'entity';
			$sql .= ' WHERE rowid > 1';
			$resql = $db->query($sql);
			if (!$resql) {
				$this->log::error(__METHOD__ . ' ' . $db->lasterror());
				dol_print_error($db);
				return -1;
			}
		}

		return 1;
	}

	private function enableModule($moduleClassname): void
	{
		global $conf, $db;
		// remove 'mod' and set only lower case from $moduleClassname
		$moduleName = strtolower(preg_replace('/^mod/', '', $moduleClassname));

		$isModEnabled = (int) DOL_VERSION >= 16 ? isModEnabled($moduleName) : $conf->$moduleName->enabled;
		if (!$isModEnabled) {
			// We enable the module
			require_once DOL_DOCUMENT_ROOT . '/core/modules/' . $moduleClassname . '.class.php';
			$mod = new $moduleClassname($db);
			$mod->init();
		}
	}

	/**
	 * @param int $res
	 * @param ?\DoliDB $db
	 * @param ?\CommonObject $invoice
	 * @return void
	 * @throws Exception
	 */
	public function controlException($res, $db, $obj): void
	{
		if ($res <= 0) {
			$errorMessage = $obj->error ?? $obj->errors[0] ?? 'Uncatched error';
			$query = new Exception($db->lastqueryerror());
			$dbError = new Exception($db->lasterror(), 500, $query);
			throw new Exception($res . $errorMessage, 500, $dbError);
		}
	}
}







