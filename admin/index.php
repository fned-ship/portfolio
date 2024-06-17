<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>
<body>
    <!-- Image and text -->
<nav class="navbar navbar-light bg-light">

  <a class="navbar-brand" href="#" id='userName' >AAAA</a>

  <img src="../files/landscape1.jpg" style='border-radius:50%;' id='userImage' width="50" height="50" class="d-inline-block align-top" alt="" loading="lazy">

</nav>

<main class="container m-auto" style="max-width: 720px;">

<?php
session_start();

require_once '../databaseConnection.php';

if(isset($_SESSION['user'])){
    if($_SESSION['user']->role === "admin"){
        echo '<div class="shadow p-3 mb-1 bg-white rounded mt-5" >Welcome ' .$_SESSION['user']->firstName . '</div>';
        echo '<a  class="btn btn-light shadow w-100 mb-1" href="profile.php"> update profile </a>';
        echo "  
        <form method='POST' enctype='multipart/form-data'>
            <input class='form-control' type='file' name='file' accept='image/*' required/>
            <button class='btn btn-dark' type='submit' name='upload'>upload file</button>
        </form>";
        echo "<form> <button class='btn btn-danger w-100' type='submit' name='logout'>log out</button></form>";
        echo '<script>
        var userName = document.getElementById("userName");
        userName.innerText="' . $_SESSION['user']->firstName .' '. $_SESSION['user']->lastName .'";
        </script>';

        $fetchImg = $database->prepare("SELECT * FROM users WHERE id= :id");
        $id= $_SESSION['user']->id;
        $fetchImg->bindParam("id",$id);
        $fetchImg->execute();
        $user = $fetchImg->fetchObject();
        $file= $user->file;

        if($file != ''){
            $imgSrc = "data:image/jpeg;base64,".base64_encode($file);
            echo '<script>
            var userImg = document.getElementById("userImage");
            userImg.src="' . $imgSrc .'";
            </script>';
        }else{
            echo '<script>
            var userImg = document.getElementById("userImage");
            userImg.src="../files/landscape1.jpg";
            </script>';
        }
    }else{
        header("location:http://localhost/server/login.php",true); 
        die("");
    }
}else{
    header("location:http://localhost/server/login.php",true); 
    die(""); 
}


if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header("location:http://localhost/server/login.php",true); 
}

if(isset($_POST['upload'])){
    $file =file_get_contents($_FILES["file"]["tmp_name"]);
    //$position = "files/".$fileName;
    //move_uploaded_file($file,"files/".$fileName);

    $uploadFile = $database->prepare("UPDATE users SET file = :file WHERE id = :id ");
    $id=$_SESSION['user']->id;
    $uploadFile->bindParam("file",$file);
    $uploadFile->bindParam("id",$id);
    if($uploadFile->execute()){
        echo "<h3 style='color:black; background:green; padding:20px; margin:10px; ' >file uploaded successfully</h3>";
        //echo '<script>window.location.reload();</script>';
    }else{
        echo "<h3 style='color:black; background:red; padding:20px; margin:10px;' >failed to upload the file</h3>";
    }
}
?>
</main>
</body>
</html>