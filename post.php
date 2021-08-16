<?php
require_once ('helpers.php');

$currentPostId = $_GET['postId'] ?? false;
$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");
$sqlPost = "
SELECT p.*, ct.type, COUNT(l.id) AS likes, COUNT(c.id) AS comments_total, u.avatar
  FROM posts p
  JOIN content_types ct ON p.content_type_id = ct.id
  LEFT JOIN likes l ON p.id = l.post_id
  LEFT JOIN comments c ON p.id = c.post_id
  JOIN users u ON p.user_id = u.id
 WHERE p.id = ?
 GROUP BY p.id;
";

$sqlPostHashtags = "
SELECT h.hashtag
  FROM hashtags h
  JOIN posts_hashtags ph ON h.id = ph.hashtag_id
 WHERE ph.post_id = ?;
";
/*
  Комментарии к посту
 */
$sqlPostComments = "
SELECT c.content, c.publication_date AS date, u.user_name AS author, u.avatar
  FROM comments c
  JOIN posts p ON c.post_id = p.id
  JOIN users u ON c.author_id = u.id
 WHERE c.post_id = ?;
";

/*
  Подробная информация об авторе конкретного поста
 */
$sqlPostAuthor = "
SELECT u.registration_date AS date, u.user_name, u.avatar, COUNT(p.id) AS posts_total, COUNT(s.id) AS subscribers_total
FROM posts p
JOIN users u ON p.user_id = u.id
LEFT JOIN subscriptions s ON u.id = s.author_id
WHERE u.id IN
     (SELECT p.user_id FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?)
GROUP BY u.id;
";

$postStmt = mysqli_prepare($con, $sqlPost);
mysqli_stmt_bind_param($postStmt, 'i', $currentPostId);
mysqli_stmt_execute($postStmt);
$resultPost = mysqli_stmt_get_result($postStmt);
$rowPost = mysqli_fetch_all($resultPost, MYSQLI_ASSOC);

$postHashtagsStmt = mysqli_prepare($con, $sqlPostHashtags);
mysqli_stmt_bind_param($postHashtagsStmt, 'i', $currentPostId);
mysqli_stmt_execute($postHashtagsStmt);
$resultPostHashtags = mysqli_stmt_get_result($postHashtagsStmt);
$rowPostHashtags = mysqli_fetch_all($resultPostHashtags, MYSQLI_ASSOC);

$postCommentsStmt = mysqli_prepare($con, $sqlPostComments);
mysqli_stmt_bind_param($postCommentsStmt, 'i', $currentPostId);
mysqli_stmt_execute($postCommentsStmt);
$resultPostComments = mysqli_stmt_get_result($postCommentsStmt);
$rowPostComments = mysqli_fetch_all($resultPostComments, MYSQLI_ASSOC);

$postAuthorStmt = mysqli_prepare($con, $sqlPostAuthor);
mysqli_stmt_bind_param($postAuthorStmt, 'i', $currentPostId);
mysqli_stmt_execute($postAuthorStmt);
$resultPostAuthor = mysqli_stmt_get_result($postAuthorStmt);
$rowPostAuthor = mysqli_fetch_all($resultPostAuthor, MYSQLI_ASSOC);

/*
  Выводим ошибку 404 если нет таких записей, либо нет параметра запроса
 */
if (count($rowPost) === 0) {
    http_response_code(404);
    die();
}

$userName = 'Игорь';

$mainContent = include_template('post.php', ['postDetails' => $rowPost[0], 'postHashtags' => $rowPostHashtags, 'postAuthorDetails' => $rowPostAuthor[0], 'postComments' => $rowPostComments]);
$layoutContent = include_template('layout.php', ['pageContent' => $mainContent, 'userName' => $userName,'pageTitle' => 'Пост']);

print($layoutContent);
