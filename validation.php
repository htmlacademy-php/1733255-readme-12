<?php
require_once ('helpers.php');

function validateTitle(string $title) :string
{
    if (empty($title)) {
        return "Поле не заполнено";
    } else {
        return '';
    }
}

function validateContent(string $content) :string
{
    if (empty($content)) {
        return "Поле не заполнено";
    } else {
        return '';
    }
}

function validateAuthor(string $author) :string
{
    if (empty($author)) {
        return "Поле не заполнено";
    } else {
        return '';
    }
}

function validateUrl(string $url) :string
{
    // Если тип контента - фото
    if (isset($_FILES['photo'])) {
        $fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
        if ($_FILES['photo']['error'] && empty($url)) { // Отсутствует ссылка и файл
            return 'Загрузите фотографию';
        } elseif (!$_FILES['photo']['error']) { // Есть файл, игнорируем ссылку
            if (!in_array($_FILES['photo']['type'], $fileTypes)) {
                return 'Загрузите корректный тип файла';
            } else {
                return '';
            }
        } elseif ($_FILES['photo']['error']) { // Файла нет, проверяем ссылку
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return 'Введите корректную ссылку';
            } elseif (!file_get_contents($url)) {
                return 'Не удалось загрузить файл';
            } else {
                return '';
            }
        } else {
            return '';
        }
    } elseif (empty($url)) {
        return "Поле не заполнено";
    } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
        return 'Введите корректную ссылку';
    } elseif ($_POST['contentType'] === 'video' && !is_bool(checkYoutubeUrl($url))) {
        return checkYoutubeUrl($url);
    } else {
        return '';
    }
}

function validateEmail(string $mail, array $existingEmails) :string
{
    if (empty($mail)) {
        return "Поле не заполнено";
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    } elseif (in_array($mail, $existingEmails)) {
        return "Пользователь с такой электронной почтой уже существует";
    } else {
        return '';
    }
}

function validateLogin(string $login) :string
{
    if (empty($login)) {
        return "Поле не заполнено";
    } else {
        return '';
    }
}

function validatePassword(string $password) :string
{
    if (empty($password)) {
        return "Поле не заполнено";
    } else {
        return '';
    }
}

function validatePasswordRepeat(string $password, string $passwordRepeat) :string
{
    if (empty($passwordRepeat)) {
        return "Поле не заполнено";
    } elseif ($password !== $passwordRepeat) {
        return "Пароли не совпадают";
    } else {
        return '';
    }
}

function validateUserPic(array $picFile) :string
{
    $fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
    if (!empty($picFile['type']) && !in_array($picFile['type'], $fileTypes)) {
        return "Загрузите файл с расширением png, jpeg или gif";
    } else {
        return '';
    }
}
