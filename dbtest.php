<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=scm', 'root', '');
    echo 'Connection successful!';
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}