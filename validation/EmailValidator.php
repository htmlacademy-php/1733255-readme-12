<?php
require_once('Validator.php');

class EmailValidator extends Validator
{

    public function __construct(string $email, mysqli $con)
    {
        if (empty($email)) {
            $this->setMessage("Поле не заполнено");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setMessage("Введите корректный email");
        } else {
            $sqlUser = '
            SELECT email
              FROM users
             WHERE email = ?
            ';
            $stmt = dbGetPrepareStmt($con, $sqlUser, [$email]);
            mysqli_stmt_execute($stmt);
            $resultUser = mysqli_stmt_get_result($stmt);
            $rowUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC);
            if (!empty($rowUser)) {
                $this->setMessage("Пользователь с такой электронной почтой уже существует");
            }
        }
    }
}
