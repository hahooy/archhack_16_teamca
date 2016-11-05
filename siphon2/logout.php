<?php
   include "utils.php";

   if (isset($_POST["logout"])) {
        logout();
    }

    function logout() {
        unset($_SESSION["username"]);
        echo json_encode(array(
            "success" => true
        ));
        exit;
    }
?>