<?php
require_once ('helpers.php');
include 'autoloader.php';

checkSession();

// Получаем типы контента
$contentTypeRepository = new ContentTypeRepository();
$contentTypes = $contentTypeRepository->all();

// Устанавливаем дефолтную форму
$currentContentTypeId = '1';

// Допустимые формы
$allowedContentTypeIds = [];
foreach ($contentTypes as $contentType) {
    array_push($allowedContentTypeIds, $contentType['id']);
}

// Показываем либо дефолтную форму, либо полученную через GET или POST (с валидацией)
if (isset($_GET['contentId']) && in_array($_GET['contentId'], $allowedContentTypeIds)) {
    $currentContentTypeId = ($_GET['contentId']);
} elseif (isset($_POST['contentId']) && in_array($_POST['contentId'], $allowedContentTypeIds)) {
    $currentContentTypeId = $_POST['contentId'];
}

$errors=[];

$title = $_POST['heading'] ?? '';
$content = $_POST['content'] ?? '';
$author = $_POST['author'] ?? '';
$url = $_POST['url'] ?? '';

$fileTypes = ['image/png', 'image/jpeg', 'image/gif'];

if (count($_POST) > 0) {
    // Формируем переменные для SQL запроса
    $video = $_POST['contentType'] === 'video' ? $_POST['url'] : '';
    $reference = $_POST['contentType'] === 'link' ? $_POST['url'] : '';
    $tags = [];
    $userId = '2';
    $contentTypeId = $currentContentTypeId;
    $img = '';

    // Валидация полученных данных
    foreach ($_POST as $key => $value) {
        if ($key === 'heading') {
            $titleValidator = new RequiredValidator();
            if (!$titleValidator->validate($title)) {
                $errors['heading'] = $titleValidator->getError();
            }
        }

        if ($key === 'content') {
            $contentValidator = new RequiredValidator();
            if (!$contentValidator->validate($content)) {
                $errors['content'] = $contentValidator->getError();
            }
        }

        if ($key === 'author') {
            $authorValidator = new RequiredValidator();
            if (!$authorValidator->validate($author)) {
                $errors['author'] = $authorValidator->getError();
            }
        }

        if ($key === 'url') {
            $urlValidator = new UrlValidator();
            if (!$urlValidator->validate($url)) {
                $errors['url'] = $urlValidator->getError();
            }
        }

        //Проверяем наличие тегов
        if ($key === 'tags' && !empty($value)) {
            $lowerTags = strtolower($value);
            preg_match_all('/[^a-zёа-я-\s]+/u', $lowerTags, $notAllowed);
            preg_match_all('/[a-zёа-я]+/u', $lowerTags, $allowed);
            $notLetters = $notAllowed[0];
            $letters = $allowed[0];
            if ($notLetters) {
                $errors[$key] = 'Используйте только буквы';
            } else {
                $tags = $letters;
            }
        }
    }

    // Удаляем ключ с отсутствующим значением
    $errors = array_filter($errors);

    // Отправляем данные в БД при отсутствии ошибок + загружаем фото
    if (count($errors) === 0) {
        // Загружаем фото
        if (isset($_FILES['photo'])) {
            if ($_FILES['photo']['name']) {
                $fileName = $_FILES['photo']['name'];
                $filePath = __DIR__ . '/uploads/';
                $img = '../uploads/' . $fileName; // Для SQL запроса
                move_uploaded_file($_FILES['photo']['tmp_name'], $filePath . $fileName);
            } else {
                $uploadedFile = file_get_contents($_POST['url']);
                $fileName = explode('.', parse_url(basename($_POST['url']))['path'])[0]; // Получаем имя файла без расширения
                $fileExtension = explode('/', get_headers($_POST['url'], 1)['Content-Type'])[1]; // Получаем расширение в независимости, было оно в URL или нет
                $filePath = __DIR__ . '/uploads/';
                $fullPath = $filePath . $fileName . '.' . $fileExtension;
                $img = '../uploads/' . $fileName . '.' . $fileExtension; // Для SQL запроса
                file_put_contents($fullPath, $uploadedFile);
            }
        }

        // Добавляем пост в БД
        $postRepository = new PostRepository();
        $newPostId = $postRepository->add([$title, $content, $author, $img, $video, $reference, $userId, $contentTypeId]);

        // Проверяем наличие тегов
        if (count($tags) > 0) {
            // Обновляем таблицу тегов
            $tagsRepository = new TagsRepository();
            $tagsRepository->add($tags);

            // Получаем добавленные теги для определения их ID
            $updatedTags = $tagsRepository->find($tags);

            // Сохраняем ID добавленных в базу тегов
            $tagIds = [];
            foreach ($updatedTags as $tag) {
                array_push($tagIds, $tag['id']);
            }

            // Добавляем ID поста к каждому ID хэштега
            $postTagsIds = [];
            foreach ($tagIds as $tagId) {
                array_push($postTagsIds, $newPostId);
                array_push($postTagsIds, $tagId);
            }

            // Связываем ID поста и ID тега
            $tagsRepository->addPostConnection($tagIds, $postTagsIds);
        }

        // Перенаправляем на страницу поста
        header('Location: post.php?postId=' . $newPostId);
    }
}

$mainContent = include_template('add.php', ['postContentTypes' => $contentTypes, 'currentContentTypeId' => $currentContentTypeId, 'errors' => $errors]);
$layoutContent = include_template('layout.php', prepareLayoutData($mainContent, 'Добавление публикации'));

print($layoutContent);
