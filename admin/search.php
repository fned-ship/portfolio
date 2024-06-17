<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>
<body>
<nav class="navbar navbar-light bg-light">

<a class="navbar-brand" href="#">
  Xturing
</a>

<img src="../files/landscape2.jpg" width="50" height="50" class="d-inline-block align-top" alt="" loading="lazy">

</nav>
    <main class="container " style="text-align: center;  max-width:760px;  margin:auto;" >
<?php 
session_start();
if(isset($_SESSION['user'])){
    if($_SESSION['user']->role === "admin"){
        echo '
        <form>
            <input class="form-control" type="text" name="search" placeholder="search for ...." />
            <button class="btn btn-warning w-100 mt-3" type="submit" name="searchBtn" >search</button>
        </form>';

        if(isset($_GET['searchBtn'])){
            require_once '../databaseConnection.php';
    
            $searchResult = $database->prepare("SELECT * FROM users WHERE firstName LIKE :fname OR lastName LIKE :lname OR email LIKE :email");
            $searchValue = "%" . $_GET['search'] . "%";
            $searchResult->bindParam("fname",$searchValue);
            $searchResult->bindParam("lname",$searchValue);
            $searchResult->bindParam("email",$searchValue);
            $searchResult->execute();
            echo '<table class="table mt-3">';
            echo  "<tr>";
            echo "<th> first name</th>";
            echo "<th> last name</th>";
            echo "<th> email</th>";
            echo  "<tr>";
            foreach($searchResult AS $result){
                echo  "<tr>";
                echo "<td> ".$result['firstName'] ."</td>";
                echo "<td> ".$result['lastName'] ."</td>";
                echo "<td> ".$result['email'] ."</td>";
                echo  "<tr>";
            }
            echo '</table>';
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

</main>
</body>
</html>
