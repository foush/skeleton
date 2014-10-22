<?php
namespace Application\Annotation\Field;

use Application\Annotation\Field;
use Application\Annotation\Row;

class Checkbox extends Radio
{
    const DEFAULT_VALUE_LABEL = 'Yes';

    /**
     * @var string
     */
    protected $valueLabel;

    public function __construct(array $elementData)
    {
        parent::__construct($elementData);

        $this->valueLabel = $this->extractValue($elementData, 'value_label', self::DEFAULT_VALUE_LABEL );
    }

    public function onAddedTo(Row $row)
    {
        parent::onAddedTo($row);
        $row->setCssClass('fieldset checkboxes');
    }

    public function getInputType()
    {
        return 'checkbox';
    }

    public function getValueSet()
    {
        /* @var $e \Zend\Form\Element\Checkbox */

        return array($this->getZendFormElement()->getCheckedValue() => $this->getValueLabel());
    }

    /**
     * @param string $valueLabel
     */
    public function setValueLabel($valueLabel)
    {
        $this->valueLabel = $valueLabel;
    }

    /**
     * @return string
     */
    public function getValueLabel()
    {
        return $this->valueLabel;
    }
}
