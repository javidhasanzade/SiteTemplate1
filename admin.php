<?php
	session_start();
    if(!isset($_SESSION["user"])) { header("Location: login.php"); die(); } 
     
    require "db.php";
    $ok = 0;
    if(isset($_FILES["cfile"]) && $_FILES["cfile"]["size"] > 0) {
        include "upload.php";
        if($ok) {
            $sql = 'INSERT INTO `image` (`file`,`desc`) VALUES ("'.$_FILES["cfile"]["name"].'", "")';
            mysqli_query($conn,$sql);
            $image_id = mysqli_insert_id($conn);
        }
        
        //$cimage = $ok ? $_FILES["cfile"]["name"] : NULL;

    }   

    $lang = (isset($_REQUEST['lang'])) ? $_REQUEST['lang'] : '1';
    $page = (isset($_GET['page'])) ? $_GET['page'] : '';

    $action =  (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

    $lid =  (isset($_GET['lid'])) ? $_GET['lid'] : '';
    $lshort =  (isset($_GET['lshort'])) ? $_GET['lshort'] : '';
    $lfull =  (isset($_GET['lfull'])) ? $_GET['lfull'] : '';
    $lstat =  (isset($_GET['lstat'])) ? $_GET['lstat'] : '';

    $mid =  (isset($_GET['mid'])) ? $_GET['mid'] : '';
    $morder =  (isset($_GET['morder'])) ? $_GET['morder'] : '';
    $mname =  (isset($_GET['mname'])) ? $_GET['mname'] : '';
    $mstat =  (isset($_GET['mstat'])) ? $_GET['mstat'] : '';

    $menu =  (isset($_REQUEST['menu'])) ? $_REQUEST['menu'] : '';
    
    $ctitle =  (isset($_REQUEST['ctitle'])) ? $_REQUEST['ctitle'] : '';
    $ctext =  (isset($_REQUEST['ctext'])) ? $_REQUEST['ctext'] : '';
    $cimage = "";
    

    $laction = "ladd";
    $lbutton = "Add";
    $maction = "madd";
    $mbutton = "Add";

    $sql = "";
    switch ($action) {
        case "logout":
            session_unset(); session_destroy(); header("Location: login.php"); die();
            break;
        case "ladd":
            $sql = 'INSERT INTO `language` (`short`, `full`, `status`) VALUES ("'.$lshort.'", "'.$lfull.'", 0)';
            $lshort = '';
            $lfull = '';
            break;
        case "ldel":
            $sql = 'DELETE FROM `language` WHERE `id`='.$lid;
            break;
        case "lstat":
            $sql = 'UPDATE `language` SET `status`='.(1 - $lstat).' WHERE `id`='.$lid;            
            break;
        case "ledit":
            $laction = 'lupdate';
            $lbutton = 'Edit';      
            break;
        case "lupdate":
            $sql = 'UPDATE `language` SET `short`="'.$lshort.'", `full`="'.$lfull.'" WHERE `id`='.$lid; 
            $lshort = '';
            $lfull = '';
            break;
        case "madd":
            $sql = 'INSERT INTO `menu` (`lang`, `order`, `name`, `status`) VALUES ('.$lang.', '.$morder.', "'.$mname.'", 0)';
            $morder = '';
            $mname = '';
            break;
        case "mdel":
            $sql = 'DELETE FROM `menu` WHERE `id`='.$mid;
            break;
        case "mstat":
            $sql = 'UPDATE `menu` SET `status`='.(1 - $mstat).' WHERE `id`='.$mid;
            break;
        case "mupdate":
            $sql = 'UPDATE `menu` SET `order`='.$morder.' , `name`="'.$mname.'" WHERE `id`='.$mid;
            $morder = '';
            $mname = '';           
            break;
        case "cadd": 
            $cimgexp = $ok ? $image_id : 'NULL';           
            $sql = 'INSERT INTO `content` (`menu`,`title`,`text`,`image`) VALUES ('.$menu.',"'.$ctitle.'","'.$ctext.'",'.$cimgexp.')';
            echo $sql;
            break;
        case "cupdate":
            $cimgexp = $ok ? ', `image`='.$image_id : '';
            $sql = 'UPDATE `content` SET `title`="'.$ctitle.'", `text`="'.$ctext.'"'.$cimgexp.' WHERE `menu`='.$menu;
            echo $sql;
            break;
      }
      if ($sql != "") mysqli_query($conn, $sql);

      if ($menu != '') {
        $sql = 'SELECT * FROM `content` LEFT JOIN `image` ON `content`.`image`= `image`.`id` WHERE `menu`='.$menu; 
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $ctitle = $row["title"];
            $ctext = $row["text"];
            $caction = 'cupdate';
            $cimage = $row["file"];
        } else {
            $ctitle = '';
            $ctext = '';
            $caction = 'cadd';
            $cimage = '';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />
        <link rel="stylesheet" href="test.css" />
        <title>Site Admin</title>
        <script src="https://kit.fontawesome.com/30908b1d2e.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header class="bg-success p-2">
            <h3 class="text-white">CMS - Content Management System</h3>
            <p id="login">
                <?=$_SESSION["user"]?> 
                ( <a href="?action=logout">logout</a> ) 
            </p>
        </header>
        <main>
            <div class="container-fluid">
                <div class="row vh-80">
                    <nav class="col-4 text-light bg-secondary">
                    
                        <div>
                        <?php
                            $sql = "SELECT * FROM `language`";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($result)) {
                                $chck =  $row["status"] ? "check-" : "";
                                echo '
                                <div class="row">
                                    <div class="col-3 p-2 text-center">'.$row["short"].'</div>
                                    <div class="col-6 p-2"><a href="?lang='.$row["id"].'">'.$row["full"].'</a></div>
                                    <div class="col-3 p-2">
                                        <a href="?action=lstat&lid='.$row["id"].'&lstat='.$row["status"].'"><i class="far fa-'.$chck.'square"></i></a> | 
                                        <a href="?action=ledit&lid='.$row["id"].'&lshort='.$row["short"].'&lfull='.$row["full"].'"><i class="far fa-edit"></i></a> | 
                                        <a href="?action=ldel&lid='.$row["id"].'"><i class="far fa-trash-alt"></i></a>
                                    </div>
                                </div>
                                ';
                            }
                        ?>
                            <form>
                                <input name="action" value="<?=$laction?>" type="hidden" />
                                <?php 
                                    if($lid != '') echo' <input name="lid" value="'.$lid.'" type="hidden" /> '; 
                                ?>
                                <div class="form-row">
                                    <div class="form-group col-md-3"><input value="<?=$lshort?>" name="lshort" type="text" class="form-control shadow-none" /></div>
                                    <div class="form-group col-md-6"><input value="<?=$lfull?>" name="lfull" type="text" class="form-control shadow-none" /></div>
                                    <div class="form-group col-md-3"><input type="submit" value="<?=$lbutton?>" class="btn btn-light"/></div>
                                </div>
                            </form>
                        </div>
                        <hr />
                        <div>
                        <?php
                            $sql = "SELECT * FROM `menu` WHERE `lang`=".$lang;
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($result)) {
                                $chck =  $row["status"] ? "check-" : "";
                                echo '
                                <div class="row">
                                    <div class="col-3 p-2 text-center">'.$row["order"].'</div>
                                    <div class="col-6 p-2"><a href="?lang='.$lang.'&menu='.$row["id"].'">'.$row["name"].'</a></div>
                                    <div class="col-3 p-2">
                                        <a href="?lang='.$lang.'&action=mstat&mid='.$row["id"].'&mstat='.$row["status"].'"><i class="far fa-'.$chck.'square"></i></a> | 
                                        <a class="menuEdit"
                                            data-id="'.$row["id"].'" 
                                            data-order="'.$row["order"].'" 
                                            data-name="'.$row["name"].'" 
                                            data-toggle="modal" 
                                            data-target="#menuEdit"><i class="far fa-edit"></i></a> | 
                                        <a href="?lang='.$lang.'&action=mdel&mid='.$row["id"].'"><i class="far fa-trash-alt"></i></a>
                                    </div>
                                </div>
                                ';
                            }
                        ?>
                            <form>
                                <input name="lang" value="<?=$lang?>" type="hidden" />
                                <input name="action" value="<?=$maction?>" type="hidden" />
                                <?php 
                                    if($mid != '') echo'  <input name="mid" value="'.$mid.'" type="hidden" /> ';
                                ?>
                                <div class="form-row">
                                    <div class="form-group col-md-3"><input value="<?=$morder?>" name="morder" type="text" class="form-control shadow-none" /></div>
                                    <div class="form-group col-md-6"><input value="<?=$mname?>" name="mname" type="text" class="form-control shadow-none" /></div>
                                    <div class="form-group col-md-3"><input type="submit" value="<?=$mbutton?>" class="btn btn-light"/></div>
                                </div>
                            </form>

                            <!-- Modal -->
                            <div class="modal fade text-dark" id="menuEdit" tabindex="-1">
                                <div class="modal-dialog">
                                    <form class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <input name="lang" type="hidden" value="<?=$lang?>" />
                                            <input name="action" type="hidden" value="mupdate" />
                                            <input name="mid" type="hidden" id="menuId" />
                                            <div class="form-group">
                                                <label for="menuOrder">Order</label>
                                                <input name="morder" type="text" class="form-control shadow-none" id="menuOrder" />
                                            </div>
                                            <div class="form-group">
                                                <label for="menuName">Name</label>
                                                <input name="mname" type="text" class="form-control shadow-none" id="menuName" />
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>                                        
                                    </form>
                                </div>
                            </div>

                        </div>
                    </nav>
                    <section class="col-8">
                        <form method="POST" enctype="multipart/form-data">
                            <input name="lang" type="hidden" value="<?=$lang?>" />
                            <input name="action" type="hidden" value="<?=$caction?>" />
                            <input name="menu" type="hidden" value="<?=$menu?>" />
                            <div class="form-group pt-3">
                                <input name="ctitle" value="<?=$ctitle?>" type="text" class="form-control" placeholder="Title..." />
                            </div>
                            <div class="form-group">
                            <?php
                                if($cimage) echo '<img src="img/'.$cimage.'" width="70" />'
                            ?>
                                <input name="cfile" type="file" class="form-control-file" />
                            </div>
                            <div class="form-group">
                                <textarea name="ctext" class="form-control vh-50" rows="3"  placeholder="Text..."><?=$ctext?></textarea>
                            </div>
                            <div class="col-auto my-1">
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </main>
        <footer class="text-light bg-dark">Copyright &copy; 2020</footer>

        
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>tinymce.init({selector:'textarea'});</script>
        <script>
            $(".menuEdit").on("click", function() {
                let id = $(this).data('id');
                let order = $(this).data('order');
                let name = $(this).data('name');

                $("#menuId").val( id );
                $("#menuOrder").val( order );
                $("#menuName").val( name );
            });
        </script>
    </body>
</html>

