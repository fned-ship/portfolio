<!-- id : 2 -->
<?php
require_once 'databaseConnection.php';

$files = $database->prepare("SELECT * FROM files1 WHERE fileType = 'image/jpeg' ");
$files->execute();

foreach($files AS $file ){
    $getFile = "data:" . $file['fileType'] . ";base64,".base64_encode($file['file']);
    echo "<a href='" . $getFile. "' download>" .$file['fileName'] . "</a> <br>";
    echo '<img src="' .$getFile . '" width="300px" />';
}
?>