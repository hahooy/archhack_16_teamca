var currentDate = new Date();
var currentMonth = currentDate.getMonth() + 1;
var currentYear = currentDate.getFullYear();
var numRows = 6;
var numCols = 7;
var priorityMap = {
    'low': 'low-priority',
    'medium': 'medium-priority',    
    'high': 'high-priority',
    'critical': 'critical-priority'
};

// Register listeners.
function bind() {
    $('#login').click(login);
    $('#register').click(register);
    $('#logout').click(logout);
    $('#last-month').click(lastMonth);
    $('#next-month').click(nextMonth);
    $('#add-event').click(addEvent);
    $('#delete-event').click(deleteEvent);
    $('#edit-event').click(editEvent);
    $('#priority-container input').click(function() {
        renderCalendar(currentYear, currentMonth);
    });
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
                renderAll(currentYear, currentMonth);               
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
        url: 'login.php',
        data: {
            'logout': true
        },
        success: function(data) {
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                $('#login-wrapper').removeClass('hide');
                $('#logout-wrapper').addClass('hide');                
                renderAll(currentYear, currentMonth);
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

function renderTags() {
    $.ajax({
        type: 'GET',
        url: 'get_tags.php',
        data: {},
        success: function(data) {
            // Clean up old data.
            $('#tag-container').html('');
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                var $tagContainer = $('#tag-container');
                jsonData.tags.forEach(function(tag) {
                    if ($tagContainer.find('input[name="' + tag.tag + '"]').length === 0) {
                        var $tag = $('<label for="##"><input type="checkbox" name="##" id="##" checked/> ## </label>'.replace(/##/g, tag.tag));
                        $tagContainer.append($tag);
                    }
                });
                $('#tag-container input').click(function () {
                    renderCalendar(currentYear, currentMonth);
                });
                renderCalendar(currentYear, currentMonth);
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

// Render calendar and events.
function renderCalendar(year, month) {
    var firstDate = new Date(year, month - 1, 1);
    var lastDate = new Date(year, month, 0);
    $('#year-month').text(year + ', ' + month);



    // Get filters.
    var priorities = [];
    $.each($("#priority-container input:checked"), function(i, e) {
        priorities.push(e.name);
    });

    // Fetch tags.
    var tags = [];
    $.each($("#tag-container input:checked"), function(i, e) {
        tags.push(e.name);
    });

    // Fetch events from server.
    $.ajax({
        type: 'GET',
        url: 'get_event.php',
        data: {
            'year': currentYear,
            'month': currentMonth,
            'priorities': priorities,
            'tags': tags
        },
        success: function(data) {
            // Erase old calendar
            for (var r = 1; r <= numRows; r++) {
                for (var c = 1; c <= numCols; c++) {
                    $('#calendar-table tr:nth-child(' + r + ') td:nth-child(' + c + ')').text('');
                }
            }

            // Draw dates on calendar
            console.log('!!');
            var row = 1;
            for (var i = 1; i <= lastDate.getDate(); i++) {
                var d = new Date(year, month - 1, i);
                var col = d.getDay() + 1;
                var td = $('#calendar-table tr:nth-child(' + row + ') td:nth-child(' + col + ')');
                var $dayLable = $('<span class="day">' + i + '</span>');
                var today = new Date();
                if (currentMonth === today.getMonth() + 1 && currentYear === today.getFullYear() && i === today.getDate()) {
                    $dayLable.addClass('today');
                }
                var $addButton = $('<button class="btn-xs btn-info right-align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>');
                var $zoomButton = $('<button class="btn-xs btn-info right-align"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></button>');
                $addButton.click(presentEventForm);
                $zoomButton.click(zoom);
                td.append($dayLable);
                td.append($zoomButton);
                td.append($addButton);        
                if (col == 7) {
                    row += 1;
                }
            }
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                console.log('!');
                jsonData.events.forEach(function(event) {
                    var offset = firstDate.getDay();
                    var row = Math.floor((offset + parseInt(event.day) - 1) / 7) + 1;
                    var col = (offset + parseInt(event.day) - 1) % 7 + 1;
                    var $td = $('#calendar-table tr:nth-child(' + row + ') td:nth-child(' + col + ')');
                    var $event = $('<div class="event">' + 'title: ' + event.title + ', time: ' + event.datetime.split(' ')[1] + '</div>');
                    $event.addClass(priorityMap[event.priority]);
                    $event.click(function() {
                        var dateString = moment(new Date(currentYear, currentMonth - 1, event.day)).format('MM/DD/YYYY');
                        $('#edit-event-modal input[name="date"]').val(dateString);
                        $('#edit-event-modal input[name="event-id"]').val(event.pk_event_ID);
                        $('#edit-event-modal input[name="title"]').val(event.title);
                        $('#edit-event-modal textarea[name="description"]').val(event.description);
                        $('#edit-event-modal select[name="hour"]').val(event.hour < 10 ? '0' + event.hour : event.hour);
                        $('#edit-event-modal select[name="minute"]').val(event.minute < 10 ? '0' + event.minute : event.minute);                        
                        $('#edit-event-modal select[name="priority"]').val(event.priority);                        
                        $('#edit-event-modal input[name="tag"]').val(event.tag);                        
                        $('#edit-event-modal').modal({});
                    });
                    $td.append($event);
                });
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

// Go to the next month.
function nextMonth() {
    currentMonth = currentMonth % 12 + 1;
    if (currentMonth === 1) {
        currentYear += 1;
    }
    renderCalendar(currentYear, currentMonth);
}

// Go to the last month.
function lastMonth() {
    currentMonth -= 1;
    if (currentMonth === 0) {
        currentMonth = 12;
        currentYear -= 1;
    }
    renderCalendar(currentYear, currentMonth);
}

// Show modal for adding events.
function presentEventForm(e) {
    var day = parseInt($(this).parent().children('span.day').text());
    var dateString = moment(new Date(currentYear, currentMonth - 1, day)).format('MM/DD/YYYY');
    $('#event-modal input[name="date"]').val(dateString);
    $('#event-modal').modal({});
}

// Add an event to the calendar.
function addEvent(e) {
    var title = $('#event-modal input[name="title"]').val();
    var date = $('#event-modal input[name="date"]').val();
    var hour = $('#event-modal select[name="hour"]').val();
    var minute = $('#event-modal select[name="minute"]').val();                        
    var description = $('#event-modal textarea[name="description"]').val();
    var token = $('#event-modal input[name="token"]').val();
    var priority = $('#event-modal select[name="priority"]').val();                        
    var tag = $('#event-modal input[name="tag"]').val().trim().toLowerCase();
    var datetime = date + ' ' + hour + ':' + minute;

    if (title.trim() === '') {
        alert('title is required!');
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'add_event.php',
        data: {
            'title': title,
            'datetime': datetime,
            'description': description,
            'priority': priority,
            'tag': tag,
            'token': token
        },
        success: function(data) {
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                $('#event-modal').modal('hide');
                renderAll(currentYear, currentMonth);
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

// Delete an event from the calendar.
function deleteEvent() {
    var eventID = $('#edit-event-modal input[name="event-id"]').val();
    var token = $('#edit-event-modal input[name="token"]').val();
    $.ajax({
        type: 'POST',
        url: 'delete_event.php',
        data: {
            'id': eventID,
            'token': token
        },
        success: function(data) {
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                $('#edit-event-modal').modal('hide');
                renderAll(currentYear, currentMonth);
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

// Edit an event.
function editEvent() {
    var eventID = $('#edit-event-modal input[name="event-id"]').val();
    var title = $('#edit-event-modal input[name="title"]').val();
    var date = $('#edit-event-modal input[name="date"]').val();
    var hour = $('#edit-event-modal select[name="hour"]').val();
    var minute = $('#edit-event-modal select[name="minute"]').val();    
    var description = $('#edit-event-modal textarea[name="description"]').val();
    var token = $('#edit-event-modal input[name="token"]').val();
    var priority = $('#edit-event-modal select[name="priority"]').val();                        
    var tag = $('#edit-event-modal input[name="tag"]').val().trim().toLowerCase();    
    var datetime = date + ' ' + hour + ':' + minute;

    if (title.trim() === '') {
        alert('title is required!');
        return;
    }

    console.log(datetime);
    $.ajax({
        type: 'POST',
        url: 'edit_event.php',
        data: {
            'title': title,
            'datetime': datetime,
            'description': description,
            'priority': priority,
            'tag': tag,
            'token': token,
            'id': eventID
        },
        success: function(data) {
            $('#edit-event-modal').modal('hide');
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                renderAll(currentYear, currentMonth);
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

// Zoom in to a particular day.
function zoom(e) {
    var day = parseInt($(this).parent().children('span.day').text());
    $('#day-modal .modal-title').text(currentMonth + '/' + day + '/' + currentYear);

    $.ajax({
        type: 'GET',
        url: 'get_event.php',
        data: {
            'day': day,
            'month': currentMonth,
            'year': currentYear
        },
        success: function(data) {
            // clean up old data.
            for (var i = 1; i <= 24; i++) {
                var $eventCell = $('#day-modal tr:nth-child(' + i + ') td');
                $eventCell.removeClass();
                $eventCell.text('');
            }
            var jsonData = JSON.parse(data);
            if (jsonData.success) {
                jsonData.events.forEach(function(event) {
                    var $eventCell = $('#day-modal tr:nth-child(' + (parseInt(event.hour) + 1) + ') td');
                    $eventCell.text('title: ' + event.title + ', time: ' + event.datetime.split(' ')[1]);
                    $eventCell.addClass(priorityMap[event.priority]);
                });
                $('#day-modal').modal('show');
            } else {
                alert(jsonData.msg);
            }
        }
    });
}

function renderAll(year, month) {
    renderTags();
    //renderCalendar(year, month);
}

$( document ).ready(function() {
    renderAll(currentYear, currentMonth);
    bind();    
});







