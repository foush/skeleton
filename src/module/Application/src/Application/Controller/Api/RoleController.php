<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller\Api;

class RoleController extends AbstractApiController
{
    /**
     * @return mixed
     */
    protected function getSearchServiceKey()
    {
        return 'roles';
    }

    /**
     * @return mixed
     */
    protected function getUpdateServiceKey()
    {
        throw new \RuntimeException("Roles may not be updated");
    }
}
