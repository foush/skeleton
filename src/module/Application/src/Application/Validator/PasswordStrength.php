<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 8/18/14
 * Time: 11:19 AM
 */
namespace Application\Validator;

use Zend\Validator\AbstractValidator;

class  PasswordStrength extends AbstractValidator
{
    const LENGTH = 'length';
    const UPPER  = 'upper';
    const LOWER  = 'lower';
    const DIGIT  = 'digit';
    const GENERAL = 'general';

    protected $messageTemplates = array(
    self::GENERAL => "Your password must be at least 8 characters in length, and must contain at least one uppercase letter, at least one lowercase letter, and at least one digit character",
    self::LENGTH => "Your password must be at least 8 characters in length",
    self::UPPER  => "Your password must contain at least one uppercase letter",
    self::LOWER  => "Your password must contain at least one lowercase letter",
    self::DIGIT  => "Your password must contain at least one digit character"
);

    public function isValid($value)
    {
        $this->setValue($value);

        $isValid = true;

        if (strlen($value) < 8) {
            //$this->error(self::LENGTH);
            $this->error(self::GENERAL);
            $isValid = false;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            //$this->error(self::UPPER);
            $this->error(self::GENERAL);
            $isValid = false;
        }

        if (!preg_match('/[a-z]/', $value)) {
            //$this->error(self::LOWER);
            $this->error(self::GENERAL);
            $isValid = false;
        }

        if (!preg_match('/\d/', $value)) {
            //$this->error(self::DIGIT);
            $this->error(self::GENERAL);
            $isValid = false;
        }

        return $isValid;
    }
}
