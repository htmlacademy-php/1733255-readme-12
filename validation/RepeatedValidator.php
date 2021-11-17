<?php
require_once('Validator.php');

class RepeatedValidator extends Validator
{

    public function validate($passwords): bool
    {
        $password = $passwords[0];
        $passwordRepeat = $passwords[1];

        if (empty($passwordRepeat)) {
            $this->setError("Поле не заполнено");
            return false;
        } elseif ($password !== $passwordRepeat) {
            $this->setError("Пароли не совпадают");
            return false;
        }
        return true;
    }
}
