<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\DownloadController;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database Connection
$dbConfig = (require __DIR__ . '/../app/config/database.php')();
Flight::register('db', 'PDO', $dbConfig, function ($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});

// Paths
Flight::set('flight.views.path', __DIR__ . '/../views');

// Routes
Flight::route('/', function () {
    Flight::redirect('/download-app');
});

Flight::route('/download-app', [new DownloadController(), 'index']);

Flight::start();
