<?php 
use db\conn;
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    echo "Invalid request!";
    die;
}
switch ($_GET['type']) {
    case '1' :
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = conn::$conn->prepare($sql);
        $stmt->bind_param("s", $_GET['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            echo "success";
        } else {
            echo "fail";
        }
        break;
    default :
        echo "Invalid request!";
        die;
}