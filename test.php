<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
    <h1>File Upload Form</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label>Select image to upload:</label>
        <input type="file" name="cfile">
        <input type="submit" value="Upload Image" name="submit">
    </form>
</body>
</html>