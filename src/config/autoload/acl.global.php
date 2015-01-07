<?php
use FzyAuth\Util\Acl\Resource as AclResource;
use FzyAuth\Entity\Base\UserInterface;

return array(
    \FzyAuth\Service\Base::MODULE_CONFIG_KEY => array(
        'acl' => array(
	        'roles' => array(
				UserInterface::ROLE_GUEST => array(
					'allow' => array(
						// web routes
						array(
							AclResource::KEY_ROUTE => 'home',
						),
						array(
							AclResource::KEY_CONTROLLER => \ZfcUser\Controller\UserController::CONTROLLER_NAME,
							AclResource::KEY_ACTIONS => array(
								'login', 'register', 'authenticate'
							),
						),
						// api routes
					),
					'deny' => array(

					),
				),
				UserInterface::ROLE_USER => array(
					'inherits' => array(UserInterface::ROLE_GUEST),
					'allow' => array(
						// web routes
						array(
							AclResource::KEY_CONTROLLER => \ZfcUser\Controller\UserController::CONTROLLER_NAME,
							AclResource::KEY_ACTIONS => array(
								'logout', 'index', 'changepassword', 'changeemail',
							),
						),
						// API routes
					),
					'deny' => array(
						array(
							AclResource::KEY_CONTROLLER => \ZfcUser\Controller\UserController::CONTROLLER_NAME,
							AclResource::KEY_ACTIONS => array(
								'authenticate'
							),
						),
					),
				),
	        ),
        ),
    ),
);