<?php 
require_once 'websiteURL.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="logIn.css">
</head>
<body>
  <div class="full-screen-container">
    <div class="login-container">
      <h1 class="login-title" id="errorMsg" >Welcome</h1>
      <form class="form" method="POST" >
        <div class="input-group" id="emailDiv" >
          <input type="email" name="email" id="email" placeholder="email..." required >
          <span class="msg" id='emailMsg' >Valid email</span>
        </div>

        <div class="input-group" id="passwordDiv" >
          <input type="password" name="password" id="password" placeholder="password..." required >
          <span class="msg" id="passwordMsg" >Incorrect password</span>
        </div>

        <button type="submit" name="login" class="login-button">Login</button>
      </form>
      <a href="<?php echo $webURL ?>signUp.php" id="createacc" style="display:block; width:100%; margin-top: 20px;" >create new account</a>
      <a href="<?php echo $webURL ?>reset.php" style='margin-top:15px; display: block; text-align:center; color:hsl(var(--primary-hsl)); text-decoration:none;' class='forgot-password' >forgot password ?</a>
    </div>
  </div>

<?php
// echo '<script>
// var email_div = document.getElementById("emailDiv");
// email_div.classList.toggle("error");

// var email_msg = document.getElementById("emailMsg");
// email_msg.innerText="invalid email adress";

// var password_div = document.getElementById("passwordDiv");
// password_div.classList.toggle("success");

// var password_msg = document.getElementById("passwordMsg");
// password_msg.innerText="valid password";
// </script>';
if(isset($_POST['login'])){
  require_once 'databaseConnection.php';
  $password=sha1($_POST['password']);
  
  $login = $database->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
  $login->bindParam("email",$_POST['email']);
  $login->bindParam("password",$password);
  $login->execute();

  $loginE = $database->prepare("SELECT * FROM users WHERE email = :email ");
  $loginE->bindParam("email",$_POST['email']);
  $loginE->execute();

  $loginP = $database->prepare("SELECT * FROM users WHERE password = :password");
  $loginP->bindParam("password",$password );
  $loginP->execute();

  if($login->rowCount()===1){
    $user = $login->fetchObject();
    if($user->activated == 1){
      session_start();
      $_SESSION['email'] = $user->email;
      $_SESSION['password'] = $user->password;
      $_SESSION['fName'] = $user->firstName;
      $_SESSION['lName'] = $user->lastName;
      $_SESSION['user'] = $user;
      echo '<script>
      var email_div = document.getElementById("emailDiv");
      email_div.classList.remove("success");
      email_div.classList.remove("error");

      var password_div = document.getElementById("passwordDiv");
      password_div.classList.remove("success");
      password_div.classList.remove("error");
      </script>';
      if($user->role ==="user"){
        header("location: user/portfHeading.php",true);
      }else if($user->role ==="admin"){
        header("location: admin/index.php",true);
      }else if($user->role ==="super-admin"){
        header("location: super_admin/index.php",true);
      }
    }else{
      echo'<script>
      var error_msg = document.getElementById("errorMsg");
      error_msg.innerText="Please activate your account first, we have sent Verification code for your account to your e-mail";
      error_msg.style.color="hsl(var(--error-hsl))";
      </script>';
    }
  }elseif($loginE->rowCount()===1 && $loginP->rowCount()===0 ){
    echo '<script>
    var email_div = document.getElementById("emailDiv");
    email_div.classList.toggle("success");

    var email_msg = document.getElementById("emailMsg");
    email_msg.innerText="valid email adress";

    var password_div = document.getElementById("passwordDiv");
    password_div.classList.toggle("error");

    var password_msg = document.getElementById("passwordMsg");
    password_msg.innerText="incorrect password";
    </script>';
  }elseif($loginE->rowCount()===0 && $loginP->rowCount()>=1 ){
    echo '<script>
    var email_div = document.getElementById("emailDiv");
    email_div.classList.toggle("error");

    var email_msg = document.getElementById("emailMsg");
    email_msg.innerText="invalid email adress";

    var password_div = document.getElementById("passwordDiv");
    password_div.classList.toggle("success");

    var password_msg = document.getElementById("passwordMsg");
    password_msg.innerText="correct password";
    </script>';
  }else{
    echo '<script>
    var email_div = document.getElementById("emailDiv");
    email_div.classList.toggle("error");

    var email_msg = document.getElementById("emailMsg");
    email_msg.innerText="invalid email adress";

    var password_div = document.getElementById("passwordDiv");
    password_div.classList.toggle("error");

    var password_msg = document.getElementById("passwordMsg");
    password_msg.innerText="incorrect password";
    </script>';
  }
}
?>
</body>
</html>