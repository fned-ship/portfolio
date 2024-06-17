<!-- id : 3 -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<?php
require_once 'databaseConnection.php';



$getItems = $database->prepare("SELECT * FROM posts");
$getItems->execute();

foreach($getItems AS $data){
  echo '
    <div  class="card text-white bg-success m-3" style="max-width: 18rem; float:left;">
        <div class="card-header">product - ' . $data['id']. '</div>
        <div class="card-body">
            <h5 class="card-title">' . $data['title'] . '</h5>
            <p class="card-text">' .$data['content']. ' </p>
            <form method="POST"> 
                <button class="btn btn-danger" type="submit" name="remove" value="'.$data['id'] .' ">delete</button>
            </from>
            <a href="editData.php?edit='. $data['id'].'" class="btn btn-light" type="submit" name="edit" >update</a>
        </div>
    </div>
  ';
}

if(isset($_POST['remove'])){
  $removePost = $database->prepare("DELETE FROM posts WHERE id = :id ");
  $getId = $_POST['remove'];
  $removePost->bindParam("id",$getId);
  $removePost->execute();
  header("Location: deleteData.php");
}
?>

