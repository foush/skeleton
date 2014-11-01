<?php

namespace Application\Service;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Session\SessionHandler;
use FzyCommon\Util\Params;
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
        $this->addCustomSaveHandler($this->getConfig(), $sessionManager);
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
    public function addCustomSaveHandler(Params $config, SessionManager $sessionManager)
    {
        $sessionHandlerConfig = $config->getWrapped('aws_zf2')->getWrapped('session')->getWrapped('save_handler')->get('dynamodb', null);
        if ($sessionHandlerConfig) {
            $this->createSessionTable($sessionHandlerConfig);
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
        return $this->getConfig()->get('session', array());
    }
}
