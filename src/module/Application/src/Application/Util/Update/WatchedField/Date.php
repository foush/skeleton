<?php
namespace Application\Util\Update\WatchedField;

use Application\Util\Update\WatchedField;
use Application\Entity\BaseInterface as Entity;

class Date extends WatchedField
{
    public function formatValue($value)
    {
        /* @var $value \DateTime */

        return $value instanceof \DateTime ? $value->format(Entity::DATE_FORMAT_FLAT) : null;
    }

}
