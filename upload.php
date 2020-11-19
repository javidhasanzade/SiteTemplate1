<?php
    $dir = "img/";
    $file = $dir . $_FILES["cfile"]["name"];
    $ok = 1;
    $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));

    
        $check = getimagesize($_FILES["cfile"]["tmp_name"]);
        if($check) echo "File is an image - " . $check["mime"] . ".";
        else {
            echo "File is not an image";
            $ok = 0;
        }

        if($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif") {
            echo "Sorry, only JPG,JPEG,PNG,GIF files are allowed";
            $ok = 0;
        }
        if(file_exists($file)) {
            echo "Sorry, file already exists";
            $ok = 0;
        }
    
        if($_FILES["cfile"]["size"] > 1000000) {
            echo "Sorry, file is too large";
            $ok = 0;
        }

        if($ok) {
            if(move_uploaded_file($_FILES["cfile"]["tmp_name"],$file))
            echo "The file" .$_FILES['cfile']['name'], " has been uploaded";
            else echo "Sorry, file was not uploaded";
        } else echo "Sorry, file was not uploaded";
    
?>