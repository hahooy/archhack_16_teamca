<?php
    include "utils.php";

    // Function that fetch records.
    function get_records() {
        global $mysqli;

        $records = array();

        if (isset($_POST['existing_moments_id'])) {

        }

        $stmt = $mysqli->prepare("SELECT *
                                  FROM records
                                  WHERE username = ?
                                  ORDER BY createtime DESC;");
        if(!$stmt) {
            echo json_encode(array());
            exit;
        }

        $stmt->bind_param('s', $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($record = $result->fetch_assoc()) {
            foreach ($record as $key => $value) {
                $record[$key] = $value;
            }
            array_push($records, $record);
        }
        $stmt->close();

        echo json_encode($records);
    }

    if (isset($_SESSION["username"])) {
        get_records();
    } else {
        echo json_encode(array());
    }
?>