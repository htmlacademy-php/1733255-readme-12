<?php
require_once ('helpers.php');
require_once ('validation.php');

$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");

$sqlUsers = '
SELECT email
FROM users;
';
$resultUsers = mysqli_query($con, $sqlUsers);
$rowUsers = mysqli_fetch_all($resultUsers, MYSQLI_ASSOC);
$existingEmails = [];
foreach ($rowUsers as $user) {
    array_push($existingEmails, $user['email']);
}

$isSetUserPic = isset($_FILES['userpic-file']) && !boolval($_FILES['userpic-file']['error']);

$fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
$errors = [];
$rules = [
    'email' => function() use ($existingEmails) {
        return validateEmail($_POST['email'], $existingEmails);
    },
    'login' => function() {
        return validateLogin($_POST['login']);
    },
    'password' => function() {
        return validatePassword($_POST['password']);
    },
    'password-repeat' => function() {
        return validatePasswordRepeat($_POST['password'], $_POST['password-repeat']);
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
    if (!in_array($_FILES['userpic-file']['type'], $fileTypes)) {
        $errors['userPic'] = "Загрузите файл с расширением png, jpeg или gif";
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
