<?php
require_once ('helpers.php');

// Соединение с БД для получения и отправки данных
$con = mysqli_connect('localhost', 'root', '', 'readme');
mysqli_set_charset($con, "utf8");

// Показываем либо дефолтную форму, либо полученную через GET или POST
$currentContentTypeId = '1';

if (isset($_GET['contentId'])) {
    $currentContentTypeId = ($_GET['contentId']);
} elseif (isset($_POST['contentId'])) {
    $currentContentTypeId = $_POST['contentId'];
}

$errors=[];
$FILE_TYPES = ['image/png', 'image/jpeg', 'image/gif'];

if (count($_POST)) {
    // Формируем переменные для SQL запроса
    $title = $_POST['heading'];
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? '';
    $tags = [];
    $video = $_POST['contentType'] === 'video' ? $_POST['url'] : '';
    $reference = $_POST['contentType'] === 'link' ? $_POST['url'] : '';
    $userId = '2';
    $contentTypeId = $_POST['contentId'];
    $img = '';

    // Валидация полученных данных
    foreach ($_POST as $key => $value) {
        // Проверяем заполнение необходимых полей
        if (empty($value) && ($key === 'heading' || $key === 'content' || $key === 'author' || $key === 'url')) {
            $errors[$key] = 'Поле не заполнено';
        }

        // Проверяем, что отправленная форма типа "ФОТО"
        if (isset($_FILES['photo'])) {
            if ($_FILES['photo']['error'] && $key === 'url' && empty($value)) { // Отсутствует ссылка и файл
                $errors[$key] = 'Загрузите фотографию';
            } elseif (!$_FILES['photo']['error'] && $key === 'url') { // Есть файл, игнорируем ссылку
                unset($errors[$key]); // Убираем ошибку "Поле не заполнено"
                if (!in_array($_FILES['photo']['type'], $FILE_TYPES)) { // Неверный формат файла
                    $errors['url'] = 'Загрузите корректный тип файла';
                } else if (!$errors) { // Загружаем файл при отсутствии ошибок (в т.ч) в других полях
                    $fileName = $_FILES['photo']['name'];
                    $filePath = __DIR__ . '/uploads/';
                    $img = '../uploads/' . $fileName; // Для SQL запроса
                    move_uploaded_file($_FILES['photo']['tmp_name'], $filePath . $fileName);
                }
            } elseif ($_FILES['photo']['error'] && $key === 'url' && filter_var($value, FILTER_VALIDATE_URL)) { // Файла нет, проверяем ссылку
                if (!file_get_contents($value)) {
                    $errors['url'] = 'Не удалось загрузить файл';
                } else {
                    $uploadedFile = file_get_contents($value);
                    $fileName = explode('.', parse_url(basename($value))['path'])[0]; // Получаем имя файла без расширения
                    $fileExtension = explode('/', get_headers($value, 1)['Content-Type'])[1]; // Получаем расширение в независимости, было оно в URL или нет
                    $filePath = __DIR__ . '/uploads/';
                    $fullPath = $filePath . $fileName . '.' . $fileExtension;
                    $img = '../uploads/' . $fileName . '.' . $fileExtension; // Для SQL запроса
                    file_put_contents($fullPath, $uploadedFile);
                }
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

        // Проверяем ссылки всех типов форм на корректный формат
        if ($key === 'url' && !empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
            $errors[$key] = 'Введите корректную ссылку';
        }

        // Проверяем ссылку на YouTube из типа "ВИДЕО"
        if ($key === 'url' && $_POST['contentType'] === 'video') {
            if (!is_bool(checkYoutubeUrl($value))) {
                $errors[$key] = checkYoutubeUrl($value);
            }
        }
    }

    // Отправляем данные в БД при отсутствии ошибок
    if (!count($errors)) {
        // Добавляем пост в БД
        $sqlNewPost = '
        INSERT INTO posts (title, content, author, img, video, reference, user_id, content_type_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?);
        ';
        $stmt = mysqli_prepare($con, $sqlNewPost);
        mysqli_stmt_bind_param($stmt, 'ssssssii', $title, $content, $author, $img, $video, $reference, $userId, $contentTypeId);
        mysqli_stmt_execute($stmt);

        $newPostId = mysqli_insert_id($con); // Сохраняем ID нового поста

        // Проверяем наличие тегов
        if (count($tags)) {
            $sqlOldTags = '
            SELECT id, hashtag
              FROM hashtags
             WHERE hashtag = ?;
            ';
            $sqlNewTags = '
            INSERT INTO hashtags (hashtag)
            VALUES (?);
            ';
            $sqlTagsPostsCon = '
            INSERT INTO posts_hashtags (post_id, hashtag_id)
            VALUES (?, ?);
            ';
            foreach ($tags as $tag) {
                // Проверяем наличие тега в базе
                $oldTagsStmt = mysqli_prepare($con, $sqlOldTags);
                mysqli_stmt_bind_param($oldTagsStmt, 's', $tag);
                mysqli_stmt_execute($oldTagsStmt);
                $resultOldTags = mysqli_stmt_get_result($oldTagsStmt);
                $oldTag = mysqli_fetch_all($resultOldTags);

                if (!empty($oldTag)) { // Если тег уже есть в БД, связываем его с постом
                    $tagId = $oldTag[0][0];
                } else {
                    // Сохраняем новые теги
                    $newTagsStmt = mysqli_prepare($con, $sqlNewTags);
                    mysqli_stmt_bind_param($newTagsStmt, 's', $tag);
                    mysqli_stmt_execute($newTagsStmt);

                    $tagId = mysqli_insert_id($con); // Сохраняем ID нового тега
                }

                // Сохраняем новые связи тегов
                $newTagsPostsConStmt = mysqli_prepare($con, $sqlTagsPostsCon);
                mysqli_stmt_bind_param($newTagsPostsConStmt, 'ii', $newPostId, $tagId);
                mysqli_stmt_execute($newTagsPostsConStmt);
            }
        }

        // Перенаправляем на страницу поста
        header('Location: post.php?postId=' . $newPostId);
    }
}

// Получаем типы контента
$sqlContentTypes = '
SELECT type, title, id
  FROM content_types;
';
$resultContentTypes = mysqli_query($con, $sqlContentTypes);
$rowContentTypes = mysqli_fetch_all($resultContentTypes, MYSQLI_ASSOC);

// Формируем страницу
$userName = 'Игорь';

$mainContent = include_template('add.php', ['postContentTypes' => $rowContentTypes, 'currentContentTypeId' => $currentContentTypeId, 'errors' => $errors]);
$layoutContent = include_template('layout.php', ['pageContent' => $mainContent, 'userName' => $userName, 'pageTitle' => 'Добавление публикации']);

print($layoutContent);
