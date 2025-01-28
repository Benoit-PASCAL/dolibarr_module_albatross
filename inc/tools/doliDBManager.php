<?php

namespace Albatross\Tools;

require_once __DIR__ . '/intDBManager.php';
require_once DOL_DOCUMENT_ROOT . '/user/class/user.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/custom/albatross/inc/models/index.php';
require_once DOL_DOCUMENT_ROOT . '/custom/albatross/inc/mappers/index.php';
require_once DOL_DOCUMENT_ROOT . '/custom/multicompany/class/dao_multicompany.class.php';
require_once DOL_DOCUMENT_ROOT . '/custom/multicompany/class/actions_multicompany.class.php';

use ActionsMulticompany;
use Albatross\BankDTO;
use Albatross\BankDTOMapper;
use Albatross\EntityDTO;
use Albatross\EntityDTOMapper;
use Albatross\InvoiceStatus;
use Albatross\QuotationDTOMapper;
use Albatross\OrderDTO;
use Albatross\InvoiceDTO;
use Albatross\InvoiceDTOMapper;
use Albatross\OrderDTOMapper;
use Albatross\ProductDTO;
use Albatross\ProductDTOMapper;
use Albatross\ProjectDTO;
use Albatross\ProjectDTOMapper;
use Albatross\ServiceDTO;
use Albatross\TaskDTO;
use Albatross\TaskDTOMapper;
use Albatross\ThirdpartyDTO;
use Albatross\ThirdpartyDTOMapper;
use Albatross\TicketDTO;
use Albatross\TicketDTOMapper;
use Albatross\UserDTO;
use Albatross\UserDTOMapper;
use Albatross\UserGroupDTO;
use Albatross\UserGroupDTOMapper;
use Exception;
use modBanque;
use modCommande;
use modFacture;
use modProjet;
use modSociete;
use modTicket;
use User;

class DoliDBManager implements intDBManager
{
	/**
	 * @var int $currentEntityId
	 */
	private $currentEntityId;

	public function __construct()
	{
		$this->currentEntityId = 0;
	}

	/**
	 * @param \Albatross\UserDTO $userDTO
	 */
	public function createUser($userDTO): int
	{
		dol_syslog(get_class($this) . '::createUser lastname:' . $userDTO->getLastname(), LOG_INFO);
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
		dol_syslog(get_class($this) . '::createUserGroup label:' . $userGroupDTO->getLabel(), LOG_INFO);
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
	 * @param \Albatross\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createCustomer($thirdpartyDTO): int
	{
		dol_syslog(get_class($this) . '::createCustomer', LOG_INFO);

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
	 * @param \Albatross\ThirdpartyDTO $thirdpartyDTO
	 */
	public function createSupplier($thirdpartyDTO): int
	{
		dol_syslog(get_class($this) . '::createSupplier', LOG_INFO);

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
	 * @param \Albatross\InvoiceDTO $invoiceDTO
	 */
	public function createSupplierInvoice($invoiceDTO): int
	{
		dol_syslog(__METHOD__, LOG_INFO);
		global $db, $user;

		$invoiceDTOMapper = new InvoiceDTOMapper();
		$tmpSupplier = $invoiceDTOMapper->toSupplierInvoice($invoiceDTO);
		$res = $tmpSupplier->create($user);

		if ($res <= 0) {
			throw new Exception($res . $tmpSupplier->error);
		}

		return $tmpSupplier->id;
	}

	/**
	 * @param \Albatross\ProductDTO $productDTO
	 */
	public function createProduct($productDTO): int
	{
		dol_syslog(get_class($this) . 'createProduct', LOG_INFO);
		global $db, $user;

		$productDTOMapper = new ProductDTOMapper();
		$product = $productDTOMapper->toProduct($productDTO);
		$res = $product->create($user);

		return $product->id ?? 0;
	}

	/**
	 * @param \Albatross\ServiceDTO $serviceDTO
	 */
	public function createService($serviceDTO): int
	{
		dol_syslog(get_class($this) . 'createService', LOG_INFO);
		global $db, $user;

		$productDTOMapper = new ProductDTOMapper();
		$product = $productDTOMapper->toService($serviceDTO);
		$product->create($user);

		return $product->id ?? 0;
	}

	/**
	 * @param \Albatross\QuotationDTO $quotationDTO
	 */
	public function createSupplierQuotation($quotationDTO): int
	{
		dol_syslog(get_class($this) . 'createQuotation', LOG_INFO);
		global $db, $user;

		//$isModEnabled = (int) DOL_VERSION <= 14 ? isModEnabled('devis') : $conf->commande->enabled;
		$quotationDTOMapper = new QuotationDTOMapper();
		$quotation = $quotationDTOMapper->toQuotation($quotationDTO);
		$quotation->create($user);

		return $quotation->id ?? 0;
	}


	/**
	 * @param \Albatross\OrderDTO $orderDTO
	 */
	public function createOrder($orderDTO): int
	{
		dol_syslog(get_class($this) . 'createOrder', LOG_INFO);
		global $conf, $db, $user;
		$user->id = 1;

		$isModEnabled = (int) DOL_VERSION >= 16 ? isModEnabled('commande') : $conf->commande->enabled;
		if (!$isModEnabled) {
			// We enable the module
			require_once DOL_DOCUMENT_ROOT . '/core/modules/modCommande.class.php';
			$mod = new modCommande($db);
			$mod->init();
		}

		$orderDTOMapper = new OrderDTOMapper();
		$order = $orderDTOMapper->toOrder($orderDTO);
		$res = $order->create($user);

		if ($res <= 0) {
			throw new Exception($res . $order->error);
		}

		// TODO: Move to fixtures
		if (rand(0, 1)) {
			$order->valid($user);
		}
		return $order->id ?? 0;
	}

	/**
	 * @param \Albatross\InvoiceDTO $invoiceDTO
	 */
	public function createInvoice($invoiceDTO): int
	{
		dol_syslog(get_class($this) . 'createInvoice', LOG_INFO);
		global $conf, $db, $user;
		$user->id = 1;

		$isModEnabled = (int) DOL_VERSION >= 16 ? isModEnabled('facture') : $conf->facture->enabled;
		if (!$isModEnabled) {
			// We enable the module
			require_once DOL_DOCUMENT_ROOT . '/core/modules/modFacture.class.php';
			$mod = new modFacture($db);
			$mod->init();
		}

		$invoiceDTOMapper = new InvoiceDTOMapper();
		$invoice = $invoiceDTOMapper->toInvoice($invoiceDTO);
		$res = $invoice->create($user);

		if ($res <= 0) {
			$query = new Exception($db->lastqueryerror());
			$dbError = new Exception($db->lasterror(), 500, $query);
			throw new Exception($res . $invoice->error, 500, $dbError);
		}

		if ($invoiceDTO->getStatus() === InvoiceStatus::VALIDATED) {
			$invoice->validate($user);
		}

		// TODO: Move to fixtures
		return $invoice->id ?? 0;
	}

	/**
	 * @param \Albatross\TicketDTO $ticketDTO
	 */
	public function createTicket($ticketDTO): int
	{
		dol_syslog(get_class($this) . '::createTicket', LOG_INFO);
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
	 * @param \Albatross\BankDTO $bankDTO
	 */
	public function createBank($bankDTO): int
	{
		dol_syslog(__METHOD__, LOG_INFO);
		global $conf, $db, $user;

		$isModEnabled = (int)DOL_VERSION >= 16 ? isModEnabled('bank') : $conf->bank->enabled;
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
	 * @param \Albatross\ProjectDTO $projectDTO
	 */
	public function createProject($projectDTO): int
	{
		dol_syslog(__METHOD__, LOG_INFO);

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
	 * @param \Albatross\TaskDTO $taskDTO
	 */
	public function createTask($taskDTO): int
	{
		dol_syslog(__METHOD__, LOG_INFO);

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
	 * @param \Albatross\EntityDTO $entityDTO
	 * @param mixed[] $params
	 */
	public function createEntity($entityDTO, $params = []): int
	{
		dol_syslog(get_class($this) . '::createEntity entity:' . $entityDTO->getName(), LOG_INFO);
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
			return 0;
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
		dol_syslog(get_class($this) . '::configEntity entity:' . $entityDTO->getName(), LOG_INFO);
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
		dol_syslog(get_class($this) . '::setupEntity $entityId:' . $entityId, LOG_INFO);
		// TODO: Move to fixtures as it is a specific setup
		return true;
	}

	public function removeFixtures(): bool
	{
		dol_syslog(get_class($this) . '::removeFixtures', LOG_INFO);
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
			'product_price',
			'product',
			'socpeople',
			'societe_extrafields',
			'societe_commerciaux',
			'societe_contacts',
			'societe_prices',
			'societe',
			'ticket',
			'projet_task_extrafields',
			'projet_task',
			'projet_extrafields',
			'projet'
		];

		foreach ($toDrop as $table) {
			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . $table;
			$resql = $db->query($sql);
			if (!$resql) {
				dol_syslog(get_class($this) . '::removeFixtures ' . $db->lasterror(), LOG_ERR);
				dol_print_error($db);
				return -1;
			}
		}

		$sql = $sql = 'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = "' . MAIN_DB_PREFIX . 'entity_extrafields"';
		$resql = $db->query($sql);
		if ($resql && $db->num_rows($resql) > 0) {
			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . 'entity_extrafields';
			$sql .= ' WHERE fk_object > 1';
			$resql = $db->query($sql);
			if (!$resql) {
				dol_syslog(get_class($this) . '::removeFixtures ' . $db->lasterror(), LOG_ERR);
				dol_print_error($db);
				return -1;
			}

			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . 'entity';
			$sql .= ' WHERE rowid > 1';
			$resql = $db->query($sql);
			if (!$resql) {
				dol_syslog(get_class($this) . '::removeFixtures ' . $db->lasterror(), LOG_ERR);
				dol_print_error($db);
				return -1;
			}
		}

		return 1;
	}
}
