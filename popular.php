<?php
require_once ('helpers.php');
include 'autoloader.php';

checkSession();

$currentContentTypeId = $_GET['contentId'] ?? false;

$contentTypeRepository = new ContentTypeRepository();
$contentTypes = $contentTypeRepository->all();
$postRepository = new PostRepository();
$postList = $postRepository->findByTypeId($currentContentTypeId);

$mainContent = include_template('popular.php', ['contentTypes' => $contentTypes, 'postCards' => $postList, 'currentContentTypeId' => $currentContentTypeId]);
$layoutContent = include_template('layout.php', prepareLayoutData($mainContent, 'Популярное'));

print($layoutContent);
