<?php
    require "db.php";

    if (isset($_GET["lang"])) {
        $sql = "SELECT * FROM `language` WHERE `id`=".intval($_GET["lang"]);
        $lang = mysqli_num_rows(mysqli_query($conn,$sql))>0 ? $_GET["lang"] : 1;
        setcookie("lang",$lang, time() + (86400 * 30), "/");
    } else if(isset($_COOKIE["lang"])) {
        $lang = $_COOKIE["lang"];
    } else { 
        $lang = "1";
    }
 
   
   if(isset($_GET["page"])) $page = $_GET["page"];
   else {
       $sql = "SELECT `id` FROM `menu` WHERE `lang`=$lang ORDER BY `order` LIMIT 1";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($result);
        $page = $row[0];
    }


    include "header.php";
    include "main.php";
    include "footer.php";

    mysqli_close($conn);
?>