<?php
namespace Application\Entity;

use FzyAuth\Entity\Base\AbstractUser;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @Annotation\Options({
 *      "autorender": {
 *          "ngModel": "user",
 *          "fieldsets": {
 *              {
 *                  "name": \FzyForm\Annotation\FieldSet::DEFAULT_NAME,
 *                  "legend": "User Information"
 *              }
 *          }
 *      }
 * })
 *
 */
class User extends AbstractUser implements UserInterface
{

}
