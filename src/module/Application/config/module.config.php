<?php
namespace Application;
use FzyCommon\Util\Params;

return array(
    'router' => array(
        'routes' => array(
            'api' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/api/v1',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Api\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'report' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/user',
                        ),
                        'child_routes' => array(
                            'short_report_api' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/user',
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
                                        'child_routes' => array(
                                            'operation' => array(
                                                'type' => 'segment',
                                                'options' => array(
                                                    'route' => '[/:user[/:action]]',
                                                ),
                                            ),
                                        ),
                                    ),
                                    'post' => array(
                                        'type' => 'method',
                                        'options' => array(
                                            'verb' => 'post',
                                            'defaults' => array(
                                                'action' => 'update',
                                            ),
                                        ),
                                        'child_routes' => array(
                                            'operation' => array(
                                                'type' => 'segment',
                                                'options' => array(
                                                    'route' => '[/:user[/:action]]',
                                                ),
                                            ),
                                        ),

                                    ),

                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
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
            'em' => 'Doctrine\ORM\EntityManager',
        ),
        'factories' => array(

            'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',

            'email_renderer' => function($sm) {
                $rendererFactory = $sm->get('email_renderer_factory');
                return $rendererFactory->createService($sm);
            },
            'aws_config' => function(\Zend\ServiceManager\ServiceManager $sm) {
                    $config = $sm->get('config');
                    if (!isset($config['aws'])) {
                        throw new \RuntimeException('AWS configuration not detected.');
                    }
                    return Param::create($config['aws']);
                },
            'aws_service_config' => function(\Zend\ServiceManager\ServiceManager $sm) {
	            $config = $sm->get('config');
	            if (!isset($config['aws_services'])) {
		            throw new \RuntimeException('AWS Services configuration not detected.');
	            }
	            return Param::create($config['aws_services']);
            },
            'ses_config' => function(\Zend\ServiceManager\ServiceManager $sm) {
                    $config = $sm->get('aws_service_config');
	                return $config->in('ses');
                },
            'ses' => function(\Zend\ServiceManager\ServiceManager $sm) {
                    return \Aws\Ses\SesClient::factory($sm->get('ses_config')->get());
                },
            's3_config' => function(\Zend\ServiceManager\ServiceManager $sm) {
                    $aws = $sm->get('aws_service_config');
	                return $aws->in('s3');
                },
            's3_key_prefix' => function(\Zend\ServiceManager\ServiceManager $sm) {
                return $sm->get('s3_config')->get('environment_prefix', 'default') . '/';
            },
            's3' => function(\Zend\ServiceManager\ServiceManager $sm) {
                    return \Aws\S3\S3Client::factory($sm->get('s3_config')->get());
                },

        ),
        'invokables' => array(
            // search result services
            'result' => 'Application\Service\Search\Result',

            'users' => 'Application\Service\Search\Base\DQL\User',
            'user' => 'Application\Service\Update\User',

            'user_update_factory' => 'Application\Factory\Update\User',
            'email' => 'Application\Service\Email',
            'email_attachment' => 'Application\Service\Email\Attachment',

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
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Api\Index' => 'Application\Controller\Api\IndexController',
        ),
    ),

    'controller_plugins' => array(
        'invokables' => array(
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'request'        => 'Application\View\Helper\Request',
        ),
        'factories' => array(

            'mainMenu' => function($sm) {
	            $locator = $sm->getServiceLocator();
	            $nav = $sm->get('Zend\View\Helper\Navigation')->menu('navigation');
	            $nav->setUlClass('');
	            $nav->escapeLabels(false);
	            $nav->setMaxDepth(1);
	            //$nav->setPartial('partials/primary-nav');
//	            $acl = $locator->get('BjyAuthorize\Service\Authorize')->getAcl();
//	            $role = $locator->get('BjyAuthorize\Service\Authorize')->getIdentity();
//	            $nav->setAcl($acl);
//	            $nav->setRole($role);
//	            $nav->setUseAcl();
	            return $nav->setUlClass('nav')->setTranslatorTextDomain(__NAMESPACE__);
            }
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
            'layout/fullscreen'       => __DIR__ . '/../view/layout/fullscreen.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/403'               => __DIR__ . '/../view/error/403.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
            'zfcuser' => __DIR__ . '/../view',
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

//    'doctrine' => array(
//        'driver' => array(
//            __NAMESPACE__ . '_driver' => array(
//                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                'cache' => 'array',
//                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
//            ),
//            'orm_default' => array(
//                'drivers' => array(
//                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
//                )
//            )
//        )
//    )

);
