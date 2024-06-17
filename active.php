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
  <link rel="stylesheet" href="active.css">
</head>
<body>
<div class="full-screen-container">
    <div class="login-container">
      <h1 class="login-title" id='errorMsg' ></h1>
      <a class="login-button" id='login' href="<?php echo $webURL ?>login.php">login</a>
    </div>
</div>
<?php

if(isset($_GET['code'])){

    require_once 'databaseConnection.php';

    $checkCode = $database->prepare("SELECT SECURITY_CODE FROM users WHERE security_code = :SECURITY_CODE");
    $checkCode->bindParam("SECURITY_CODE",$_GET['code']);
    $checkCode->execute();
    if($checkCode->rowCount()>0){
        $update = $database->prepare("UPDATE users SET security_code = :NEWSECURITY_CODE , activated=true WHERE security_code = :SECURITY_CODE");
        $securityCode = md5(date("h:i:s"));
        $update->bindParam("NEWSECURITY_CODE",$securityCode);
        $update->bindParam("SECURITY_CODE",$_GET['code']);

        if($update->execute()){
            echo '<div class="alert alert-success" role="alert">Your account has been successfully verified</div>';
            echo '<a class="btn btn-warning" href="'.$webURL.'login.php">login</a>';

            echo '<script>
            var errorMsg = document.getElementById("errorMsg");
            errorMsg.innerText="Your account has been successfully verified";
            errorMsg.style.color="hsl(var(--success-hsl))";

            var login = document.getElementById("login");
            login.style.display="block";
            </script>';
        }
    }else{
        echo '<script>
            var errorMsg = document.getElementById("errorMsg");
            errorMsg.innerText="This code is no longer usable";
            errorMsg.style.color="hsl(var(--error-hsl))";

            var login = document.getElementById("login");
            login.style.display="none";
        </script>';
    }
}
?>

</body>
</html>