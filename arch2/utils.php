<?php
ini_set("session.cookie_httponly", 1);
// Start the session.
session_start();

// Set up timezone.
date_default_timezone_set("America/Chicago");

// Connect to mysql.
$mysqli = new mysqli("localhost:3307", "root", "123456", "tracker");
 
if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

// Directory for uploading users' files
$UPLOAD_DIR = "uploads/";

// Make sure the username is alphanumeric with limited other characters.
function is_valid_username($username) {
    if(!preg_match('/^[\w_\-]+$/', $username)) {
        return False;
    } else {
        return True;
    }
}


// Make sure the password is alphanumeric with limited other characters.
function is_valid_password($password) {
    if(!preg_match('/^[#!@-_\w]+$/', $password)) {
        return False;
    } else {
        return True;
    }
}


// Ensure only authenticated users have access to the web page guarded by this helper function.
function is_login() {
    if (is_null($_SESSION["username"])) {
        // User not login yet, redirect to the login page.
        header("Location: index.php");
        exit;
    }
}

// Construct the full file path on the server for a particular filename and username.
// Perform necessary security check for the input username and filename.
function get_full_path($username, $filename) {
    $full_path = sprintf($GLOBALS['UPLOAD_DIR']."%s/%s", $username, $filename);
    // Ensure we don't overwrite an exsisting file.
    $increment = '';

    if (strpos($full_path, ".")) {
        $tokens = explode(".", $full_path);
        $full_path = $tokens[0];
        $extension = $tokens[1];
    } else {
        $extension = 'jpg';  // Default extension to jpg
    }
    while(file_exists($full_path . $increment . '.' . $extension)) {
        $increment++;
    }
    $full_path = $full_path . $increment . '.' . $extension;
    return $full_path;
}


// Import headers in HTML
function import_header() {
    echo
    '<!-- js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Custom style -->
    <link rel="stylesheet" href="css/style.css">';
}
?>