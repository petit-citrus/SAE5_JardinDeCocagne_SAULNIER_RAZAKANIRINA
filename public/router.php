<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Servir les fichiers statiques existants
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}

// Indiquer à Symfony Runtime le véritable point d'entrée
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Transmettre la requête à Symfony
require __DIR__ . '/index.php';
