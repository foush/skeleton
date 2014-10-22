<?php
namespace Application\View\Helper;

use Application\Entity\BaseInterface;

class EntityToForm extends Base
{
    public function __invoke(BaseInterface $entity)
    {
        return $this->getService('entity_to_form')->convertEntity($entity);
    }
}
