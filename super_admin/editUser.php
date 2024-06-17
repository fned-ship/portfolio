<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>
<body>
    <!-- Image and text -->
<nav class="navbar navbar-light bg-light">

    <a class="navbar-brand" href="#">
        youssef fned 
    </a>

    <img src="../files/landscape2.jpg" width="50" height="50" class="d-inline-block align-top" alt="" loading="lazy">

</nav>

<main class="container m-auto" style="max-width: 720px;">

<?php
session_start();
if(isset($_SESSION['user'])){
    if($_SESSION['user']->role === "super-admin"){
        require_once '../databaseConnection.php';

        if(isset( $_SESSION['userId'])){
            $user = $database->prepare("SELECT * FROM users WHERE id = :id");
            $user->bindParam('id', $_SESSION['userId']);
            $user->execute();
            $user = $user->fetchObject();

            echo '
            <form method="POST">
                <div class="p-3 shadow "> first name :  </div>
                <input class="form-control mb-1" type="text" name="fname" value="'.$user->firstName.'" required />
                <div class="p-3 shadow "> last name : </div>
                <input  class="form-control mb-1" type="text" name="lname" value="'.$user->lastName.'" required />
            ';
            echo '<select class="form-control mb-3" name="activated" > ';
            if($user->activated =="1"){
                echo ' <option value="' .$user->activated.' "></option>';
            }else{
               echo ' <option value="' .$user->activated.' "></option>';
            }
            echo '
            <option value="0">unactivate</option>
            <option value="1">activate</option>
            </select>

            <button class="w-100 btn btn-warning mt-1 mb-3" type="submit" name="update" value="'.$user->id.'">update</button>
            <a class="w-100 btn btn-light mt-1 mb-3" href="index.php">back home</a>
            </form>';
        }

        if(isset($_POST['update'])){
            $updateUser = $database->prepare("UPDATE users SET firstName = :fname , lastName= :lname ,activated=:activated WHERE id = :id ");
            $updateUser->bindParam('id', $_SESSION['userId']);
            $updateUser->bindParam("fname",$_POST['fname']);
            $updateUser->bindParam("lname",$_POST['lname']);
            $updateUser->bindParam("activated",$_POST['activated']);
            $updateUser->execute();
            header("location:editUser.php");
        }

        echo "<form> <button class='btn btn-danger w-100' type='submit' name='logout'> logout</button></form>";
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
?> 
</main>
</body>
</html>


