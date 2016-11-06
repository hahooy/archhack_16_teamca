<?php
    include "utils.php";

    if (isset($_SESSION['username']) &&
        isset($_POST['title']) && $_POST['title'] != '' 
        && isset($_POST['description']) && $_POST['description'] != ''
        && isset($_FILES['uploadedfile'])) {
        upload_record($_SESSION['username'], $_POST['title'], $_POST['description']);
    } else {
        echo json_encode(array(
            "success" => false,
            "msg" => sprintf("Something is missing, check the form you submit. username: %s; title: %s; description: %s; uploadedfile: %s.", $_SESSION['username'], $_POST['title'], $_POST['description'], $_FILES['uploadedfile'])
        ));
        exit;
    }

    function upload_record($username, $title, $description) {
        global $mysqli;
        // store image file.
        // Get the file path on server to store the uploaded file.
        $filename = basename($_FILES['uploadedfile']['name']);
        $full_path = get_full_path($username, $filename);

        // Store the original file in file system.
        if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path)){
            // Failed.
            echo json_encode(array(
                "success" => false,
                "msg" => "Failed to upload file."
            ));
            exit;
        }

        // TODO: execute mathmatica program and update detected area here
        $output = exec("sudo runuser -l ubuntu -c './segment.wl " . getcwd() . "/" . $full_path . "'");
        $area = 0;
        $tokens = explode(".", $full_path);
        $original_image_url = $full_path;
        $image_url = $tokens[0] . "_out." . $tokens[1];
        $thumbnail_url = $image_url;


        // store record info.
        $stmt = $mysqli->prepare("INSERT INTO records (title, description, original_image, image, thumbnail, username, area) VALUES (?, ?, ?, ?, ?, ?, ?);");
        if(!$stmt) {
            echo json_encode(array(
                "success" => false,
                "msg" => sprintf("Query Prep Failed: %s\n", $mysql->error)
            ));
            exit;
        }
        $stmt->bind_param('ssssssi', $title, $description, $original_image_url, $image_url, $thumbnail_url, $username, $area);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
            "success" => true,
            "msg" => "Upload file successfully."
        ));
        exit;
    }
?>