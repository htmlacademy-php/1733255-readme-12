<?php
require_once ('helpers.php');
include 'autoloader.php';

checkSession();

$currentPostId = $_GET['postId'] ?? false;

/*
  Выводим ошибку 404 если нет параметра запроса
 */
if (!$currentPostId) {
    http_response_code(404);
    die();
}

$postRepository = new PostRepository();
$post = $postRepository->findByPostId($currentPostId);

$tagsRepository = new TagsRepository();
$hashtags = $tagsRepository->findByPostId($currentPostId);

$commentsRepository = new CommentsRepository();
$comments= $commentsRepository->findByPostId($currentPostId);

$authorRepository = new AuthorRepository();
$author= $authorRepository->findByPostId($currentPostId);

/*
  Выводим ошибку 404 если нет таких записей
 */
if (count($post) === 0) {
    http_response_code(404);
    die();
}

$postAuthorData = $author[0] ?? null;

$mainContent = include_template('post.php', [
    'postDetails' => $post[0],
    'postHashtags' => $hashtags,
    'postAuthorDetails' => $postAuthorData,
    'postComments' => $comments,
]);
$layoutContent = include_template('layout.php', prepareLayoutData($mainContent, 'Пост'));

print($layoutContent);
