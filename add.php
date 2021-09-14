<?php
require_once ('helpers.php');
require_once ('validation.php');

// Соединение с БД для получения и отправки данных
$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");

// Получаем типы контента
$sqlContentTypes = '
SELECT type, title, id
  FROM content_types;
';
$resultContentTypes = mysqli_query($con, $sqlContentTypes);
$rowContentTypes = mysqli_fetch_all($resultContentTypes, MYSQLI_ASSOC);

// Устанавливаем дефолтную форму
$currentContentTypeId = '1';

// Допустимые формы
$allowedContentTypeIds = [];
foreach ($rowContentTypes as $contentType) {
    array_push($allowedContentTypeIds, $contentType['id']);
}

// Показываем либо дефолтную форму, либо полученную через GET или POST (с валидацией)
if (isset($_GET['contentId']) && in_array($_GET['contentId'], $allowedContentTypeIds)) {
    $currentContentTypeId = ($_GET['contentId']);
} elseif (isset($_POST['contentId']) && in_array($_POST['contentId'], $allowedContentTypeIds)) {
    $currentContentTypeId = $_POST['contentId'];
}

$errors=[];
$rules = [
    'heading' => function() {
        return validateTitle('heading');
    },
    'content' => function() {
        return validateContent('content');
    },
    'author' => function() {
        return validateAuthor('author');
    },
    'url' => function() {
        return validateUrl('url');
    },
];
$fileTypes = ['image/png', 'image/jpeg', 'image/gif'];

if (count($_POST) > 0) {
    // Формируем переменные для SQL запроса
    $title = $_POST['heading'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? '';
    $tags = [];
    $video = $_POST['contentType'] === 'video' ? $_POST['url'] : '';
    $reference = $_POST['contentType'] === 'link' ? $_POST['url'] : '';
    $userId = '2';
    $contentTypeId = $currentContentTypeId;
    $img = '';

    // Валидация полученных данных
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
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
        $sqlNewPost = '
        INSERT INTO posts (title, content, author, img, video, reference, user_id, content_type_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?);
        ';

        mysqli_stmt_execute(dbGetPrepareStmt($con, $sqlNewPost, [$title, $content, $author, $img, $video, $reference, $userId, $contentTypeId]));

        $newPostId = mysqli_insert_id($con); // Сохраняем ID нового поста

        // Проверяем наличие тегов
        if (count($tags) > 0) {
            $sqlTags = '
            SELECT id, hashtag
              FROM hashtags
            ';
            $resultOldTags = mysqli_query($con, $sqlTags);
            $rowOldTags = mysqli_fetch_all($resultOldTags, MYSQLI_ASSOC);

            $tagIds = []; // массив ID тегов для добавления в таблицу связей тега и поста

            // Если тег уже в базе, сохраняем его ID и убираем из массива тегов поста
            foreach ($rowOldTags as $oldTag) {
                foreach ($tags as $tagIndex => $tagValue) {
                    if ($oldTag['hashtag'] === $tagValue) {
                        array_push($tagIds, intval($oldTag['id']));
                        unset($tags[$tagIndex]);
                    }
                }
            }

            // Оставшиеся в массиве теги добавляем в таблицу тегов
            if (count($tags) > 0) {
                $insertTagValue = '(?)';
                $insertTagValuesSum = [];

                $selectTagValue = '?';
                $selectTagValuesSum = [];

                foreach ($tags as $tag) {
                    array_push($insertTagValuesSum, $insertTagValue);
                    array_push($selectTagValuesSum, $selectTagValue);
                }

                $sqlCreateTags = '
                INSERT INTO hashtags (hashtag)
                VALUES ' . implode(',', $insertTagValuesSum);
                mysqli_stmt_execute(dbGetPrepareStmt($con, $sqlCreateTags, [...$tags]));

                // Получаем добавленные теги для определения их ID
                $sqlNewTags = '
                SELECT id, hashtag
                  FROM hashtags
                 WHERE hashtag IN (' . implode(',', $selectTagValuesSum) . ')';
                $stmt = dbGetPrepareStmt($con, $sqlNewTags, [...$tags]);
                mysqli_stmt_execute($stmt);
                $resultNewTags = mysqli_stmt_get_result($stmt);
                $rowUpdatedTags = mysqli_fetch_all($resultNewTags, MYSQLI_ASSOC);

                // Сохраняем ID добавленных в базу тегов
                foreach ($rowUpdatedTags as $updatedTag) {
                    array_push($tagIds, $updatedTag['id']);
                }

                // Подготавливаем данные для отправки в таблицу связи ID поста и ID тега
                $insertTagsPostsConValue = '(? , ?)';
                $insertTagsPostsConValuesSum = [];

                foreach ($tagIds as $tagId) {
                    array_push($insertTagsPostsConValuesSum, $insertTagsPostsConValue);
                }

                // Добавляем ID поста к каждому ID хэштега
                $postTagsIds = [];
                foreach ($tagIds as $tagId) {
                    array_push($postTagsIds, $newPostId);
                    array_push($postTagsIds, $tagId);
                }

                $sqlTagsPostsCon = '
                INSERT INTO posts_hashtags (post_id, hashtag_id)
                VALUES ' . implode(',', $insertTagsPostsConValuesSum);
                mysqli_stmt_execute(dbGetPrepareStmt($con, $sqlTagsPostsCon, [...$postTagsIds]));
            }
        }

        // Перенаправляем на страницу поста
        header('Location: post.php?postId=' . $newPostId);
    }
}

// Формируем страницу
$userName = 'Игорь';

$mainContent = include_template('add.php', ['postContentTypes' => $rowContentTypes, 'currentContentTypeId' => $currentContentTypeId, 'errors' => $errors]);
$layoutContent = include_template('layout.php', ['pageContent' => $mainContent, 'userName' => $userName, 'pageTitle' => 'Добавление публикации']);

print($layoutContent);
