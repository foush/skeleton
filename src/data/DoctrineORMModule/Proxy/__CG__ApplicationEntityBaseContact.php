<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Base;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Contact extends \Application\Entity\Base\Contact implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', 'phone', 'fax', 'cell', 'email', 'id', 'dateFormatFlat');
        }

        return array('__isInitialized__', 'phone', 'fax', 'cell', 'email', 'id', 'dateFormatFlat');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Contact $proxy) {
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
    public function setCell($cell)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCell', array($cell));

        return parent::setCell($cell);
    }

    /**
     * {@inheritDoc}
     */
    public function getCell()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCell', array());

        return parent::getCell();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($email));

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setFax($fax)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFax', array($fax));

        return parent::setFax($fax);
    }

    /**
     * {@inheritDoc}
     */
    public function getFax()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFax', array());

        return parent::getFax();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhone($phone)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhone', array($phone));

        return parent::setPhone($phone);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhone()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhone', array());

        return parent::getPhone();
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
