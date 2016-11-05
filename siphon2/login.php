<?php
    include "utils.php";

    if (isset($_POST["username"]) && isset($_POST["password"])) {
        login($_POST["username"], $_POST["password"]);
    }

    function login($username, $password) {
        // Validate input username
        if (!is_valid_username($username)) {
            echo "<script>alert('Invalid Username');</script>";
            return;
        }

        // Validate input password
        if (!is_valid_password($password)) {
            echo "<script>alert('Invalid Password');</script>";
            return;
        }
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT pwd_hash FROM users WHERE username=?");
        if(!$stmt) {
            printf("Query Prep Failed: %s\n", $mysql->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $pwd_hash = $stmt->get_result()->fetch_assoc()['pwd_hash'];
        $stmt->close();
        if (crypt($password, $pwd_hash) == $pwd_hash) {
            $_SESSION["username"] = $username;  // Create a session for this user.
            echo json_encode(array(
                "success" => true,
                "username" => $username
            ));
            exit;
        } else {
            echo json_encode(array(
                "success" => false,
                "msg" => "Login failed. Please check your username and password"
            ));
            exit;
        }
    }
?>