<?php
    include "utils.php";

    if (isset($_POST["username"]) && isset($_POST["password"])
        && isset($_POST["email"]) && isset($_POST["password_ag"])) {
        register_user($_POST["username"], $_POST["password"], $_POST["password_ag"], $_POST["email"]);
    }

    function register_user($username, $password, $password_ag, $email) {
        // Validate the inputs.
        if ($password != $password_ag) {
            // Ensure the passwords are correct.
            echo json_encode(array(
                "success" => false,
                "msg" => 'Your passwords do not match each other. Please check.'
            ));
            exit;
        }
        if (!is_valid_username($username)) {
            echo json_encode(array(
                "success" => false,
                "msg" => 'Invalid username, your username can only have alphanumeric characters and -,_.'
            ));
            exit;
        }
        if (!is_valid_password($password)) {
            echo json_encode(array(
                "success" => false,
                "msg" => 'Invalid password, your password can only have alphanumeric characters and #,!,@,-,_.'
            ));
            exit;
        }

        // Save user info in database.
        global $mysqli;
        $stmt = $mysqli->prepare("INSERT INTO users (username, pwd_hash, email_address) values (?, ?, ?)");
        if(!$stmt) {
            printf("Query Prep Failed: %s\n", $mysql->error);
            exit;
        }
        $password_encrypted = crypt($password);
        $stmt->bind_param('sss', $username, $password_encrypted, $email);
        $stmt->execute();
        $stmt->close();

        // Create a directory for this user on the file system.
        mkdir($GLOBALS["UPLOAD_DIR"].$username);
        exec("sudo chmod 777 ".$GLOBALS["UPLOAD_DIR"].$username);

        echo json_encode(array(
            "success" => true,
            "msg" => 'You have successfully registered with our website!'
        ));
        exit;
    }
?>