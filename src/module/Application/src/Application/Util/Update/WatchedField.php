<?php
namespace Application\Util\Update;

use Application\Entity\BaseInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Class WatchedField
 * @package Application\Util\Update
 */
class WatchedField
{
    /**
     * @var string
     */
    protected $fieldName;

    /**
     * @var string
     */
    protected $formattedName;

    /**
     * @var mixed
     */
    protected $oldValue;

    /**
     * @var mixed
     */
    protected $newValue;

    /**
     * @var BaseInterface
     */
    protected $entity;

    /**
     * @var bool
     */
    protected $adminOnly = false;

    /**
     * @var Callable
     */
    protected $formatCallable;

    /**
     * @param $fieldName
     * @param $formattedName
     * @param null $formatCallable
     */
    public function __construct($fieldName, $formattedName, $formatCallable = null, $adminOnly = false)
    {
        $this->setFieldName($fieldName);
        $this->setFormattedName($formattedName);
        $this->formatCallable = $formatCallable === null ? (function ($value) {return $value;}) : $formatCallable;
        $this->adminOnly = $adminOnly;
    }

    /**
     * Convenience method to set the couple of fields
     * @param  PreUpdateEventArgs $args
     * @return $this
     */
    public function setValuesFromArgs(PreUpdateEventArgs $args)
    {
        $this->setEntity($args->getEntity());
        $this->setOldValue($args->getOldValue($this->getFieldName()));
        $this->setNewValue($args->getNewValue($this->getFieldName()));

        return $this;
    }

    /**
     * @param  BaseInterface $entity
     * @return WatchedField
     */
    public function setEntity(BaseInterface $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return BaseInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param  mixed        $fieldName
     * @return WatchedField
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param  mixed        $newValue
     * @return WatchedField
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * @param  mixed        $oldValue
     * @return WatchedField
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @param  string       $formattedName
     * @return WatchedField
     */
    public function setFormattedName($formattedName)
    {
        $this->formattedName = $formattedName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormattedName()
    {
        return $this->formattedName;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function formatValue($value)
    {
        return call_user_func($this->formatCallable, $value);
    }

    /**
     * @return mixed
     */
    public function getFormattedOldValue()
    {
        return $this->formatValue($this->getOldValue());
    }

    /**
     * @return mixed
     */
    public function getFormattedNewValue()
    {
        return $this->formatValue($this->getNewValue());
    }

    /**
     * @return string
     */
    public function getActivityMessage()
    {
        return "Field {$this->formattedName} changed from {$this->getFormattedOldValue()} to {$this->getFormattedNewValue()}";
    }

    /**
     * @param boolean $adminOnly
     */
    public function setAdminOnly($adminOnly)
    {
        $this->adminOnly = $adminOnly;
    }

    /**
     * @return boolean
     */
    public function getAdminOnly()
    {
        return $this->adminOnly;
    }
}
