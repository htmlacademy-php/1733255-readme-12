<?php
require_once ('helpers.php');
include 'autoloader.php';

session_start();

$errors = [];

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

$requiredValidator = new RequiredValidator();

if (!$requiredValidator->validate($login)) {
    $errors['login'] = $requiredValidator->getError();
}

if (!$requiredValidator->validate($password)) {
    $errors['password'] = $requiredValidator->getError();
}

$errors = array_filter($errors);

if ( ! empty($login) && count($errors) === 0 ) {
    $userRepository = new UserRepository();
    $user = $userRepository->findByName($login);

    $authError = 'Введен не верный логин или пароль';

    if ( ! $user ) {
        $errors['login'] = $authError;
    } else {
        $userName = $user->getUserName();
        $userPassword = $user->getPassword();
        $userAvatar= $user->getAvatar();

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
