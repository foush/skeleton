<?php
namespace Application\Service\Update;

use Application\Service\Update as UpdateService;
use Application\Util\Param;
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
    //const MAIN_TAG  = 'user';
    const TAG_ROLES = 'roles';

    const OFFICE_LOCATION_TAG = 'officeLocationEntity';
    const AUTHORIZED_ADJUSTER_TAG = 'authorizedAdjusterEntity';

    /**
     * Returns any side entities as a tag/entity associative array
     * @return array
     */
    protected function generateEntities()
    {
        /* @var $entity \Application\Entity\Base\User */
        $entity = $this->getEntity();

        return array(
            //self::OFFICE_LOCATION_TAG => $entity->getOfficeLocation(),
        );
    }

    /**
     * Workaround
     *
     * Roles is not defined in the form
     * So $form->setData(...) doesn't work to update the user's role
     * @param $valid
     * @param Param $params
     */
    protected function includeRoleEntity($valid, Param $params)
    {
        $user   = $this->getEntity();
        $roles  = $params->get('roles');

        if (isset($roles) && isset($roles[0]) && isset($roles[0]['id'])) {
            $roleId = $roles[0]['id'];
            $role   = $this->lookup('Application\Entity\Base\Role', $roleId);
            $user->setRoles(array($role));
        }
    }

    /**
     * This checks if the authorized adjuster was set.
     *
     * If null, then we are removing the adjuster entity from the user.
     * @param $valid
     * @param Param $params
     */
    protected function handleAdjusterEntity($valid, Param $params)
    {
        $user     = $this->getEntity();
        $adjuster = $params->get('authorizedAdjuster');

        if (!$adjuster) {
            $user->setAuthorizedAdjuster(new \Application\Entity\Base\AdjusterNull());
        }
    }

    /**
     * This checks if the office location was set.
     *
     * If null, then we are removing the office entity from the user.
     * @param $valid
     * @param Param $params
     */
    protected function handleOfficeEntity($valid, Param $params)
    {
        $user     = $this->getEntity();
        $office = $params->get('officeLocation');

        if (!$office) {
            $user->setOfficeLocation(new \Application\Entity\Base\OfficeNull());
        }
    }

    /**
     * When a new user is added, we'll need to generate a password
     *
     * This is a bit of placeholder functionality.
     *
     * @param $valid
     * @param Param $params
     */
    protected function addPasswordToNewUser($valid, Param $params)
    {
        $user   = $this->getEntity();

        if ($user->getPassword() == null) {
            $user->setPassword(uniqid());
        }
    }

    /**
     * @param $valid
     * @param Param $params
     */
    protected function _postProcessEntity($valid, Param $params)
    {
        $this->includeRoleEntity($valid, $params);
        $this->handleAdjusterEntity($valid, $params);
        $this->handleOfficeEntity($valid, $params);
        $this->addPasswordToNewUser($valid, $params);
    }

    /**
     * Called after all forms have been validated.
     * Passed the validation result
     * @param $valid
     * @param Param $params
     */
    protected function postValidate($valid, Param $params)
    {
        if ($valid) {
            $this->_postProcessEntity($valid, $params);
        }

        return parent::postValidate($valid, $params);
    }

    /**
     * Hook to allow setup of forms
     * @param array $entities
     */
    protected function beforeFormGeneration(array $entities)
    {
        $this->attachToMainEntity(self::OFFICE_LOCATION_TAG, $entities, 'setOfficeLocation');
        $this->attachToMainEntity(self::AUTHORIZED_ADJUSTER_TAG, $entities, 'setAuthorizedAdjuster');
    }

    /**
     * Function which attaches any data events to the
     * forms relevant to this update.
     * @return $this
     */
    protected function setUpFormDataEventListeners()
    {
        parent::setUpFormDataEventListeners();
        $this->configureFormToExcludeData(self::MAIN_TAG, array('office'));
        $this->setSubFormDataHandler(self::OFFICE_LOCATION_TAG, 'office');

        return $this;
    }

    /**
     * Array of form validator callables
     *
     * The default behavior is to simply return the result of '$form->isValid()'
     * however some further validation may be necessary (or $form->isValid() may not apply)
     * so this array allows you to specify a $tag => $callable associative array
     * where $callable accepts 2 params: (Param $params, Form $form)
     * and should return a boolean $isValid
     *
     * @return array
     */
    protected function generateFormValidators()
    {
        return array(
            self::MAIN_TAG =>
                function (Param $params, Form $form) {
                    return $this->preventDuplication($params, $form);
                }
        );
    }

    /**
     * Add a duplicate status validator to the name element
     * This presumes that name is the only field
     * used to identify a unique settlement
     *
     * @param  Form $form
     * @return bool
     */
    protected function preventDuplication(Param $params, Form $form)
    {
        /* @var \Application\Validator\DuplicateInsuranceCompany $duplicateValidator */
        $duplicateValidator = new DuplicateUserEmail();
        $duplicateValidator->setEntityManager($this->em());
        $duplicateValidator->setEntity($this->getEntity());
        $duplicateValidator->setParams($params);

        /* @var \Zend\Validator\ValidatorChain $validatorChain */
        $validatorChain = $form->getInputFilter()->get('email')->getValidatorChain();
        $validatorChain->attach($duplicateValidator);

        $duplicateValidator = new DuplicateUserUsername();
        $duplicateValidator->setEntityManager($this->em());
        $duplicateValidator->setEntity($this->getEntity());
        $duplicateValidator->setParams($params);

        /* @var \Zend\Validator\ValidatorChain $validatorChain */
        $validatorChain = $form->getInputFilter()->get('username')->getValidatorChain();
        $validatorChain->attach($duplicateValidator);

        return $form->isValid();
    }
}
