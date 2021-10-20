<?php
require_once ('helpers.php');
require_once('Validator.php');

class UrlValidator extends Validator
{
    public function __construct(string $url)
    {
        // Если тип контента - фото
        if (isset($_FILES['photo'])) {
            $fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if ($_FILES['photo']['error'] && empty($url)) { // Отсутствует ссылка и файл
                $this->setMessage('Загрузите фотографию');
            } elseif (!$_FILES['photo']['error']) { // Есть файл, игнорируем ссылку
                if (!in_array($_FILES['photo']['type'], $fileTypes)) {
                    $this->setMessage('Загрузите корректный тип файла');
                }
            } elseif ($_FILES['photo']['error']) { // Файла нет, проверяем ссылку
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    $this->setMessage('Введите корректную ссылку');
                } elseif (!file_get_contents($url)) {
                    $this->setMessage('Не удалось загрузить файл');
                }
            }
        } elseif (empty($url)) {
            $this->setMessage("Поле не заполнено");
        } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->setMessage('Введите корректную ссылку');
        } elseif ($_POST['contentType'] === 'video' && !is_bool(checkYoutubeUrl($url))) {
            $this->setMessage(checkYoutubeUrl($url));
        }
    }
}
