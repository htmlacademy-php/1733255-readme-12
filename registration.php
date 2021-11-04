<?php
require_once ('helpers.php');
include 'autoloader.php';

spl_autoload_register(function ($classname){
    require_once ('validation/' . $classname . '.php');
});

$isSetUserPic = isset($_FILES['userpic-file']) && !boolval($_FILES['userpic-file']['error']);

$fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
$errors = [];

$login = $_POST['login'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$passwordRepeat = $_POST['password-repeat'] ?? '';

if (!empty($_POST)) {
    $loginValidator = new RequiredValidator();
    if (!$loginValidator->validate($login)) {
        $errors['login'] = $loginValidator->getError();
    }

    $emailValidator = new EmailValidator(new UserRepository());
    if (!$emailValidator->validate($email)) {
        $errors['email'] = $emailValidator->getError();
    }

    $passwordValidator = new RequiredValidator();
    if (!$passwordValidator->validate($password)) {
        $errors['password'] = $passwordValidator->getError();
    }

    $passwordRepeatValidator = new RepeatedValidator();
    if (!$passwordRepeatValidator->validate([$password, $passwordRepeat])) {
        $errors['password-repeat'] = $passwordRepeatValidator->getError();
    }

    if ($isSetUserPic) {
        $userPicValidator = new PhotoValidator();
        if (!$userPicValidator->validate($_FILES['userpic-file'])) {
            $errors['userPic'] = $userPicValidator->getError();
        }
    }
}

$errors = array_filter($errors);

if (count($errors) === 0 && !empty($_POST)) {
    $avatar = '';
    if ($isSetUserPic) {
        $fileExtension = preg_match('/image\/(\w+)/', $_FILES['userpic-file']['type'], $matches);
        $fileName = 'userpic-' . strtolower($_POST['login'] . '.' . $matches[1]);
        move_uploaded_file($_FILES['userpic-file']['tmp_name'], __DIR__ . '/img/' . $fileName);
        $avatar = $fileName;
    }
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $userRepository = new UserRepository();
    $userRepository->add($email, $login, $passwordHash, $avatar);

    header('Location: feed.php');
}

$mainContent = include_template('registration.php', ['errors' => $errors]);
$layoutContent = include_template('layout.php', ['pageContent' => $mainContent, 'pageTitle' => 'Регистрация']);
print($layoutContent);
