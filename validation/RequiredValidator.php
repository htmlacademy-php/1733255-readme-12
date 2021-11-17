<?php
require_once('Validator.php');

class RequiredValidator extends Validator
{

    public function validate($value): bool
    {
        if (empty($value)) {
            $this->setError("Поле не заполнено");
            return false;
        }
        return true;
    }
}
