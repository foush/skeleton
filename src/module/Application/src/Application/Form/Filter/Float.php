<?php

namespace Application\Form\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

class Float extends AbstractFilter
{

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed                      $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if (empty($value) || $value === null)
            return floatval(0);

        return floatval(preg_replace('/[^\.|\d]/is', '', $value));
    }
}
