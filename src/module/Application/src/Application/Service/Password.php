<?php
namespace Application\Service;

use Application\Util\Param;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Application\Entity\Base\User;
use Zend\Mail\Message as Message;

/**
 * Class Password
 * @package Application\Service
 */
abstract class Password extends Base implements EventManagerAwareInterface
{
    /**
     *
     */
    const CONFIG_KEY = 'password_management';

    /**
     * @var array
     */
    protected $events;

    /**
     * @var
     */
    protected $entityManager;

    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var
     */
    protected $config;

    /**
     * @param  mixed $identifier
     * @return bool
     */
    public function handle($identifier = array(), $options = array())
    {
        /* @var \Application\Entity\Base\User $user */
        $user = $this->findUserByOne($identifier);

        if ($user !== null) {
            return $this->process($user, $options);
        } else {
            $this->addErrorMessage($this->unableToLocateUserErrorMessage());

            return false;
        }
    }

    /**
     * @param  \Application\Entity\Base\User $user
     * @param  array                         $options
     * @return boolean
     */
    abstract protected function process($user, $options = array());

    /**
     * Override this in your child class if you need to change the messaging.
     *
     * @return string
     */
    protected function unableToLocateUserErrorMessage()
    {
        return 'This password reset request is expired or invalid. Please check your email for the latest password reset link.';
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        if (!isset($config)) {
            $this->config = $this->getServiceLocator()->get('config');
        }

        return
            isset($this->config) &&
            isset($this->config[self::CONFIG_KEY]) ?
                $this->config[self::CONFIG_KEY]:
                null;
    }

    /**
     * Get a particular config value.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        $config = $this->getConfig();

        return
            isset($config[$key]) ?
                $config[$key] :
                $default;
    }

    /**
     * @param  array  $options
     * @param  string $key
     * @return null
     */
    protected function extractOption($options = array(), $key = '')
    {
        return
            isset($options[$key]) ?
                $options[$key] :
                null;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return $this->success;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        if (isset($this->errorMessages)) {
            return $this->errorMessages;
        }

        return array();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findUserByOne($identifier = array())
    {
        return
            $this
                ->entityManager
                ->getRepository('Application\Entity\Base\User')
                ->findOneBy($identifier);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findUserById($id)
    {
        return
            $this
                ->entityManager
                ->getRepository('Application\Entity\Base\User')
                ->find( $id);
    }

    /**
     * @param $emailAddress
     * @return mixed
     */
    public function findUserByEmail($emailAddress)
    {
        return
            $this
                ->entityManager
                ->getRepository('Application\Entity\Base\User')
                ->findOneBy(array('email' => $emailAddress));
    }

    /**
     * @param $passwordResetToken
     * @return mixed
     */
    public function findUserByToken($passwordResetToken)
    {
        return
            $this
                ->entityManager
                ->getRepository('Application\Entity\Base\User')
                ->findOneBy(array('passwordToken' => $passwordResetToken));
    }

    /**
     * @param $message
     */
    protected function addErrorMessage($message)
    {
        if (!isset($this->errorMessages)) {
            $this->errorMessages = array();
        }

        array_push( $this->errorMessages, $message);
        $this->success = false;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;

        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }
}
