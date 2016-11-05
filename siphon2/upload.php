<?php
    include "utils.php";

    if (isset($_POST['username']) && $_POST['username'] != '' 
        && isset($_POST['title']) && $_POST['title'] != '' 
        && isset($_POST['description']) && $_POST['description'] != ''
        && isset($_FILES['uploadedfile'])) {
        upload_record($_POST['username'], $_POST['title'], $_POST['description']);
    } else {
        echo json_encode(array(
            "success" => false,
            "msg" => "Something is missing, check the form you submit."
        ));
        exit;
    }

    function upload_record($username, $title, $description) {
        global $mysqli;
        // store image file.
        // Get the file path on server to store the uploaded file.
        $filename = basename($_FILES['uploadedfile']['name']);
        $full_path = get_full_path($username, $filename);
        // Store the file.
        if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path)){
            // Failed.
            echo json_encode(array(
                "success" => false,
                "msg" => "Failed to upload file."
            ));
            exit;
        }

        // path to images.
        $image_url = $full_path;
        $thumbnail_url = $full_path;

        // TODO: update detected area here.
        $area = 0;

        // store record info.
        $stmt = $mysqli->prepare("INSERT INTO records (title, description, image, thumbnail, username, area) VALUES (?, ?, ?, ?, ?, ?);");
        if(!$stmt) {
            echo json_encode(array(
                "success" => false,
                "msg" => sprintf("Query Prep Failed: %s\n", $mysql->error)
            ));
            exit;
        }
        $stmt->bind_param('sssssi', $title, $description, $image_url, $thumbnail_url, $username, $area);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
            "success" => true,
            "msg" => "Upload file successfully."
        ));
        exit;
    }
?>