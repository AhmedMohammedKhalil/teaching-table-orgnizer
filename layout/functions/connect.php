<?php

$dsn = 'mysql:host=localhost;dbname=teaching-table-database';
$user = 'root';
$pass = '';

try {
    $con = new PDO($dsn, $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $con;
}
catch(PDOException $e) {
    header('Location: ../../errors/databaseError.php');
    exit();
}