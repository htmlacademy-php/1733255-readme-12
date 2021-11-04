<?php
require_once('Validator.php');
require_once('repository/UserRepository.php');

class EmailValidator extends Validator
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($email): bool
    {
        if (empty($email)) {
            $this->setError("Поле не заполнено");
            return false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setError("Введите корректный email");
            return false;
        } else {
            $existUser = $this->userRepository->findByEmail($email);
            if (!empty($existUser)) {
                $this->setError('Пользователь с такой электронной почтой уже существует');
                return false;
            }
        }
        return true;
    }
}
