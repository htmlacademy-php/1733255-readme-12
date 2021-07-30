<?php
require_once ('helpers.php');

$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");
$sqlContentTypes = '
SELECT type, image_class
  FROM content_types;
';
$sqlPostList = '
SELECT p.*, u.user_name, u.avatar, ct.image_class
  FROM posts p JOIN users u ON p.user_id = u.id
               JOIN content_types ct ON p.content_type_id = ct.id
 ORDER BY views DESC;
';
$resultContentTypes = mysqli_query($con, $sqlContentTypes);
$resultPostList = mysqli_query($con, $sqlPostList);

$rowContentTypes = mysqli_fetch_all($resultContentTypes, MYSQLI_ASSOC);
$rowPostList = mysqli_fetch_all($resultPostList, MYSQLI_ASSOC);

$userName = 'Игорь';

$mainContent = include_template('main.php', ['contentTypes' => $rowContentTypes, 'postCards' => $rowPostList]);

$layoutContent = include_template('layout.php', ['pageContent' => $mainContent, 'userName' => $userName,'pageTitle' => 'Главная']);

print($layoutContent);
