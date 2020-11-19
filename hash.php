<?php
//$hash = password_hash('mlnj62713',PASSWORD_DEFAULT) . "\n";
//echo $hash;
require "db.php";

$sql = 'SELECT * FROM `user` WHERE `status`=1';
$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($result)) {
    echo (password_verify('123qwe123', $row["password"])) ? 'Password is valid!' : 'Invalid password';
}
?>