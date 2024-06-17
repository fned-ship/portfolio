<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="signUp.css">
</head>
<body>
<div class="full-screen-container">
    <div class="login-container">
      <h1 class="login-title" id='errorMsg' >Welcome</h1>
      <form class="form" method="POST" >
        <div class="input-group" id="fNameDiv" >
            <input type="text" name="firstName" id="firstName" placeholder="first name" required >
            <span class="msg" id='fNameMsg' ></span>
        </div>
        <div class="input-group" id="lNameDiv" >
            <input type="text" name="lastName" id="lastName" placeholder="last name" required >
            <span class="msg" id='lNameMsg' ></span>
        </div>
        <div class="input-group" id="emailDiv" >
            <input type="email" name="email" id="email" placeholder="email" required >
            <span class="msg" id='emailMsg' ></span>
        </div>
        <div class="input-group" id="passwordDiv" >
            <input type="password" name="password" id="password" placeholder="password" required >
            <span class="msg" id='passwordMsg' ></span>
        </div>

        <button type="submit" name='signUp' class="sign-up-button">sign up</button>
      </form>
    </div>
</div>


<?php 
require_once 'databaseConnection.php';
require_once 'websiteURL.php';


if(isset($_POST['signUp'])){
    $checkEmail = $database->prepare("SELECT * FROM users WHERE email = :EMAIL");
    $email = $_POST['email'];
    $checkEmail->bindParam("EMAIL",$email);
    $checkEmail->execute();

    if($checkEmail->rowCount()>0){
        echo '<script>
            var email_div = document.getElementById("emailDiv");
            email_div.classList.toggle("error");

            var email_msg = document.getElementById("emailMsg");
            email_msg.innerText="email already used";
        </script>';
    }else{
        $firstName =$_POST['firstName'] ;
        $lastName =$_POST['lastName'] ;
        $password =sha1($_POST['password']) ;
        $email = $_POST['email'];

        $addUser = $database->prepare("INSERT INTO users(firstName,lastName,password,email,security_code,role) VALUES(:FNAME,:LNAME,:PASSWORD,:EMAIL,:SECURITY_CODE,'user')");
        $securityCode = md5(date("h:i:s"));
        $addUser->bindParam("SECURITY_CODE",$securityCode);
        $addUser->bindParam("FNAME",$firstName);
        $addUser->bindParam("LNAME",$lastName);
        $addUser->bindParam("PASSWORD",$password);
        $addUser->bindParam("EMAIL",$email);

        if($addUser->execute()){
            echo '<script>
            var errorMsg = document.getElementById("errorMsg");
            errorMsg.innerText="account successfully created";
            errorMsg.style.color="hsl(var(--success-hsl))";
            errorMsg.innerHTML="<br> check your email";
            </script>';

            require_once "mail.php";
            $mail->addAddress($email);
            $mail->Subject = 'security code';
            $mail->Body = '<h1>thank you for signing up!</h1>'
            . "<div>hit the link to continue the process<div>" . 
            "<a href='".$webURL."active.php?code=".$securityCode  . "'>
            " .$webURL."active.php?code=" .$securityCode . "</a>";
            $mail->setFrom("px.turing@gmail.com", "PX-Turing");
            $mail->send();
        }else{
            echo '<script>
            var errorMsg = document.getElementById("errorMsg");
            errorMsg.innerText="unexpected error";
            errorMsg.style.color="hsl(var(--error-hsl))"
            </script>';
        }
    }
}else{
    echo '<script>
        var errorMsg = document.getElementById("errorMsg");
        errorMsg.innerText="welcome";
        errorMsg.style.color="white";
    </script>';
}
?>
</body>
</html>
