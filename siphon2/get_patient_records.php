<?php
    include "utils.php";

    // Function that fetch records.
    function get_records($username) {
        global $mysqli;

        $records = array();

        $stmt = $mysqli->prepare("SELECT *
                                  FROM records
                                  WHERE username = ?
                                  ORDER BY createtime DESC;");
        if(!$stmt) {
            echo json_encode(array(
                    "success" => false,
                    "msg" => "SQL query preparation failed."
            ));
            exit;
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($record = $result->fetch_assoc()) {
            foreach ($record as $key => $value) {
                $record[$key] = $value;
            }
            array_push($records, $record);
        }
        $stmt->close();

        echo json_encode(array(
                "success" => true,
                "records" => $records
        ));
    }

    if (isset($_SESSION["username"]) && isset($_POST["username"])) {
        get_records($_POST["username"]);
    } else {
        echo json_encode(array(
                "success" => false,
                "msg" => "Parameters missing."
        ));
    }
?>