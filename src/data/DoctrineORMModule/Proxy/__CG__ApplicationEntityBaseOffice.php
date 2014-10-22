<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Base;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Office extends \Application\Entity\Base\Office implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'officeNumber', 'name', 'address', 'contact', 'tollFreePhone', 'contactName', 'active', 'invoicePrefix', 'id', 'dateFormatFlat');
        }

        return array('__isInitialized__', 'officeNumber', 'name', 'address', 'contact', 'tollFreePhone', 'contactName', 'active', 'invoicePrefix', 'id', 'dateFormatFlat');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Office $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * {@inheritDoc}
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());

        parent::__clone();
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function flatten()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'flatten', array());

        return parent::flatten();
    }

    /**
     * {@inheritDoc}
     */
    public function setActive($active)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setActive', array($active));

        return parent::setActive($active);
    }

    /**
     * {@inheritDoc}
     */
    public function getActive()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getActive', array());

        return parent::getActive();
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress(\Application\Entity\Base\AddressInterface $address)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAddress', array($address));

        return parent::setAddress($address);
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAddress', array());

        return parent::getAddress();
    }

    /**
     * {@inheritDoc}
     */
    public function setContact(\Application\Entity\Base\ContactInterface $contact)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContact', array($contact));

        return parent::setContact($contact);
    }

    /**
     * {@inheritDoc}
     */
    public function getContact()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContact', array());

        return parent::getContact();
    }

    /**
     * {@inheritDoc}
     */
    public function setContactName($contactName)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContactName', array($contactName));

        return parent::setContactName($contactName);
    }

    /**
     * {@inheritDoc}
     */
    public function getContactName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContactName', array());

        return parent::getContactName();
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', array($name));

        return parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', array());

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setOfficeNumber($officeNumber)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOfficeNumber', array($officeNumber));

        return parent::setOfficeNumber($officeNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getOfficeNumber()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOfficeNumber', array());

        return parent::getOfficeNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setTollFreePhone($tollFreePhone)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTollFreePhone', array($tollFreePhone));

        return parent::setTollFreePhone($tollFreePhone);
    }

    /**
     * {@inheritDoc}
     */
    public function getTollFreePhone()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTollFreePhone', array());

        return parent::getTollFreePhone();
    }

    /**
     * {@inheritDoc}
     */
    public function setInvoicePrefix($invoicePrefix)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInvoicePrefix', array($invoicePrefix));

        return parent::setInvoicePrefix($invoicePrefix);
    }

    /**
     * {@inheritDoc}
     */
    public function getInvoicePrefix()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInvoicePrefix', array());

        return parent::getInvoicePrefix();
    }

    /**
     * {@inheritDoc}
     */
    public function id()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'id', array());

        return parent::id();
    }

    /**
     * {@inheritDoc}
     */
    public function isNull()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isNull', array());

        return parent::isNull();
    }

    /**
     * {@inheritDoc}
     */
    public function flatCollection($collection, $extended = false, $indexById = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'flatCollection', array($collection, $extended, $indexById));

        return parent::flatCollection($collection, $extended, $indexById);
    }

    /**
     * {@inheritDoc}
     */
    public function asDoctrineProperty()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'asDoctrineProperty', array());

        return parent::asDoctrineProperty();
    }

    /**
     * {@inheritDoc}
     */
    public function addSelfTo(\Doctrine\Common\Collections\Collection $collection)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addSelfTo', array($collection));

        return parent::addSelfTo($collection);
    }

    /**
     * {@inheritDoc}
     */
    public function nullGet($className, \Application\Entity\BaseInterface $entity = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'nullGet', array($className, $entity));

        return parent::nullGet($className, $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function tsSet($ts, $createIfEmpty = true, $timezone = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'tsSet', array($ts, $createIfEmpty, $timezone));

        return parent::tsSet($ts, $createIfEmpty, $timezone);
    }

    /**
     * {@inheritDoc}
     */
    public function tsGet(\DateTime $tsProperty = NULL, $createIfEmpty = true)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'tsGet', array($tsProperty, $createIfEmpty));

        return parent::tsGet($tsProperty, $createIfEmpty);
    }

    /**
     * {@inheritDoc}
     */
    public function tsGetFormatted(\DateTime $tsProperty = NULL, $format, $timezone = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'tsGetFormatted', array($tsProperty, $format, $timezone));

        return parent::tsGetFormatted($tsProperty, $format, $timezone);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', array());

        return parent::__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function getFormTag()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFormTag', array());

        return parent::getFormTag();
    }

    /**
     * {@inheritDoc}
     */
    public function setFormTag($tag)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFormTag', array($tag));

        return parent::setFormTag($tag);
    }

}
