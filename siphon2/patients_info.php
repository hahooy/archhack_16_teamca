<?php
include 'utils.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Patients Info</title>
    <!-- js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
    <script src="js/main.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Custom style -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation bar. -->
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="patients_info.php">Patients Info</a>
        </div>
        <div class="navbar-right">
            <div class="navbar-form navbar-left">
            <?php 
            if (isset($_SESSION["username"]) && !is_null($_SESSION["username"])) {
                printf('
                <div id="login-wrapper" class="form-group hide">
                    <input type="text" id="username" class="form-control" name="username" placeholder="username">
                    <input type="password" id="password" class="form-control" name="password" placeholder="password">
                    <button id="login" class="btn btn-primary btn-sm navbar-btn">Login</button>
                    <button class="btn btn-default btn-sm navbar-btn" data-toggle="modal" data-target="#register-modal">Register</button>
                </div>
                <div id="logout-wrapper">
                    <a id="display-username"> %s </a>
                    <button id="logout" class="btn btn-sm btn-default navbar-btn">Logout</button>
                </div>', htmlentities($_SESSION['username']));
            } else {
                echo '
                <div id="login-wrapper" class="form-group">
                    <input type="text" id="username" class="form-control" name="username" placeholder="username">
                    <input type="password" id="password" class="form-control" name="password" placeholder="password">
                    <button id="login" class="btn btn-primary btn-sm navbar-btn">Login</button>
                    <button class="btn btn-default btn-sm navbar-btn" data-toggle="modal" data-target="#register-modal">Register</button>
                </div>
                <div id="logout-wrapper" class="hide">
                    <a id="display-username"> %s </a>
                    <button id="logout" class="btn btn-sm btn-default navbar-btn">Logout</button>
                </div>';
            }
            ?>
            </div>
        </div>
      </div>
    </nav>

    <!-- Main content -->
    <div>
        <div class="container">
            <div id="patients-records" class="col-md-10">
            </div>
            <div id="patients-container" class="col-md-2 block">
            </div>            
        </div>
    </div>

    <!-- Registration Modal -->
    <div class="modal fade" id="register-modal" tabindex="-1" role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Register</h4>
          </div>
          <div id="register-form" class="modal-body login">
            <input type="text" class="form-control" name="username" placeholder="username" required>
            <input type="password" class="form-control" name="password" placeholder="password" required>
            <input type="password" class="form-control" name="password-ag" placeholder="password again" required>
            <input type="email" class="form-control" name="email" placeholder="email (optional)" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="register" class="btn btn-primary">Register</button>
          </div>
        </div>
      </div>
    </div> 
</body>
</html>
