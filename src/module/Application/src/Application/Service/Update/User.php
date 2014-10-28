<?php
namespace Application\Service\Update;

use FzyCommon\Service\Update\Base as UpdateService;
use FzyCommon\Util\Params;
use Zend\Form\Form;
use Application\Validator\Duplicate\DuplicateUserEmail;
use Application\Validator\Duplicate\DuplicateUserUsername;

/**
 * Class Office
 * @package Application\Service\Update
 * Service Key: office
 */
class User extends UpdateService
{
    const MAIN_TAG  = 'user';


}
