<?php
require_once('Validator.php');

class PhotoValidator extends Validator
{
    public function __construct(array $picFile)
    {
        $fileTypes = ['image/png', 'image/jpeg', 'image/gif'];
        if (!empty($picFile['type']) && !in_array($picFile['type'], $fileTypes)) {
            $this->setMessage("Загрузите файл с расширением png, jpeg или gif");
        }
    }
}
