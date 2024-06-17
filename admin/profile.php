<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="update.css">
</head>
<body>

<div class="full-screen-container">
<div class="update-container">
<?php 
session_start();
if(isset($_SESSION['user'])){
    if($_SESSION['user']->role === "admin"){
        // $file=$_SESSION['user']->file;
        // if($file!=""){
        //     $imgSrc = "data:image/jpeg;base64,".base64_encode($file);
        // }else{
        //     $imgSrc = "../files/landscape2.jpg";
        // }
        $imgSrc="../files/landscape2.jpg";
        echo '
        <img src="'.$imgSrc.'" class="user-img" id="userImg"/>
        <form class="form" method="POST" >
            <div class="input-group" id="fNameDiv" >
                <input type="text" value="'.$_SESSION['user']->firstName.'" name="Fname" id="Fname" required >
            </div>
            <div class="input-group" id="lNameDiv" >
                <input type="text" value="'.$_SESSION['user']->lastName.'" name="Lname" id="Lname" required >
            </div>
            <div class="input-group" id="passwordDiv" >
                <input type="password" value="'.$_SESSION['user']->password.'" name="password" id="password" required >
            </div>

            <button type="submit" value="'.$_SESSION['user']->id.'" name="update" class="update-button">update</button>
            <a class="back-home" href="index.php">  back home </a>
        </form>';

        if(isset($_POST['update'])){
            require_once '../databaseConnection.php';
    
            $updateUserData = $database->prepare("UPDATE users SET firstName = :fName ,password = :password ,lastName=:lName WHERE id = :id ");
            $updateUserData->bindParam('fName',$_POST['Fname']);
            $updateUserData->bindParam('password',$_POST['password']);
            $updateUserData->bindParam('lName',$_POST['Lname']);
            $updateUserData->bindParam('id',$_POST['update']);

            if($updateUserData->execute()){
                echo '<h1 class="error-msg" style="color:hsl(var(--success-hsl));" id="errorMsg" >data has been successfully updated</h1>';
                $user =  $database->prepare("SELECT * FROM users WHERE id = :id ");
                $user->bindParam('id',$_POST['update']);
                $user->execute();   
                $_SESSION['user'] = $user->fetchObject();
                header("Refresh:0");
            }else{
                echo '<h1 class="error-msg" style="color:hsl(var(--error-hsl));" id="errorMsg" >something went wrong while updating data</h1>' ;
            }
        }
    }else{
        session_unset();
        session_destroy();
        header("location:http://localhost/server/login.php",true);  
    }
}else{
    session_unset();
    session_destroy();
    header("location:http://localhost/server/login.php",true);  
}

?>
</div>
</div>

</body>
</html>