<?php
require_once ('helpers.php');
require_once ('validation.php');

$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");

$isSetUserPic = isset($_FILES['userpic-file']) && !boolval($_FILES['userpic-file']['error']);

$fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
$errors = [];

$email = $_POST['email'] ?? '';
$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';
$passwordRepeat = $_POST['password-repeat'] ?? '';

$rules = [
    'email' => function() use ($email, $con) {
        return validateEmail($email, $con);
    },
    'login' => function() use ($login) {
        return validateLogin($login);
    },
    'password' => function() use ($password) {
        return validatePassword($password);
    },
    'password-repeat' => function() use ($passwordRepeat, $password) {
        return validatePasswordRepeat($password, $passwordRepeat);
    },
    'userPic' => function() {
        return validateUserPic($_FILES['userpic-file']);
    },
];

foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule();
    }
}

if ($isSetUserPic) {
    $errors['userPic'] =  $rules['userPic']();
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
    $sqlNewUser = '
    INSERT INTO users (email, user_name, password, avatar)
    VALUES (?, ?, ?, ?);
    ';
    mysqli_stmt_execute(dbGetPrepareStmt($con, $sqlNewUser, [$_POST['email'], $_POST['login'], $passwordHash, $avatar]));

    header('Location: login.html');
}


$mainContent = include_template('registration.php', ['errors' => $errors]);
$layoutContent = include_template('layout.php', ['pageContent' => $mainContent, 'pageTitle' => 'Главная']);
print($layoutContent);
