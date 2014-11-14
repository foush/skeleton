<?php

return array(
	'navigation' => array(
		'default' => array(
			'dashboard' => array(
				'label' => '<img class="svg" src="/dist/img/icons/i_dashboard.svg" /> Dashboard',
				'route' => 'home',
			),

			'contacts' => array(
				'label' => '<img class="svg" src="/dist/img/icons/i_settings.svg" /> Users',
				'route' => 'home/system/user',
				'resource' => \FzyAuth\Service\AclEnforcerInterface::RESOURCE_ROUTE_PREFIX . 'home/system/user',
				'privilege' => 'index',
			),
		),
	),
);
