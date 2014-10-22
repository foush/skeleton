<?php

namespace Application\View\Helper;

/**
 * Class Ordinal
 * @package Application\View\Helper
 */
class Ordinal extends Base
{
    /**
     *
     */
    const NUMERIC_SUFFIX_DEFAULT = 'th';

    /**
     * @var array
     */
    protected $defaultMap =
        array(
            1 => 'first',
            2 => 'second',
            3 => 'third',
            4 => 'fourth',
            5 => 'fifth',
        );

    /**
     * @param $index
     * @param  string $append
     * @param  array  $map
     * @return string
     */
    public function __invoke($index, $append = '', $map = array())
    {
        $thisMap = $map + $this->defaultMap;

        return ucfirst($this->getMappedIndex($thisMap, $index)) . (!empty($append) ? ' ' . $append : '');
    }

    /**
     * @return mixed
     */
    protected function getMap()
    {
        return $this->map;
    }

    /**
     * @param  array  $map
     * @param  int    $index
     * @return string
     */
    protected function getMappedIndex($map = array(), $index = 0)
    {
        if (isset($map) && isset($map[$index])) {
            return $map[$index];
        }

        return $index . $this->getNumericSuffix($index);
    }

    /**
     * @param $index
     * @return string
     */
    protected function getNumericSuffix($index)
    {
        return $index . self::NUMERIC_SUFFIX_DEFAULT;
    }

}
