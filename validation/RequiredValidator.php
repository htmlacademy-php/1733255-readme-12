<?php
require_once('Validator.php');

class RequiredValidator extends Validator
{

    public function __construct(string $value)
    {
        if (empty($value)) {
            $this->setMessage("Поле не заполнено");
        }
    }
}
