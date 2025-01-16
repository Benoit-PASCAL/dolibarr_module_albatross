<?php

namespace Albatross\Tools;

require_once __DIR__ . '/EntityConfiguratorInterface.php';

class DolibarrDefaultEntityConfigurator implements EntityConfiguratorInterface
{
	/**
	 * Initialize needed Dolibarr Modules
	 * @param string[] $modules
	 */
	public function initModules($modules): int
	{
		try {
			dol_syslog(get_class($this) . '::initModules count:' . count($modules), LOG_INFO);
			global $conf, $db;
			foreach ($modules as $requiredModule) {
				$isModEnabled = (int) DOL_VERSION >= 16 ? isModEnabled($requiredModule) : $conf->$requiredModule->enabled;
				if (!$isModEnabled) {
					// We enable the module
					$modPath = $this->getModulePath($requiredModule);
					$modName = $this->getModuleClassName($requiredModule);
					dol_syslog('Initializing module :' . $modName, LOG_NOTICE);
					require_once $modPath;
					$module = new $modName($db);
					$module->init();
				} else {
					dol_syslog('Module ' . $modName . ' is already enabled', LOG_NOTICE);
				}
			}
		} catch (\Exception $e) {
			dol_syslog('Error: ' . $e->getMessage(), LOG_ERR);
			return 0;
		}

		return 1;
	}

	public function setSecurity(): void
	{
		dol_syslog(get_class($this) . '::setSecurity', LOG_INFO);
		global $db;

		$tokenLevel = '3';
		$tokenLevel = (int) DOL_VERSION < 17 ? '2' : $tokenLevel;
		$tokenLevel = (int) DOL_VERSION < 15 ? '0' : $tokenLevel;

		$constArray = array(
			'MAIN_SECURITY_CSRF_WITH_TOKEN' => $tokenLevel,
			'MAIN_RESTRICTHTML_ONLY_VALID_HTML' => '1',
			'MAIN_RESTRICTHTML_ONLY_VALID_HTML_TIDY' => '1',
			'MAIN_RESTRICTHTML_REMOVE_ALSO_BAD_ATTRIBUTES' => '1',
			'MAIN_DISALLOW_URL_INTO_DESCRIPTIONS' => '1'
		);

		foreach ($constArray as $key => $value) {
			if (!empty(dolibarr_get_const($db, $key))) {
				dolibarr_del_const($db, $key);
			}
			dolibarr_set_const($db, $key, $value, 'chaine', 1, '', 0);
		}
	}

	/**
	 * @param int $entityId
	 * @param mixed[] $params
	 */
	public function setupEntity($entityId = 0, $params = []): bool
	{
		// TODO: Implement setupEntity() method.
		return 1;
	}

	/**
	 * @param string $moduleName
	 */
	public function getModulePath($moduleName)
	{
		$lowercaseModule = strtolower($moduleName);
		$modFileName = 'mod' . ucfirst($moduleName);

		$modPath = DOL_DOCUMENT_ROOT . '/core/modules/' . $modFileName . '.class.php';
		if (file_exists($modPath)) {
			return $modPath;
		}

		$modPath = DOL_DOCUMENT_ROOT . '/custom/' . $lowercaseModule . '/core/modules/' . $modFileName . '.class.php';
		if (file_exists($modPath)) {
			return $modPath;
		}

		throw new \Exception('Module ' . $moduleName . ' not found');
	}

	/**
	 * @param string $moduleName
	 */
	public function getModuleClassName($moduleName)
	{
		return 'mod' . ucfirst($moduleName);
	}
}
