<?php

require __DIR__ . '/../inc/config.php';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
$charset = mysqli_set_charset($conn, 'utf8');

$sql_details = array(
    "host" => DB_SERVER,
    "user" => DB_USER,
    "pass" => DB_PASSWORD,
    "db"   => DB_DATABASE,
);

try {
    $pdo = new PDO(
        'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE,
        DB_USER,
        DB_PASSWORD,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION),
    );
} catch (PDOException $e) {
    throw new PDOException($e);
}
