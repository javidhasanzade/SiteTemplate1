<?php
    $sql = "SELECT * FROM `content` WHERE `menu`=".intval($page);
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result); 

            echo '<h2>'.$row['title'].'</h2>';


            if($row["image"]) {
                $sql = "SELECT * FROM `image` WHERE `id`=".$row["image"];
                $result = mysqli_query($conn, $sql);
                $img = mysqli_fetch_assoc($result);
                echo '<img src="img/'.$img["file"].'" alt="'.$img["desc"].'" />';

            }
            echo $row["text"];

        
    } else {
        echo '<h2>Error 404</h2>';
        echo 'Page not found...';
    }

?>