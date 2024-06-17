<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
    <link rel="stylesheet" href="reset.css">
</head>
<body>
<div class="full-screen-container">
<div class="update-container">
<?php 
require_once 'websiteURL.php';

if(!isset($_GET['code'])){
    echo '
    <h1 class="login-title" id="errorMsg" >write your email adress</h1>
    <form class="form" method="POST" >
        <div class="input-group" id="emailDiv" >
            <input type="email" placeholder="email" name="email" id="email" required >
        </div>
        <button type="submit"  name="resetPassword" class="update-button">send</button>
    </form>';
}else if(isset($_GET['code']) && isset($_GET['email'])){
    echo '
    <h1 class="login-title" id="errorMsg" >update password</h1>
    <form class="form" method="POST" >
        <div class="input-group" id="emailDiv" >
            <input type="password" placeholder="write a new password..." name="password" id="password" required >
        </div>
        <button type="submit"  name="newPassword" class="update-button">update</button>
    </form>';
}
?>


<?php 
if(isset($_POST['resetPassword']) ){
    require_once 'databaseConnection.php';
    
    $checkEmail = $database->prepare("SELECT email,security_code FROM users WHERE email = :email");
    $checkEmail->bindParam("email",$_POST['email']);
    $checkEmail->execute();

    if( $checkEmail->rowCount() > 0){
        $user = $checkEmail->fetchObject();
        require_once 'mail.php';
        $mail->addAddress($_POST['email']);
        $mail->Subject = "updating password";
        $mail->Body = '
        a link to update password
        <br>
        ' . '<a href="'.$webURL.'reset.php?email='.$_POST['email']. 
        '&code='.$user->security_code. '">'.$webURL.'reset.php?email='.$_POST['email']. 
        '&code='.$user->security_code.'</a>';
        
        $mail->setFrom("px.turing@gmail.com", "PX-Turing");
        $mail->send();
        echo '<h1 class="error-msg" style="color:hsl(var(--success-hsl));" id="errorMsg" >check your email</h1>' ;
    }else{
        echo '<h1 class="error-msg" style="color:hsl(var(--error-hsl));" id="errorMsg" >this email is not available</h1>' ;
    }
}
?>


<?php 

if(isset($_POST['newPassword'])){
    require_once 'databaseConnection.php';
    
   $updatePassword = $database->prepare("UPDATE users SET password = :password WHERE email = :email");
   $updatePassword->bindParam("password",$_POST['password']);
   $updatePassword->bindParam("email",$_GET['email']);
   
   if($updatePassword->execute()){
    echo '<h1 class="error-msg" style="color:hsl(var(--success-hsl));" id="errorMsg" >password has been successfully updated</h1>' ;
    echo "<script> location.replace('".$webURL."login.php') </script>";
   }else{
    echo '<h1 class="error-msg" style="color:hsl(var(--error-hsl));" id="errorMsg" >something went wrong</h1>' ;
   }
}

?>

</main>
</body>
</html>