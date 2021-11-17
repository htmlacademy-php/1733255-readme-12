<?php
require_once('Validator.php');

class PhotoValidator extends Validator
{
    public function validate($picFile): bool
    {
        $fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
        if (!empty($picFile['type']) && !in_array($picFile['type'], $fileTypes)) {
            $this->setError("Загрузите файл с расширением png, jpeg или gif");
            return false;
        }
        return true;
    }
}
