<?php
namespace Application\View\Helper;

use Zend\Form\FormInterface;

class VotrSession extends Base
{
    const SESSION_SERVICE = 'votr_session';

    protected $container;

    /**
     * @param  FormInterface $form
     * @return $this|string
     */
    public function __invoke()
    {
        $this->container = $this->getService(self::SESSION_SERVICE);

        return $this;
    }

    public function getOffice()
    {
        return $this->get('office');
    }

    public function get($key)
    {
        return $this->container->$key;
    }

    public function getOfficeName()
    {
        return $this->container->office['name'];
    }

}
