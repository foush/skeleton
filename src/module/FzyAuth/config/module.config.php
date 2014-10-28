<?php
return array(
	'service_manager' => array(
		'invokables' => array(
			'FzyAuth\Listener\Route' => 'Application\Listener\Route',
		),
	),
	\FzyAuth\Service\Base::MODULE_CONFIG_KEY => array(
		// whether to display exception traces
		'debug' => true,
		// whether to intercept api errors
		'intercept_api_errors' => true,
	),
);