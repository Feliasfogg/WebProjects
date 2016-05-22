<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 22-May-16
 * Time: 11:47
 */
//UPDATE users SET first_name='{$userName['first_name']}',last_name='{$userName['last_name']}',
// image='{$userName['image']}' WHERE user_id='{$user_id}';";
require_once 'connect.php';
if(isset($_POST['name'], $_POST['email'], $_POST['password'])){
    $request = "UPDATE UsersTable SET name=?, email =?', 
    password =? WHERE name=?;";

    $stmt = $conn->prepare($request);
    $stmt->bind_param('ssss', $_POST['name'],$_POST['email'], $_POST['password'], $_POST['name']);
    $stmt->execute();
}
?>