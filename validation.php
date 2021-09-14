<?php
require_once ('helpers.php');

function validateTitle($title) {
    if (empty($_POST[$title])) {
        return "Поле не заполнено";
    }
}

function validateContent($content) {
    if (empty($_POST[$content])) {
        return "Поле не заполнено";
    }
}

function validateAuthor($author) {
    if (empty($_POST[$author])) {
        return "Поле не заполнено";
    }
}

function validateUrl($url) {
    $urlValue = $_POST[$url];
    // Если тип контента - фото
    if (isset($_FILES['photo'])) {
        $fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
        if ($_FILES['photo']['error'] && empty($urlValue)) { // Отсутствует ссылка и файл
            return 'Загрузите фотографию';
        } elseif (!$_FILES['photo']['error']) { // Есть файл, игнорируем ссылку
            if (!in_array($_FILES['photo']['type'], $fileTypes)) {
                return 'Загрузите корректный тип файла';
            }
        } elseif ($_FILES['photo']['error']) { // Файла нет, проверяем ссылку
            if (!filter_var($urlValue, FILTER_VALIDATE_URL)) {
                return 'Введите корректную ссылку';
            } elseif (!file_get_contents($urlValue)) {
                return 'Не удалось загрузить файл';
            }
        }
    } elseif (empty($urlValue)) {
        return "Поле не заполнено";
    } elseif (!filter_var($urlValue, FILTER_VALIDATE_URL)) {
        return 'Введите корректную ссылку';
    } elseif ($_POST['contentType'] === 'video' && !is_bool(checkYoutubeUrl($urlValue))) {
        return checkYoutubeUrl($urlValue);
    }
}

