<?php
require_once ('helpers.php');
var_dump($_POST);

$currentContentTypeId = $_GET['contentId'] ?? '1';

$errors=[];

foreach($_POST as $key => $value) {
    if ($key === 'text-heading' && empty($value)) {
        var_dump($key);
        $errors[$key] = 'Заголовок не заполнен';
    }
    if ($key === 'text-content' && empty($value)) {
        var_dump($key);
        $errors[$key] = 'Текст поста не заполнен';
    }
}

var_dump($errors);

$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");
$sqlContentTypes = '
SELECT type, title, id
  FROM content_types;
';

$resultContentTypes = mysqli_query($con, $sqlContentTypes);
$rowContentTypes = mysqli_fetch_all($resultContentTypes, MYSQLI_ASSOC);

$userName = 'Игорь';

$mainContent = include_template('add.php', ['postContentTypes' => $rowContentTypes, 'currentContentTypeId' => $currentContentTypeId, 'errors' => $errors]);
$layoutContent = include_template('layout.php', ['pageContent' => $mainContent, 'userName' => $userName, 'pageTitle' => 'Добавление публикации']);

print($layoutContent);
