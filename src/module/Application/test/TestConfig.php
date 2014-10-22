<?php
return array(
    'modules' => array(
	    'DoctrineModule',
	    'DoctrineORMModule',
        'ZfcBase',
        'ZfcUser',
        'ZfcUserDoctrineORM',
        'BjyAuthorize',
        'Application',
    ),

    'module_listener_options' => array(
        'config_glob_paths' => array(
            '../../../config/autoload/{,*.}{global,local}.php',
            __DIR__ . '/testing.config.php',
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
    ),
);
