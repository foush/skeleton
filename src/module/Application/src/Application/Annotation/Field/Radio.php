<?php
namespace Application\Annotation\Field;

use Application\Annotation\Field;
use Application\Annotation\Row;

class Radio extends Field
{
    const DEFAULT_TEMPLATE_ELEMENT_LABEL = 'partials/form/element/radio/label.phtml';
    const DEFAULT_TEMPLATE_ELEMENT_INPUT = 'partials/form/element/radio/input.phtml';

    public function onAddedTo(Row $row)
    {
        $row->setCssClass('fieldset radios');
        if ($row->getTemplate() == Row::DEFAULT_TEMPLATE) {
            $row->setTemplate('partials/form/element/radio/row.phtml');
        }
    }

    public function getInputType()
    {
        return 'radio';
    }

    /**
     * Returns array of key/value pairs to be used as the
     * selection options.
     * @return array
     */
    public function getValueSet()
    {
        return $this->getZendFormElement()->getValueOptions();
    }

}
