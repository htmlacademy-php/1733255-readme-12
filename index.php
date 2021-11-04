<?php
require_once ('helpers.php');
include 'autoloader.php';

session_start();

$errors = [];

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

$loginValidator = new RequiredValidator();
if (!$loginValidator->validate($login)) {
    $errors['login'] = $loginValidator->getError();
}

$passwordValidator = new RequiredValidator();
if (!$passwordValidator->validate($password)) {
    $errors['password'] = $passwordValidator->getError();
}

$errors = array_filter($errors);

if ( ! empty($login) && count($errors) === 0 ) {
    $userRepository = new UserRepository();
    $user = $userRepository->findByName($login);

    $authError = 'Введен не верный логин или пароль';

    if ( empty($user) ) {
        $errors['login'] = $authError;
    } else {
        $userName = $user[0]['user_name'];
        $userPassword = $user[0]['password'];
        $userAvatar= $user[0]['avatar'];

        if ( ! password_verify($password, $userPassword )) {
            $errors['password'] = $authError;
        } else {
            $_SESSION['userName'] = $userName;
            $_SESSION['userAvatar'] = $userAvatar;
            header('Location: feed.php');
        }
    }
}


$content = include_template('main.php', ['errors' => $errors]);

print($content);
