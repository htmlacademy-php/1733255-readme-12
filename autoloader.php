<?php

spl_autoload_register('autoloader');

function autoloader($classname): bool
{
    $validationPath = 'validation/';
    $repositoryPath = 'repository/';
    $extension = '.php';
    $fullValidationPath = $validationPath . $classname . $extension;
    $fullRepositoryPath = $repositoryPath . $classname . $extension;

    if (file_exists($fullValidationPath)) {
        require_once $fullValidationPath;
        return true;
    } else if (file_exists($fullRepositoryPath)) {
        require_once $fullRepositoryPath;
        return true;
    }

    return false;
}
