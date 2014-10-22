<?php

namespace Application\Entity;

interface BaseInterface
{
    const DEFAULT_DATE_FORMAT = 'Y-m-d';
    const DATE_FORMAT_FLAT = 'm/d/Y';

    const ACTIVE   = 1;
    const INACTIVE = 0;

    const ACTIVE_STRING   = true;
    const INACTIVE_STRING = false;

    /**
     * Get entity id.
     * @return int
     */
    public function id();

    /**
     * Turn this entity into an associative array
     * @param  boolean $extended
     * @return array
     */
    public function flatten();

    /**
     * Returns whether this is a null object
     * @return boolean
     */
    public function isNull();

    /**
     * Calls flatten on each of the entities in a collection. If $indexById is true
     * the returned array is a key/value associative array with the entity ids as the key
     * @param $collection
     * @param  bool  $extended
     * @param  bool  $indexById
     * @return array
     */
    public function flatCollection($collection, $extended = false, $indexById = false);

    /**
     * Helper method to allow entities to set $this->property = $entity->asDoctrineProperty()
     * which will translate setting a null entity to setting a null value
     * @return \App\Entity\Base|null
     */
    public function asDoctrineProperty();

    /**
     * Adds $this to collection
     * @param  \Doctrine\Common\Collections\Collection $collection
     * @return mixed
     */
    public function addSelfTo(\Doctrine\Common\Collections\Collection $collection);

    /**
     * Helper method to allow entities to return
     * $this->nullGet('Property\Class\Name', $this->property)
     * and have the entity never return an actual null
     * @param  type                              $className
     * @param  \Application\Entity\BaseInterface $entity
     * @return \Application\Entity\BaseInterface
     */
    public function nullGet($className, BaseInterface $entity = null);

    /**
     * Used to verify the value is valid to be assigned to timestamp property.
     * Acceptable: either \DateTime object or string which can be parsed to a \DateTime value.
     * An \InvalidArgumentException is thrown if the passed value does not meet that criteria
     * @param $ts
     * @return \DateTime
     * @throws \InvalidArgumentException
     */
    public function tsSet($ts, $createIfEmpty = true);

    /**
     * Used to ensure a \DateTime is returned. If the given property is null, a new \DateTime
     * object is returned.
     * @param  \DateTime $ts The property which may contain a \DateTime value
     * @return \DateTime
     */
    public function tsGet(\DateTime $tsProperty = null, $createIfEmpty = true);

    /**
     * Returns a formatted form of the datetime property, if the property is not null
     * @param  \DateTime   $tsProperty
     * @param $format
     * @return string|null
     */
    public function tsGetFormatted(\DateTime $tsProperty = null, $format);

    /**
     * Form Tag set on this entity for this request
     * @return mixed
     */
    public function getFormTag();

    /**
     * Retrieve tag set on this entity
     * @param $tag
     * @return mixed
     */
    public function setFormTag($tag);

}
