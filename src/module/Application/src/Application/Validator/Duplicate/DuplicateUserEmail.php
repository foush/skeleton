<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 8/18/14
 * Time: 11:19 AM
 */
namespace Application\Validator\Duplicate;

class DuplicateUserEmail extends DuplicateEntity
{
    const DUPLICATE = 'email';

    const MAIN_ENTITY_CLASS = 'Application\Entity\Base\User';

    protected $messageTemplates = array(
        self::DUPLICATE => "The email %value% is already in use."
    );

    public function isValid($value)
    {
        $this->setValue($value);

        $candidates =
            $this->getEntityManager()
                ->getRepository(self::MAIN_ENTITY_CLASS)
                ->findBy(array('email' => $value));

        foreach ($candidates as $candidate) {
            if (
                strcasecmp($candidate->getEmail(), $value) == 0 &&
                !$this->areDuplicate($this->getEntity(), $candidate)
            ) {
                $this->error(self::DUPLICATE);

                return false;
            }
        }

        return true;
    }
}
