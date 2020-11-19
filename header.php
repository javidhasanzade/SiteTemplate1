<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body >
    <div class="container">
    <header></header>
    <nav>
        <p>
            <?php
                $sql = 'SELECT * FROM `language` WHERE `status`=1 ORDER BY `full`';
                $result = mysqli_query($conn,$sql);

                while($row = mysqli_fetch_assoc($result)) {
                    echo '<a href="?lang='.$row["id"].'">'.$row["short"].'</a> | ';
                }
            ?>
         </p>
        <ul>
            <?php
               $sql = 'SELECT * FROM `menu` WHERE `lang`='.$lang.' AND `status`=1 ORDER BY `order`';
               $result = mysqli_query($conn,$sql);
               while($row = mysqli_fetch_assoc($result)) {
                echo '<li><a href="?page='.$row["id"].'">'.$row["name"].'</a></li>';
                }
               
               


                
            ?>  
        </ul>
    </nav>
    <main>