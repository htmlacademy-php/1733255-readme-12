<?php
require_once ('helpers.php');

checkSession();

$mainContent = include_template('feed.php');
$layoutContent = include_template('layout.php', prepareLayoutData($mainContent, 'Лента'));

print($layoutContent);
