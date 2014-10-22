<?php
namespace Application\Service;

use Application\Annotation\Subform;
use Application\Entity\BaseInterface;
use Application\Exception\Update\FailedLookup;
use Application\Service\Search\Base as SearchService;
use Application\Util\Param;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Form\Form;
use Zend\EventManager\Event;

/**
 * Class Update
 * @package Application\Service
 */
class Update extends Base implements EventManagerAwareInterface
{

    const EVENT_CONFIGURE_ENTITY = 'configure';

    const EVENT_CONFIGURE_FORM = 'configure_form';

    const EVENT_FORM_DATA = 'formdata';

    const MAIN_ENTITY_CLASS = 'Application\Entity\Base';

    const MAIN_ENTITY_ID_PARAM = 'id';

    // OPERATIONAL CONSTANTS
    const OPERATION_CREATE = 'created';
    const OPERATION_UPDATE = 'updated';
    const OPERATION_DELETE = 'deleted';
    const OPERATION_NONE   = 'none';

    /**
     * Option during update. If this is set to true,
     * the form validation loop is stopped at the first
     * invalid result (rather than running through all forms)
     */
    const OPTION_SHORT_CIRCUIT = 'short_circuit';

    const MAIN_TAG = 'mainEntityTag';

    /**
     * @var BaseInterface
     */
    protected $entity;

    /**
     * @var array
     */
    protected $entities;

    /**
     * @var array
     */
    protected $forms;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * @var array
     */
    protected $errorMessages = array();

    /**
     * @var bool
     */
    protected $valid;

    /*
     * @var SearchService
     */
    protected $searchService;

    /**
     * Boolean flag to indicate if this entity was created or updated
     * @var bool
     */
    protected $created;

    protected $operation = self::OPERATION_NONE;

    /**
     * Array of form validator callables
     *
     * The default behavior is to simply return the result of '$form->isValid()'
     * however some further validation may be necessary (or $form->isValid() may not apply)
     * so this array allows you to specify a $tag => $callable associative array
     * where $callable accepts 2 params: (Param $params, Form $form)
     * and should return a boolean $isValid
     *
     * @var array
     */
    protected $formValidators;

    /**
     * Array mapping a getter string to a configured form
     * @var array
     */
    protected $formMap = array();

    /**
     * Reset state of service
     * @return $this
     */
    public function reset()
    {
        unset($this->entity);
        unset($this->entities);
        unset($this->forms);
        unset($this->valid);
        unset($this->params);
        unset($this->created);
        $this->setOperation(self::OPERATION_NONE);
        unset($this->formValidators);
        $this->formMap = array();
        $this->errorMessages = array();

        return $this;
    }

    /**
     * @return BaseInterface
     */
    public function createNewEntity()
    {
        $class = '\\'.static::MAIN_ENTITY_CLASS;
        $mainEntity = new $class();

        return $mainEntity;
    }

    /**
     * @param  Param                                      $params
     * @param  bool                                       $readonly
     * @return $this
     * @throws \Application\Exception\Update\FailedLookup
     */
    public function newEntity()
    {

        $this->setEntity($this->createNewEntity());

        return $this;
    }

    /**
     * @param  Param                                      $params
     * @param  bool                                       $readonly
     * @return $this
     * @throws \Application\Exception\Update\FailedLookup
     */
    public function setMainEntityFromParam(Param $params, $readonly = false)
    {
        $mainEntity = null;
        if ($params->has(static::MAIN_ENTITY_ID_PARAM)) {
            $mainEntityId = $params->get(static::MAIN_ENTITY_ID_PARAM);
            $class = '\\'.static::MAIN_ENTITY_CLASS;

            $searchService = $this->getSearchService($readonly);

            if (empty($searchService)) {
                // id was specified, but lookup failed
                throw new FailedLookup('Search service undefined for ' . static::MAIN_ENTITY_ID_PARAM . '.');
            } else {

                $mainEntity = $searchService->search($params, true)->getResult();

            }
        } else {
            $mainEntity = $this->createNewEntity();
        }

        if ($mainEntity == null) {
            // id was specified, but lookup failed
            throw new FailedLookup("Unable to locate " . static::MAIN_ENTITY_ID_PARAM . " with id: $mainEntityId");
        }
        if (!$mainEntity->id()) {
            $this->beforeSetEntityForNew($mainEntity, $params, $readonly);
        }
        $this->setEntity($mainEntity);

        return $this;
    }

    /**
     * Gets invoked for new entities
     * @param BaseInterface $mainEntity
     * @param Param         $params
     * @param bool          $readonly
     */
    public function beforeSetEntityForNew(BaseInterface $mainEntity, Param $params, $readonly)
    {

    }

    /**
     * @param SearchService $searchService
     */
    public function setSearchService(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * This returns the search service, also acts as a factory method
     * in the event that the search service is undefined.
     *
     * @param  bool          $readonly
     * @return SearchService
     */
    public function getSearchService($readonly = false)
    {
        if (empty($this->searchService) && $this->getServiceLocator()->has($this->getSearchServiceKey($readonly))) {

            // If the search service is not yet defined, use the service locator to locate it
            $this->searchService = $this->getServiceLocator()->get($this->getSearchServiceKey($readonly));
            $this->searchService->setCanCreate(true);
            // Acquire the configured search filter (if applicable) and add to the search service
            if ($this->getServiceLocator()->has($this->getSearchFilterServiceKey($readonly))) {
                $searchFilterService = $this->getServiceLocator()->get($this->getSearchFilterServiceKey($readonly));
                if (!$this->searchService->hasQueryFilter($this->getSearchFilterServiceKey($readonly))) {
                    $this->searchService->addQueryFilter($this->getSearchFilterServiceKey($readonly), $searchFilterService);
                }
            }
        }

        return $this->searchService;
    }

    /**
     * Returns the search service key (as defined in module.config.php or elsewhere)
     *
     * @param  bool   $readonly
     * @return string
     */
    protected function getSearchServiceKey($readonly = false)
    {
        // Default convention is that the search service is {entity}s (s.a. claims for the claim entity)
        return static::MAIN_ENTITY_ID_PARAM . 's';
    }

    /**
     * Returns the search filter service key ( as defined in module.config.php or elsewhere)
     *
     * @param  bool   $readonly
     * @return string
     */
    protected function getSearchFilterServiceKey($readonly = false)
    {
        // Default convention is that the search service is filter_{type}_{entity}s
        // ex. filter_search_claim for the viewing claim entity
        // ex. filter_update_claim for editing the claim entity
        $type = $readonly ? 'search' : 'update';

        return 'filter_' . $type . '_' . static::MAIN_ENTITY_ID_PARAM;
    }

    /**
     * @param  Param $params
     * @param  array $options
     * @return $this
     */
    public function update(Param $params, $options = array())
    {
        $valid = true;
        $this->setUpFormDataEventListeners();
        /* @var $form \Zend\Form\Form */
        foreach ($this->getForms() as $tag => $form) {
            if (!$this->updateIteration($tag, $form, $params)) {
                $valid = false;
                // optional to stop at the first failed validation or keep validating the rest of the forms
                if (isset($options[self::OPTION_SHORT_CIRCUIT]) && $options[self::OPTION_SHORT_CIRCUIT]) {
                    break;
                }
            }
        }

        $this->valid = $valid;
        $this->postValidate($valid, $params);

        return $this;
    }

    /**
     * @param $tag
     * @param  Form  $form
     * @param  Param $params
     * @return bool
     */
    public function updateIteration($tag, Form $form, Param $params)
    {
        $valid = true;
        $this->getEventManager()->trigger(self::EVENT_FORM_DATA.$tag, $this, array('form' => $form, 'data' => $params));
        if (!$this->call($this->getFormValidators(), $tag, array($params, $form), function (Param $params, Form $form) {return $form->isValid();})) {
            $valid = false;
            $this->errorMessages[$tag] = $this->normalizeMessages($form->getMessages());
        }

        return $valid;
    }

    /**
     * This is here to format standard form validation errors, so that
     * we can use customized error messaging instead of the 1 message only
     * practice from using annotations.
     *
     * @param $messages
     * @return array
     */
    protected function normalizeMessages($messages)
    {
        $queue = array();

        foreach ($messages as $key => $value) {
            if (is_array($value) && count($value)) {
                $queue[$key] = (array) array_pop($value);
            } else {
                $queue[$key] = (array) $value;
            }
        }

        return $queue;
    }

    /**
     * Abstract utility method for calling a callable in an array of tagged callables, passing it the specified params array
     * If the $tag key exists, that callable is invoked, otherwise the default callable is invoked.
     *
     * @param $callableArray
     * @param $tag
     * @param $params
     * @param $defaultCallable
     * @return mixed
     */
    protected function call(array $callableArray, $tag, array $params, $defaultCallable)
    {
        $callable = isset($callableArray[$tag]) ? $callableArray[$tag] : $defaultCallable;

        return call_user_func_array($callable, $params);
    }

    /**
     * Method to get form validators (triggering generateFormValidators once)
     * @return array
     */
    final protected function getFormValidators()
    {
        // Not sure how form validators wouldn't be set, but leaving in place for now
        if (!isset($this->formValidators)) {
            $this->formValidators = $this->generateFormValidators();
        }

        return $this->formValidators;
    }

    /**
     * Array of form validator callables
     *
     * The default behavior is to simply return the result of '$form->isValid()'
     * however some further validation may be necessary (or $form->isValid() may not apply)
     * so this array allows you to specify a $tag => $callable associative array
     * where $callable accepts 2 params: (Param $params, Form $form)
     * and should return a boolean $isValid
     *
     * @return array
     */
    protected function generateFormValidators()
    {
        return array();
    }

    /**
     * Called after all forms have been validated.
     * Passed the validation result
     * @param boolean $valid
     * @param Param   $params
     */
    protected function postValidate($valid, Param $params)
    {
        if ($valid) {
            // if the main entity has no id before DB flush, it is created, else it is updated/deleted
            $this->created = ($this->getEntity()->id() == null);

            if ($this->getEntity()->id() == null) {
                $this->setOperation(self::OPERATION_CREATE);
            }

            if ($this->getOperation() == self::OPERATION_NONE) {
                $this->setOperation(self::OPERATION_UPDATE);
            }

            foreach ($this->getEntities() as $tag => $entity) {
                $this->em()->persist($entity);
            }
            $this->em()->flush();
        }
    }

    /**
     * Function which attaches any data events to the
     * forms relevant to this update.
     * @return $this
     */
    protected function setUpFormDataEventListeners()
    {
        $this->formDataEvent(self::MAIN_TAG);

        return $this;
    }

    /**
     * Utility method for attaching a form data event listener
     * Utility method for attaching a form data event listener
     * for a form by a given tag. The optional 'callable' method
     * should accept and return a Param object.
     * @param $tag
     * @param null $callable
     */
    protected function formDataEvent($tag, $callable = null)
    {
        $this->getEventManager()->attach(self::EVENT_FORM_DATA.$tag, function ($event) use ($callable) {
            $eventParams = $event->getParams();
            /* @var $form \Zend\Form\Form */
            $form = $eventParams['form'];
            /* @var $data Param */
            $data = $eventParams['data'];
            if (!empty($callable) && is_callable($callable)) {
                $data = call_user_func($callable, $data);
            }
            $form->setData($data->get());
        });
    }

    /**
     * @param  BaseInterface $entity
     * @return Update
     */
    public function setEntity(BaseInterface $entity)
    {
        $this->entity = $this->preSetEntity($entity);

        return $this;
    }

    /**
     * @param  BaseInterface $entity
     * @return BaseInterface
     */
    protected function preSetEntity(BaseInterface $entity)
    {
        return $entity;
    }

    /**
     * @return BaseInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Generate forms from entities
     * @return array
     */
    public function getForms()
    {
        if (!isset($this->forms)) {
            $forms = array();
            $entities = $this->getEntities();
            // set up any connections
            $this->beforeFormGeneration($entities);
            foreach ($entities as $tag => $entity) {
                /* @var $form \Zend\Form\Form */
                $form = $this->entityToForm($entity);
                $this->getEventManager()
                    ->trigger(self::EVENT_CONFIGURE_FORM.$tag, $this, array(
                        'tag' => $tag,
                        'entity' => $entity,
                        'form' => $form,
                        'entities' => $entities
                    ));
                $forms[$tag] = $form;
            }
            // set up main form with map
            /* @var $mainForm \Zend\Form\Form */
            $mainForm = $forms[self::MAIN_TAG];
            $options = $mainForm->getOptions();
            $options[Subform::OPTION_MAP_ARRAY] = $this->formMap;
            $mainForm->setOptions($options);

            $this->forms = $forms;
        }

        return $this->forms;
    }

    /**
     * Modify array to remove the specified value
     * @param $list
     * @param $value
     * @return $this
     */
    protected function removeStringFromArray(&$list, $value)
    {
        $index = array_search($value, $list);
        if ($index !== false) {
            unset($list[$index]);
        }

        return $this;
    }

    /**
     * Hook to allow setup of forms
     * @param array $entities
     */
    protected function beforeFormGeneration(array $entities)
    {

    }

    /**
     * Attaches a subentity to the main entity in several ways:
     *
     *  During form generation, some subentities may be null on the main entity, the form
     *  will create a new doctrine entity to represent its data, the main entity needs to use
     *  the specified 'setter' to set the main entity's relation to this newly generated entity
     *  additionally, the entry for this new entity at 'tag' in the Update service's '$entities' array
     *  will be modified.
     *
     *  Finally, the main entity will have a mapping set to this configured form so- on render- your configured
     *  subform will be used
     *
     * @param $tag
     * @param $entities
     * @param $mainEntitySetter
     * @param  null  $afterAttachCallable
     * @param  null  $mainEntityGetter
     * @return $this
     */
    protected function attachToMainEntity($tag, $entities, $mainEntitySetter, $afterAttachCallable = null, $mainEntityGetter = null)
    {
        $mainEntity = $entities[self::MAIN_TAG];
        $self = $this;
        $this->getEventManager()
            ->attach(self::EVENT_CONFIGURE_FORM.$tag, function (Event $e) use ($tag, $mainEntity, $mainEntitySetter, $afterAttachCallable, $self, $mainEntityGetter) {
                /* @var $form \Zend\Form\Form */
                $form = $e->getParam('form');
                // attach main entity to the entity generated by the form for this tag
                /**
                 * IMPORTANT: can't use the address entity as this may be a null object. Once it has been
                 * run through entity-to-form, the form generates a non-null entity it will save to. The
                 * office entity will need to attach to that
                 */
                $mainEntity->$mainEntitySetter($form->getObject());
                // if this entity changed, update the entities array on this service
                $self->swapEntity($tag, $form->getObject());
                if (empty($mainEntityGetter)) {
                    $mainEntityGetter = 'g'.substr($mainEntitySetter, 1);
                }
                // map this generated form in the main entity form
                // so any configuration changes are reflected when rendered
                $self->setFormMapEntry($mainEntityGetter, $form);

                if (is_callable($afterAttachCallable)) {
                    call_user_func($afterAttachCallable, $form->getObject(), $form, $mainEntity, $tag);
                }
            });

        return $this;
    }

    /**
     * Utility method to convert entity to form.
     * @param  BaseInterface   $entity
     * @return \Zend\Form\Form
     */
    protected function entityToForm(BaseInterface $entity)
    {
        return $this->getServiceLocator()->get('entity_to_form')->convertEntity($entity);
    }

    /**
     * Returns any side entities as a tag/entity associative array
     * @return array
     */
    protected function generateEntities()
    {
        return array();
    }

    /**
     * Ensures 'generateEntities' is only ever invoked once.
     * Fires the EVENT_CONFIGURE_ENTITY event on each entity
     * to allow for any configuration.
     *
     * TODO: test that this even actually gets fired and provides
     * access/configuration to the entity
     *
     * @return array
     */
    final public function getEntities()
    {
        if (!isset($this->entities)) {
            $this->entities = array();
            $entities = $this->generateEntities();
            $entities[self::MAIN_TAG] = $this->getEntity();
            /* @var $entity \Application\Entity\BaseInterface */
            foreach ($entities as $tag => $entity) {
                $this->getEventManager()
                    ->trigger(self::EVENT_CONFIGURE_ENTITY.$tag, $this, array(
                        'tag' => $tag,
                        'entity' => $entity,
                        'entities' => $entities
                    ));
                $this->entities[$tag] = $entity;
                $entity->setFormTag($tag);
            }
        }

        return $this->entities;
    }

    /**
     * Utility to replace an entity at a tag
     * @param $tag
     * @param  BaseInterface     $entity
     * @return $this
     * @throws \RuntimeException
     */
    final public function swapEntity($tag, BaseInterface $entity)
    {
        if (!isset($this->entities[$tag])) {
            throw new \RuntimeException("Unable to swap entity that does not exist");
        }
        $this->entities[$tag] = $entity;
        $entity->setFormTag($tag);
        $this->getEventManager()
            ->trigger(self::EVENT_CONFIGURE_ENTITY.$tag, $this, array(
                'tag' => $tag,
                'entity' => $entity,
                'entities' => $this->getEntities(),
            ));

        return $this;
    }

    public function getEntitiesAsJson()
    {
        $result = array();
        $entities = $this->getEntities();
        /* @var $entity \Application\Entity\BaseInterface */
        foreach ($entities as $tag => $entity) {
            $result[$tag] = $entity->flatten();
        }

        return $result;
    }

    /**
     * Public getter for an entity associated with the given tag
     * @param null $tag
     */
    public function entity($tag = null)
    {
        return $this->getEntryForTag($this->getEntities(), $tag);
    }

    public function form($tag = null)
    {
        return $this->getEntryForTag($this->getForms(), $tag);
    }

    protected function getEntryForTag(array $arr, $tag = null)
    {
        if ($tag === null) {
            $tag = self::MAIN_TAG;
        }
        if (!isset($arr[$tag])) {
            throw new \InvalidArgumentException("No entry associated with tag ".$tag);
        }

        return $arr[$tag];
    }

    /**
     * @param  boolean $valid
     * @return Update
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param  array  $errorMessages
     * @return Update
     */
    public function setErrorMessages($errorMessages)
    {
        $this->errorMessages = $errorMessages;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @return string
     */
    public function getGenericSuccessMessage()
    {
        return 'Success';
    }

    /**
     * @return string
     */
    public function getUpdateSuccessMessage()
    {
        return 'Entity was updated';
    }

    /**
     * @return string
     */
    public function getCreateSuccessMessage()
    {
        return 'Entity was created';
    }

    /**
     * @return string
     */
    public function getDeleteSuccessMessage()
    {
        return 'Entity was deleted';
    }

    /**
     * Returns message based on whether the main entity was created or updated
     * @return string
     */
    public function getFormattedSuccessMessage()
    {
        return $this->getSuccessMessage($this->getOperation());
    }

    /**
     * @return array
     */
    public function getComputedOperation()
    {
        // TODO: This could be better implemented, keeping it simple

        if ($this->getCreated()) {
            return self::OPERATION_CREATE;
        }

        if ($this->getUpdated()) {
            return self::OPERATION_UPDATE;
        }

        if ($this->getDeleted()) {
            return self::OPERATION_DELETE;
        }

        return self::OPERATION_NONE;
    }

    /**
     * @return array
     */
    public function getSuccessMessages()
    {
        return
            array(
                self::OPERATION_CREATE => $this->getCreateSuccessMessage(),
                self::OPERATION_UPDATE => $this->getUpdateSuccessMessage(),
                self::OPERATION_DELETE => $this->getDeleteSuccessMessage(),
            );
    }

    /**
     * @return array
     */
    public function getSuccessMessage($key)
    {
        $messages = $this->getSuccessMessages();

        return
            isset($messages[$key]) ?
                $messages[$key] :
                $this->getGenericSuccessMessage();
    }

    /**
     * Returns the route name to redirect to on successful update
     * @return string
     */
    public function getSuccessRedirectRouteName()
    {
        return 'home';
    }

    /**
     * Returns any param values for the success redirect route
     * @return array
     */
    public function getSuccessRedirectRouteParams()
    {
        return array();
    }

    /**
     * Returns any option values for the success redirect route
     * @return array
     */
    public function getSuccessRedirectRouteOptions()
    {
        return array();
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;

        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }

    /**
     * @param  boolean $created
     * @return Update
     */
    public function setCreated($created)
    {
        throw new Exception('The function setCreated in Application\Service\Update ' .
            'is deprecated. Please use function setOperation(Application\Service\Update::OPERATION_CREATE) ' .
            'to indicate that this update created a new entity.');
    }

    /**
     * @return boolean
     */
    public function getCreated()
    {
        return $this->getOperation() == self::OPERATION_CREATE;
    }
    /**
     * @return boolean
     */
    public function getUpdated()
    {
        return $this->getOperation() == self::OPERATION_UPDATE;
    }

    /**
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->getOperation() == self::OPERATION_DELETE;
    }

    /**
     * Removes some elements from being validated; best used for subforms
     * @param $tag
     * @param array $elementNames
     */
    public function configureFormToExcludeData($tag, array $elementNames)
    {
        $removeCallable = array($this, 'removeStringFromArray');
        $self = $this;
        $this->getEventManager()->attach(self::EVENT_CONFIGURE_FORM.$tag, function (Event $event) use ($elementNames, $self) {
            /* @var $form \Zend\Form\Form */
            $form = $event->getParam('form');
            $fieldsToValidate = array_keys($form->getElements());
            foreach ($elementNames as $elementToRemove) {
                $self->removeStringFromArray($fieldsToValidate, $elementToRemove);
            }
            $form->setValidationGroup($fieldsToValidate);
        });
    }

    /**
     * Handler for subform data
     * @param $tag
     * @param $paramName
     * @return $this
     */
    public function setSubFormDataHandler($tag, $paramName)
    {
        $this->formDataEvent($tag, function (Param $params) use ($paramName) {return Param::create($params->get($paramName));});

        return $this;
    }

    /**
     * Special handler for Address subforms
     * @param $tag
     * @param $paramName
     * @return $this
     */
    public function setAddressFormDataHandler($tag, $paramName)
    {
        $this->formDataEvent($tag, function (Param $params) use ($paramName) {
            $params = Param::create($params->get($paramName));
            $state = $params->get('state');
            if (is_array($state)) {
                $params->set('state', isset($state['abbreviation']) ? $state['abbreviation'] : null);
            }

            return $params;
        });

        return $this;
    }

    public function setFormMapEntry($getterString, $configuredForm)
    {
        $this->formMap[$getterString] = $configuredForm;

        return $this;
    }

    /**
     * Function meant to be run as a callback for attacheToMainEntity.
     *
     * In this case, it's being used to filter form fields from the sub
     * forms and entities.
     *
     * @param $formObject
     * @param $form
     * @param $mainEntity
     * @param $tag
     */
    public function afterAttach($formObject, $form, $mainEntity, $tag)
    {
        foreach ($this->getExcludedFieldsForEntityTag($tag) as $fieldName) {
            /* @var \Zend\Form\Form $form */
            $form->remove($fieldName);
        }
    }

    /**
     * Given a tag (like contact) get a list of fields that need
     * to be excluded from the form.
     *
     * @param  string $entityTag
     * @return null   | array
     */
    protected function getExcludedFieldsForEntityTag($entityTag)
    {
        $excludedFields = $this->getExcludedFields();

        return
            isset($excludedFields[$entityTag]) ?
                $excludedFields[$entityTag] :
                array();
    }

    /**
     * Maintains a list of fields to exclude for this entity and
     * its subentities.
     *
     * @return array
     */
    protected function getExcludedFields()
    {
        return array();
    }

    /**
     * @param string $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }
}
