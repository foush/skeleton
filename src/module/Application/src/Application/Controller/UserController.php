<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

class UserController extends AbstractWebController
{
    protected function getSearchServiceKey()
    {
        return 'users';
    }

    protected function getUpdateServiceKey()
    {
        return 'user';
    }
}
