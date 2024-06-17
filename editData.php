<!-- id : 3 -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<?php
require_once 'databaseConnection.php';



if(isset( $_GET['edit'])){
    $getPost = $database->prepare("SELECT * FROM posts WHERE id = :id");
    $getPost->bindParam("id",$_GET['edit']);
    $getPost->execute();

    foreach($getPost AS $data){
        echo '
            <div class="container"> 
                <form method="POST" > 
                    Name: <input class="form-control" type="text" name="title" value="'.$data['title'].'"/>
                    Price: <input class="form-control" type="text" name="content" value="'.$data['content'].'"/>
                    <button class="btn btn-dark mt-3" type="submit" name="update" value="' . $data['id'].'"> update</button>
                    <a class="btn btn-success mt-3" href="deleteData.php"> back</a>
                </form>
            </div>
        ';
    }

    if(isset($_POST['update'])){
        $update = $database->prepare("UPDATE posts SET title = :title , content = :content WHERE id = :id");
        $update->bindParam("title",$_POST['title']);
        $update->bindParam("content",$_POST['content']);
        $update->bindParam("id",$_POST['update']);
        $update->execute();
        header("Location: editData.php?edit=" . $_POST['update']);
    }
}
?>