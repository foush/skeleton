<?php
namespace Application\Annotation;

use Application\Annotation\Field\Checkbox;
use Application\Annotation\Field\MultiCheckbox;
use Application\Annotation\Field\Radio;
use Application\Annotation\Field\Select;
use Application\Service\EntityToForm;
use Zend\Form\ElementInterface;
use Application\Annotation\Form as AnnotatedForm;
use Application\Annotation\Field\Wysiwyg;

class Field extends Element
{
    const ELEMENT_TYPE = 'field';

    const DEFAULT_TEMPLATE_ELEMENT_LABEL   = 'partials/form/element/label.phtml';
    const DEFAULT_TEMPLATE_ELEMENT_INPUT   = 'partials/form/element/input.phtml';
    const DEFAULT_TEMPLATE_ELEMENT_BUTTONS = 'partials/form/element/buttons.phtml';
    const DEFAULT_TEMPLATE_ELEMENT_HELP    = 'partials/form/element/help.phtml';
    const DEFAULT_TEMPLATE_ELEMENT_ERROR   = 'partials/form/element/error.phtml';

    protected $rowName;

    protected $labelTemplate;
    protected $inputTemplate;
    protected $buttonsTemplate;
    protected $helpTemplate;
    protected $errorTemplate;

    protected $helpText;

    protected $ngModel;

    protected $buttons;

    /**
     * @var strings
     */
    protected $ngClick;

    protected $fieldsetName;

    public function __construct(array $elementData)
    {
        parent::__construct($elementData);
        $this->setRowName(isset($elementData['row']) ? $elementData['row'] : $this->getName().'Row');
        $this->fieldsetName = $this->extractValue($elementData, 'fieldset');
        $this->helpText         = $this->extractValue($elementData, 'help');
        $this->labelTemplate    = $this->extractValue($elementData, 'labelTemplate', static::DEFAULT_TEMPLATE_ELEMENT_LABEL);
        $this->inputTemplate    = $this->extractValue($elementData, 'inputTemplate', static::DEFAULT_TEMPLATE_ELEMENT_INPUT);
        $this->buttonsTemplate  = $this->extractValue($elementData, 'buttonsTemplate', static::DEFAULT_TEMPLATE_ELEMENT_BUTTONS);
        $this->helpTemplate     = $this->extractValue($elementData, 'helpTemplate', static::DEFAULT_TEMPLATE_ELEMENT_HELP);
        $this->errorTemplate    = $this->extractValue($elementData, 'errorTemplate', static::DEFAULT_TEMPLATE_ELEMENT_ERROR);
        $this->ngModel          = $this->extractValue($elementData, 'ngModel');
        $this->ngClick          = $this->extractValue($elementData, 'ngClick');

        $this->buttons = $this->extractValue($elementData, 'buttons', '');
    }

    public static function create(array $elementData, ElementInterface $element, AnnotatedForm $form, EntityToForm $e2f)
    {
        $type = isset($elementData['type']) ? $elementData['type'] : '';
        if (empty($type)) {
            // inferr type from ZF2 Form Element type
            $clsName = get_class($element);
            $parts = explode('\\', $clsName);
            $type = end($parts);
        }
        switch (strtolower(trim($type))) {
            case 'subform':
                $annotation = new Subform($elementData);
                // do not 'setZendFormElement' because this element is replaced by the subform
                $annotation->populateFromParent($form, $e2f);
                break;
            case 'select':
                $annotation = new Select($elementData);
                $annotation->setZendFormElement($element);
                break;
            case 'checkbox':
                $annotation = new Checkbox($elementData);
                $annotation->setZendFormElement($element);
                break;
            case 'multicheckbox':
                // MultiCheckbox isn't fully implemented at this time, this is just a stub
                $annotation = new MultiCheckbox($elementData);
                $annotation->setZendFormElement($element);
                break;
            case 'radio':
                $annotation = new Radio($elementData);
                $annotation->setZendFormElement($element);
                break;
            case 'wysiwyg':
                $annotation = new Wysiwyg($elementData);
                $annotation->setZendFormElement($element);
                break;
            default:
                $annotation = new Field($elementData);
                $annotation->setZendFormElement($element);
        }
        $annotation->setParentForm($form);

        return $annotation;
    }

    /**
     * @param  mixed $rowName
     * @return Field
     */
    public function setRowName($rowName)
    {
        $this->rowName = $rowName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRowName()
    {
        return $this->rowName;
    }

    /**
     * @param  mixed $helpText
     * @return Field
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * @param  null  $helpTemplate
     * @return Field
     */
    public function setHelpTemplate($helpTemplate)
    {
        $this->helpTemplate = $helpTemplate;

        return $this;
    }

    /**
     * @return null
     */
    public function getHelpTemplate()
    {
        return $this->helpTemplate;
    }

    /**
     * @param  null  $inputTemplate
     * @return Field
     */
    public function setInputTemplate($inputTemplate)
    {
        $this->inputTemplate = $inputTemplate;

        return $this;
    }

    /**
     * @return null
     */
    public function getInputTemplate()
    {
        return $this->inputTemplate;
    }

    /**
     * @param null $buttonsTemplate
     */
    public function setButtonsTemplate($buttonsTemplate)
    {
        $this->buttonsTemplate = $buttonsTemplate;
    }

    /**
     * @return null
     */
    public function getButtonsTemplate()
    {
        return $this->buttonsTemplate;
    }

    /**
     * @param  null  $labelTemplate
     * @return Field
     */
    public function setLabelTemplate($labelTemplate)
    {
        $this->labelTemplate = $labelTemplate;

        return $this;
    }

    /**
     * @return null
     */
    public function getLabelTemplate()
    {
        return $this->labelTemplate;
    }

    /**
     * @param  null  $errorTemplate
     * @return Field
     */
    public function setErrorTemplate($errorTemplate)
    {
        $this->errorTemplate = $errorTemplate;

        return $this;
    }

    /**
     * @return null
     */
    public function getErrorTemplate()
    {
        return $this->errorTemplate;
    }

    public function onAddedTo(Row $row)
    {
        // allows config of that row

    }

    /**
     * @param  null    $ngModel
     * @return Element
     */
    public function setNgModel($ngModel)
    {
        $this->ngModel = $ngModel;

        return $this;
    }

    /**
     * @return null
     */
    public function getNgModel()
    {
        return $this->ngModel;
    }

    /**
     *
     */
    public function getFullNgModel()
    {
        $components = array($this->getNgModel());
        $e = $this;
        /* @var $form \Application\Annotation\Form */
        while (($form = $e->getParentForm()) != null) {
            $components[] = $form->getNgModel();
            $e = $form;
        }
        $filtered = array_filter($components);

        return implode('.', array_reverse($filtered));
    }

    /**
     * @param \Application\Annotation\strings $ngClick
     */
    public function setNgClick($ngClick)
    {
        $this->ngClick = $ngClick;
    }

    /**
     * @return \Application\Annotation\strings
     */
    public function getNgClick()
    {
        return $this->ngClick;
    }

    /**
     * @param null $buttons
     */
    public function setButtons($buttons)
    {
        $this->buttons = $buttons;
    }

    /**
     * @return null
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @param  null  $fieldsetName
     * @return Field
     */
    public function setFieldsetName($fieldsetName)
    {
        $this->fieldsetName = $fieldsetName;

        return $this;
    }

    /**
     * @return null
     */
    public function getFieldsetName()
    {
        return $this->fieldsetName;
    }

}
