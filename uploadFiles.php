<!-- id : 2 -->
<?php
require_once 'databaseConnection.php';

if(isset($_POST['upload'])){
    $fileType = $_FILES["file"]["type"];
    $fileSize = $_FILES["file"]["size"]/1024**2;
    $fileName = $_FILES["file"]["name"];
    $file =file_get_contents($_FILES["file"]["tmp_name"]);

    $uploadFile = $database->prepare("INSERT INTO files1(file,fileName,fileType,fileSize) VALUES(:file,:name,:type,:size)");
    $uploadFile->bindParam("file",$file);
    $uploadFile->bindParam("name",$fileName);
    $uploadFile->bindParam("type",$fileType);
    $uploadFile->bindParam("size",$fileSize);
    if($uploadFile->execute()){
        echo "<h3 style='color:black; background:green; padding:20px; margin:10px; ' >file uploaded successfully</h3>";
    }else{
        echo "<h3 style='color:black; background:red; padding:20px; margin:10px;' >failed to upload the file</h3>";
    }
}
?>


<form method="POST" enctype="multipart/form-data">
<input type="file" name="file" accept="image/*,video/*,audio/*,pdf/*" required/>
<button type="submit" name="upload">upload file</button>
</form>