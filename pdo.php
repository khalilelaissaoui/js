<?php

    $hostName = "localhost";
    $userName = "alan";
    $password = "alan";
    $dbName = "js";

    try {
        $pdo = new PDO("mysql:host=$hostName;dbname=$dbName",$userName, $password);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

?>