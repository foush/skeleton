<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller\Api;

use Zend\View\Model\JsonModel;

class UserController extends AbstractApiController
{
    /**
     * @return mixed
     */
    protected function getSearchServiceKey()
    {
        return 'users';
    }

    /**
     * @return mixed
     */
    protected function getUpdateServiceKey()
    {
        return 'user';
    }

    public function testAction()
    {
        return new JsonModel(array(
            array(
                'id' => 1,
                'firstName' => 'John',
                'lastName' => 'Foushee',
            ),
        ));
    }
}
