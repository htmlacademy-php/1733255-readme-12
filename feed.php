<?php
require_once ('helpers.php');

session_start();

if ( empty($_SESSION) ) {
    header('Location: index.php');
}

$mainContent = include_template('feed.php');
$layoutContent = include_template('layout.php', parseLayoutData($mainContent, 'Лента'));

print($layoutContent);
