<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 22-May-16
 * Time: 11:20
 */
//DELETE FROM somelog WHERE user = 'jcole'
//ORDER BY timestamp_column LIMIT 1;
require_once "connect.php";

if(isset($_GET['name'], $conn)){
    $request = "DELETE FROM UsersTable WHERE name = ?";
    $stmt = $conn->prepare($request);
    $stmt->bind_param('s', $_GET['name']);
    $stmt->execute();
}


?>