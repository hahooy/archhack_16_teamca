// Register listeners.
function bind() {
    $('#login').click(login);
    $('#register').click(register);
    $('#logout').click(logout);
}

// Perform user login.
function login() {
    var username = $('#username').val();
    var password = $('#password').val();
    loginHelper(username, password);
}

function loginHelper(username, password) {
    $.ajax({
        type: 'POST',
        url: 'login.php',
        data: {
            'username': username,
            'password': password
        },
        success: function(data) {
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                $('#display-username').text(jsonData.username);
                $('#login-wrapper').addClass('hide');
                $('#logout-wrapper').removeClass('hide');
                $('input[name="token"]').val(jsonData.token);
                renderAll();               
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

// Perform user logout.
function logout() {
    $.ajax({
        type: 'POST',
        url: 'logout.php',
        data: {
            'logout': true
        },
        success: function(data) {
            console.log(data);
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                $('#login-wrapper').removeClass('hide');
                $('#logout-wrapper').addClass('hide');                
                renderAll();
            } else {
                alert('Failed to logout.');
            }
        }
    });
}

// Perform user registration.
function register() {
    var username = $('#register-form input[name="username"]').val();
    var password = $('#register-form input[name=password]').val();
    var password_ag = $('#register-form input[name=password-ag]').val();
    var email = $('#register-form input[name=email]').val();

    $.ajax({
        type: 'POST',
        url: 'register.php',
        data: {
            'username': username,
            'password': password,
            'password_ag': password_ag,
            'email': email
        },
        success: function(data) {
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                loginHelper(username, password);
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

function renderPatients() {
    $.ajax({
        type: 'GET',
        url: 'get_patients.php',
        data: {

        },
        success: function(data) {            
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                // Remove old data.
                $container = $('#patients-container');
                $container.text("");
                $container.html("<h4>Patients:</h4>");
                jsonData.patients.forEach(function(patient) {
                    $patient = $("<a class='event'>" + patient.username + "</a>");
                    $patient.click(renderRecord);
                    $container.append($patient);
                });
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

function renderRecord(e) {
    var username = $(this).text().trim();
    $.ajax({
        type: 'POST',
        url: 'get_patient_records.php',
        data: {
            "username": username
        },
        success: function(data) {
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                // Remove old data.
                $container = $('#patients-records');
                $container.text("");
                jsonData.records.forEach(function(record) {
                    $record = $("<div></div>");
                    $title = $("<h2>##</h2>".replace("##", record.title));
                    $time = $("<h4>##</h4>".replace("##", record.createtime));
                    $description = $("<p>##</p>".replace("##", record.description));
                    $originImage = $("<div class='col-md-6'><img src='##' alt='original image' width='450'></div>".replace("##", record.original_image));
                    $diagnosedImage = $("<div class='col-md-6'><img src='##' alt='original image' width='450'></div>".replace("##", record.image));
                    $row = $("<div class='row'></div>");
                    $record.append($title);
                    $record.append($time);
                    $record.append($description);
                    $row.append($originImage);
                    $row.append($diagnosedImage);
                    $record.append($row);
                    $container.append($record);
                });
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

function renderAll() {
    renderPatients();
}

$( document ).ready(function() {
    renderAll();
    bind();    
});







