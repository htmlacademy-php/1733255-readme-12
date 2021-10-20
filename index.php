<?php
require_once ('helpers.php');
require_once('validation/RequiredValidator.php');

session_start();

if ( !empty($_SESSION) ) {
    header('Location: feed.php');
}

$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");

$errors = [];

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

$rules = [
    'login' => function() use ($login) {
        $loginValidator = new RequiredValidator($login);
        return $loginValidator->getMessage();
    },
    'password' => function() use ($password) {
        $passwordValidator = new RequiredValidator($password);
        return $passwordValidator->getMessage();
    },
];

foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule();
    }
}

$errors = array_filter($errors);

if ( ! empty($login) && count($errors) === 0 ) {
    $sqlUser = "
    SELECT user_name, password, avatar
      FROM users
     WHERE user_name = ?
    ";
    $stmt = dbGetPrepareStmt($con, $sqlUser, [$login]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowUser = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if ( empty($rowUser) ) {
        $errors['login'] = 'Такого пользователя не существует';
    } else {
        $userName = $rowUser[0]['user_name'];
        $userPassword = $rowUser[0]['password'];
        $userAvatar= $rowUser[0]['avatar'];

        if ( ! password_verify($password, $userPassword )) {
            $errors['password'] = 'Неверный пароль';
        } else {
            $_SESSION['userName'] = $userName;
            $_SESSION['userAvatar'] = $userAvatar;
            header('Location: feed.php');
        }
    }
}


$content = include_template('main.php', ['errors' => $errors]);

print($content);
