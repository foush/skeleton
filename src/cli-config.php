<?php
// Setup autoloading
require 'init_autoloader.php';

// Run the application!
$app = Zend\Mvc\Application::init(require 'config/application.config.php');

$em = $app->getServiceManager()->get('Doctrine\ORM\EntityManager');


$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

return $helperSet;