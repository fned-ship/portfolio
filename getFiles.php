<!-- id : 1 -->
<?php
require_once 'databaseConnection.php';

$files = $database->prepare("SELECT * FROM files");
$files->execute();

foreach($files AS $file ){
    echo "<a href='" ."http://localhost/server/". $file["position"] . "' download>".$file["name"]."</a> <br>";
}
?>
