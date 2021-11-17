<?php
require_once('helpers.php');
require_once('Validator.php');

class UrlValidator extends Validator
{
    public function validate($value): bool
    {
        // Если тип контента - фото
        if (isset($_FILES['photo'])) {
            $fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if ($_FILES['photo']['error'] && empty($value)) { // Отсутствует ссылка и файл
                $this->setError('Загрузите фотографию');
                return false;
            } elseif (!$_FILES['photo']['error']) { // Есть файл, игнорируем ссылку
                if (!in_array($_FILES['photo']['type'], $fileTypes)) {
                    $this->setError('Загрузите корректный тип файла');
                    return false;
                }
            } elseif ($_FILES['photo']['error']) { // Файла нет, проверяем ссылку
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->setError('Введите корректную ссылку');
                    return false;
                } elseif (!file_get_contents($value)) {
                    $this->setError('Не удалось загрузить файл');
                    return false;
                }
            }
        } elseif (empty($value)) {
            $this->setError("Поле не заполнено");
            return false;
        } elseif (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->setError('Введите корректную ссылку');
            return false;
        } elseif ($_POST['contentType'] === 'video' && !is_bool(checkYoutubeUrl($value))) {
            $this->setError(checkYoutubeUrl($value));
            return false;
        }
        return true;
    }
}
