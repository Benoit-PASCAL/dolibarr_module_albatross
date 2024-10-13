<?php

// Prepare the environment
if (!defined('TEST_ENV_SETUP')) {
    require_once dirname(__FILE__) . '/_setup.php';
}

use PHPUnit\Framework\TestCase;

class MapperListingTest extends TestCase
{
    public function testMappersIsolation()
    {
        $mappersIndexPath = MODULE_ROOT . '/inc/mappers/index.php';
        $mappersFolderPath = dirname($mappersIndexPath);

        $this->assertFileExists($mappersIndexPath);

        require_once $mappersIndexPath;

        $mapperFileList = scandir($mappersFolderPath);
        $mapperList = [];
        foreach ($mapperFileList as $mapperFile) {
            if (preg_match('/\.class\.php/', $mapperFile)) {
                $mapper = preg_replace('/\.class\.php/', '', $mapperFile);
                $mapperList[] = $mapper;
            }
        }

        // We control that all mappers are in index.php
        // Errors will be automaticly thrown if isolation is not respected
        foreach ($mapperList as $mapper) {
            $this->assertThat(
                class_exists('Albatross\\' . $mapper),
                $this->isTrue(),
                'Class ' . $mapper . ' has been created but it is not in index.php or namespace is wrong'
            );
        }
    }

    public function testToolsIsolation()
    {
        $this->assertFileExists(MODULE_ROOT . '/inc/tools/dbManagerStub.php');
        $this->assertFileExists(MODULE_ROOT . '/inc/tools/doliDBManager.php');
        $this->assertFileExists(MODULE_ROOT . '/inc/tools/intDBManager.php');
        if (!defined('DOL_DOCUMENT_ROOT')) {
            define('DOL_DOCUMENT_ROOT', dirname(__DIR__, 4));
        }

        require_once MODULE_ROOT . '/inc/tools/intDBManager.php';

        $this->assertThat(
            interface_exists('Albatross\Tools\intDBManager'),
            $this->isTrue(),
            'Class intDBManager does not exist'
        );

        // This test will fail because multicompany module is not isolated
        // and business logic is not separated from the database layer
        require_once MODULE_ROOT . '/inc/tools/doliDBManager.php';

        $this->assertThat(
            class_exists('Albatross\Tools\DoliDBManager'),
            $this->isTrue(),
            'Class DoliDBManager does not exist'
        );

        require_once MODULE_ROOT . '/inc/tools/dbManagerStub.php';

        $this->assertThat(
            class_exists('Albatross\Tools\dbManagerStub'),
            $this->isTrue(),
            'Class dbManagerStub does not exist'
        );
    }
}
