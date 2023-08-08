<?php
function connect($flag = true) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "restaurant_booking";

    try {
        if ($flag) {
            $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
        } else {
            $conn = new PDO("mysql:host=$servername", $username, $password);
        }
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
