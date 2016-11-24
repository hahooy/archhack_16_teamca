<?php
    include "utils.php";

    // Function that fetch patients.
    function get_records() {
        global $mysqli;

        $patients = array();

        $stmt = $mysqli->prepare("SELECT *
                                  FROM users
                                  WHERE username != ?;");
        if(!$stmt) {
            echo json_encode(array(
                    "success" => false,
                    "msg" => "SQL query preparation failed."
            ));
            exit;
        }

        $stmt->bind_param('s', $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($patient = $result->fetch_assoc()) {
            foreach ($patient as $key => $value) {
                $patient[$key] = $value;
            }
            array_push($patients, $patient);
        }
        $stmt->close();
        echo json_encode(array(
                "success" => true,
                "patients" => $patients
        ));
    }

    if (isset($_SESSION["username"])) {
        get_records();
    } else {
        echo json_encode(array(
                "success" => false,
                "msg" => "You have to login first."
        ));
    }
?>