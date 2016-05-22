<?php
header("Content-Type: text/html; charset=utf-8");
require_once "connect.php";

if ($conn->connect_error) {
    echo "Data Base connection error";
    die("No connection" . $conn->connect_error);
} else {
    if (isset($_POST['name'], $_POST['password'], $conn)) {
        $Uname = $_POST['name'];
        $UPassword = $_POST['password'];
        $request = "SELECT id, name, email FROM UsersTable WHERE name = ? AND password= ?";

        $stmt = $conn->prepare($request);
        $stmt->bind_param('ss', $_POST['name'], $_POST['password']);
        $stmt->execute();
        $stmt->bind_result($id, $name, $email);

        while ($stmt->fetch()) {
            echo "<p> id =$id name=$name email=$email <a href='delete_user.php?name=$name'>delete user</a> </p>";
            echo "
            <form method='POST' action='edit_user.php'>
                <input name='name' type='text'><br>
                <input name='email' type='text'><br>
                <input name='password' type='text'><br>
                <input type='submit'>
            </form>
            ";
        }
    }
}

?>

<html>
<body>
<form method="POST">
    <input name="name" type="text"><br>
    <input name="password" type="text"><br>
    <input type="submit">
</form>

</body>

</html>


