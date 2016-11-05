<!DOCTYPE html>
<html>
<head>
    <title>Add Record</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1 class="center underline">Create Profile</h1>
    <p>Fill out the form below with your self detect information</p>
    <form action="upload.php" enctype="multipart/form-data" method="POST">
        <div>
            <label>username</label>
            <input type="text" name="username">
        </div>
        <div>
            <label>title</label>
            <input type="text" name="title">
        </div>
        <div>
            <label>description</label>
            <textarea name="description" rows="5" placeholder="description"></textarea>
        </div>
        <div>
            <label>diagnose picture</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
            <input name="uploadedfile" type="file" id="uploadfile_input">
        </div>
        </br>
        <div>
            <input type="submit" name="submit_profile" value="submit_profile">
        </div>
    </form>
</body>
</html>