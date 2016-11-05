<?php
include 'utils.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Calendar</title>
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
            <a class="navbar-brand" href="index.php">CALENDAR</a>
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
        <div class="col-md-10">
            <div>
                <button id="last-month">last month</button>
                <button id="next-month">next month</button>
                <strong>
                <span id="year-month"></span>
                </strong>
            </div>
            <table id="calendar-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sunday</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < 6; $i++) {
                        echo '<tr>';
                        for ($j = 0; $j < 7; $j++) {
                            echo '<td></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>          
            </table>
        </div>
        <div class="col-md-2">
            <div class="block">
                <h4>Tags:</h4>
                <div id="tag-container"></div>
            </div>
            <div id="priority-container" class="block">
                <h4>Priority:</h4>
                <label for="low"><input type="checkbox" name="low" id="low" checked/> low priority</label>
                <label for="medium"><input type="checkbox" name="medium" id="medium" checked/> medium priority</label>
                <label for="high"><input type="checkbox" name="high" id="high" checked/> high priority</label>
                <label for="critical"><input type="checkbox" name="critical" id="critical" checked/> critical priority</label>
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

    <!-- Day Modal -->
    <div class="modal fade" id="day-modal" tabindex="-1" role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">today</h4>
          </div>
            <table class="table table-striped">
              <?php
              for ($i = 0; $i < 24; $i++) {
                printf('<tr>
                            <th class="small-width">%s:00</th><td class="large-width event"> </td>
                        </tr>', $i);
              }
              ?>
            </table>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="event-modal" tabindex="-1" role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add event</h4>
          </div>
          <div id="event-form" class="modal-body login">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <input type="text" class="form-control" name="title" placeholder="title" required>
            <input type="text" class="form-control" name="date" placeholder="date" required readonly>
            <form class="form-inline">
                <label>hour</label>
                <select name="hour" class="form-control" size="1">
                    <?php 
                    for ($i = 0; $i < 24; $i++) {
                        if ($i < 10) {
                            printf('<option>0%d</option>', $i);
                        } else {
                            printf('<option>%d</option>', $i);
                        }
                    }
                    ?>
                </select>
                <label>minute</label>
                <select name="minute" class="form-control" size="1">
                    <?php 
                    for ($i = 0; $i < 60; $i++) {
                        if ($i < 10) {
                            printf('<option>0%d</option>', $i);
                        } else {
                            printf('<option>%d</option>', $i);
                        }
                    }
                    ?>
                </select>
            </form>
            <form class="form-inline">
                <label>priority</label>
                <select name="priority" class="form-control" size="1">
                    <option>low</option>
                    <option>medium</option>
                    <option>high</option>
                    <option>critical</option>                                                            
                </select>
            </form>
            <input type="text" class="form-control" name="tag" placeholder="tag">            
            <textarea class="form-control" name="description" rows="5" placeholder="description"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="add-event" class="btn btn-primary">Add</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="edit-event-modal" tabindex="-1" role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit event</h4>
          </div>
          <div id="edit-event-form" class="modal-body login">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <input type="hidden" name="event-id">
            <input type="text" class="form-control" name="title" placeholder="title" required>
            <input type="text" class="form-control" name="date" placeholder="date" required readonly>
            <form class="form-inline">
                <label>hour</label>
                <select name="hour" class="form-control" size="1">
                    <?php 
                    for ($i = 0; $i < 24; $i++) {
                        if ($i < 10) {
                            printf('<option>0%d</option>', $i);
                        } else {
                            printf('<option>%d</option>', $i);
                        }
                    }
                    ?>
                </select>
                <label>minute</label>
                <select name="minute" class="form-control" size="1">
                    <?php 
                    for ($i = 0; $i < 60; $i++) {
                        if ($i < 10) {
                            printf('<option>0%d</option>', $i);
                        } else {
                            printf('<option>%d</option>', $i);
                        }
                    }
                    ?>
                </select>
            </form>
            <form class="form-inline">
                <label>priority</label>
                <select name="priority" class="form-control" size="1">
                    <option>low</option>
                    <option>medium</option>
                    <option>high</option>
                    <option>critical</option>
                </select>
            </form>
            <input type="text" class="form-control" name="tag" placeholder="tag">
            <textarea class="form-control" name="description" rows="5" placeholder="description"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="edit-event" class="btn btn-primary">Save</button>
            <button type="button" id="delete-event" class="btn btn-danger">Delete</button>            
          </div>
        </div>
      </div>
    </div>    
</body>
</html>
