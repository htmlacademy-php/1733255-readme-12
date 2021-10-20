<?php
require_once('Validator.php');

class RepeatedValidator extends Validator
{

    public function __construct(string $passwordRepeat, string $password)
    {
        if (empty($passwordRepeat)) {
            $this->setMessage("Поле не заполнено");
        } elseif ($password !== $passwordRepeat) {
            $this->setMessage("Пароли не совпадают");
        } else {
            return '';
        }
        if (empty($value)) {
            $this->setMessage("Поле не заполнено");
        }
    }
}
