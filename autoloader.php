<?php

spl_autoload_register('autoloader');

function autoloader($classname): bool
{
    $validationPath = 'validation/';
    $repositoryPath = 'repository/';
    $modelsPath = 'models/';
    $extension = '.php';
    $fullValidationPath = $validationPath . $classname . $extension;
    $fullRepositoryPath = $repositoryPath . $classname . $extension;
    $fullModelsPath = $modelsPath . $classname . $extension;

    if (file_exists($fullValidationPath)) {
        require_once $fullValidationPath;
        return true;
    } else if (file_exists($fullRepositoryPath)) {
        require_once $fullRepositoryPath;
        return true;
    } else if (file_exists($fullModelsPath)) {
        require_once $fullModelsPath;
        return true;
    }

    return false;
}
