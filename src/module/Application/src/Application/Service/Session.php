<?php

namespace Application\Service;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Session\SessionHandler;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;

/**
 * Class Session
 * @package Application\Service
 * session_setup
 */
class Session extends Base
{
    /**
     * Standard ZF2 session manager setup.
     * Makes a call to 'addCustomSaveHandler' to allow
     * for a dynamodb save handler instead of native php session
     */
    public function init()
    {
        $config = $this->getSessionConfig();
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $this->addCustomSaveHandler($this->getServiceLocator()->get('Configuration'), $sessionManager);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }

    /**
     * Searches the application's configuration for the expected
     * config pattern. If present, attempts to add session save
     * handler for dynamodb at the configured table. If the table
     * does not exist, it is created.
     * @param array          $config
     * @param SessionManager $sessionManager
     *
     * @return $this
     */
    public function addCustomSaveHandler(array $config, SessionManager $sessionManager)
    {
        $passed = true;
        $array = $config;
        // search down this config path
        foreach (array('aws_zf2', 'session', 'save_handler', 'dynamodb') as $key) {
            if (!isset($array[$key])) {
                $passed = false;
                break;
            }
            $array = $array[$key];
        }
        if ($passed) {
            $this->createSessionTable($array);
            /* @var $saveHandler \Aws\Session\SaveHandler\DynamoDb */
            $saveHandler = $this->getServiceLocator()->get('Aws\Session\SaveHandler\DynamoDb');
            $sessionManager->setSaveHandler($saveHandler);
        }

        return $this;
    }

    /**
     * Creates dynamodb table if it does not already exist
     * @param array $config
     *
     * @return $this
     */
    public function createSessionTable(array $config)
    {
        $aws    = $this->getServiceLocator()->get('aws');
        /* @var $client DynamoDbClient */
        $client = $aws->get('dynamodb');
        $table = $config['table_name'];

        /* @var $tables \Guzzle\Service\Resource\Model */
        $tables = $client->listTables();
        if (!in_array($table, $tables->get('TableNames'))) {
            $sessionHandler = SessionHandler::factory(array(
                'dynamodb_client' => $client,
                'table_name' => $table,
            ));
            $sessionHandler->createSessionsTable(5,5);
        }

        return $this;
    }

    /**
     * Convenience, returns the session configuration array
     * @return array
     */
    public function getSessionConfig()
    {
        $config = $this->getServiceLocator()->get('Configuration');

        return isset($config['session']) ? $config['session'] : array();
    }
}
