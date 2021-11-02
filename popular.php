<?php
require_once ('helpers.php');

session_start();

if ( empty($_SESSION) ) {
    header('Location: index.php');
}

$currentContentTypeId = $_GET['contentId'] ?? false;

$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");
$sqlContentTypes = '
SELECT type, title, id
  FROM content_types;
';
$sqlPostList = "
SELECT p.*, u.user_name, u.avatar, ct.type, ct.image_class
  FROM posts p
  JOIN users u ON p.user_id = u.id
  JOIN content_types ct ON p.content_type_id = ct.id
 WHERE IF (?, p.content_type_id = ?, true)
 ORDER BY views DESC
 LIMIT 6;
";

$resultContentTypes = mysqli_query($con, $sqlContentTypes);

$stmt = mysqli_prepare($con, $sqlPostList);
mysqli_stmt_bind_param($stmt, 'ii', $currentContentTypeId, $currentContentTypeId);
mysqli_stmt_execute($stmt);
$resultPostList = mysqli_stmt_get_result($stmt);

$rowContentTypes = mysqli_fetch_all($resultContentTypes, MYSQLI_ASSOC);
$rowPostList = mysqli_fetch_all($resultPostList, MYSQLI_ASSOC);

$mainContent = include_template('popular.php', ['contentTypes' => $rowContentTypes, 'postCards' => $rowPostList, 'currentContentTypeId' => $currentContentTypeId]);
$layoutContent = include_template('layout.php', prepareLayoutData($mainContent, 'Популярное'));

print($layoutContent);
