<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;
return array(
	'router' => array(
		'routes' => array(
			'home' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'system' => array(
						'type' => 'literal',
						'options' => array(
							'route' => 'system',
						),
						'may_terminate' => true,
						'child_routes' => array(
							'user' => array(
								'type' => 'segment',
								'options' => array(
									'route' => '/user[/[:action[/:userId]]]',
									'constraints' => array(
										'userId' => '\d+',
									),
									'defaults' => array(
										'controller' => 'Application\Controller\User',
										'action' => 'index',
									),
								),
								'may_terminate' => true,
							),
						),
					),
				),
			),
			'api' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/api',
					'defaults' => array(
						'controller' => 'Application\Controller\Api\Index',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
		        'child_routes' => array(
			        'users' => array(
				        'type' => 'segment',
				        'options' => array(
					        'route' => '/users[/:userId]',
					        'constraints' => array(
						        'userId' => '\d+',
					        ),
					        'defaults' => array(
						        'controller' => 'Application\Controller\Api\User',
					        ),
				        ),
				        'child_routes' => array(
					        'get' => array(
						        'type' => 'method',
						        'options' => array(
							        'verb' => 'get',
							        'defaults' => array(
								        'action' => 'index',
							        ),
						        ),
						        'may_terminate' => true,
					        ),
					        'post' => array(
						        'type' => 'method',
						        'options' => array(
							        'verb' => 'post',
							        'defaults' => array(
								        'action' => 'update',
							        ),
						        ),
						        'may_terminate' => true,
					        ),
				        ),
			        ),
			        'roles' => array(
				        'type' => 'literal',
				        'options' => array(
					        'route' => '/roles',
					        'defaults' => array(
						        'controller' => 'Application\Controller\Api\Role',
						        'action' => 'index',
					        ),
				        ),
			        ),
		        ),
			),
		),
	),
	'service_manager' => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'aliases' => array(
			'translator' => 'MvcTranslator',
		),
		'factories' => array(
			'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
		),
		'invokables' => array(
			// returns list of users
			'users' => 'Application\Service\Search\Base\DQL\User',
			// updates or creates user entity
			'user' => 'Application\Service\Update\User',

			// returns list of roles
			'roles' => 'Application\Service\Search\Base\Role',
		),
	),
	'translator' => array(
		'locale' => 'en_US',
		'translation_file_patterns' => array(
			array(
				'type'     => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.mo',
			),
		),
	),
	'controllers' => array(
		'invokables' => array(
			// web routes
			'Application\Controller\Index' => 'Application\Controller\IndexController',
			'Application\Controller\User' => 'Application\Controller\UserController',

			// api routes
			'Application\Controller\Api\Index' => 'Application\Controller\Api\IndexController',
			'Application\Controller\Api\User' => 'Application\Controller\Api\UserController',
			'Application\Controller\Api\Role' => 'Application\Controller\Api\RoleController',
		),
	),
	'view_helpers' => array(
		'factories' => array(
			'mainMenu' => function($sm) {
				/* @var $locator \Zend\ServiceManager\ServiceManager */
				$locator = $sm->getServiceLocator();
				$nav = $sm->get('Zend\View\Helper\Navigation')->menu('navigation');
				$nav->setUlClass('');
				$nav->escapeLabels(false);
				$nav->setMaxDepth(1);
				//$nav->setPartial('partials/primary-nav');
				$acl = $locator->get('FzyAuth\Acl');
				$role = $locator->get('FzyAuth\CurrentUser')->getRole();
				$nav->setAcl($acl);
				$nav->setRole($role);
				return $nav->setUlClass('nav')->setTranslatorTextDomain(__NAMESPACE__);
			},
		),
	),
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map' => array(
			'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
			'error/404'               => __DIR__ . '/../view/error/404.phtml',
			'error/index'             => __DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
	// Placeholder for console routes
	'console' => array(
		'router' => array(
			'routes' => array(
			),
		),
	),

	'zfcuser' => array(
		// telling ZfcUser to use our own class
		'user_entity_class'       => 'Application\Entity\User',
	),

	\FzyAuth\Service\Base::MODULE_CONFIG_KEY => array(
		'null_user_class' => 'Application\Entity\UserNull',
	),

	'doctrine' => array(
		'driver' => array(
			__NAMESPACE__ . '_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
			),
			'orm_default' => array(
				'drivers' => array(
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
				)
			)
		)
	),
);
