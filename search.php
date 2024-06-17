<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<?php
require_once 'databaseConnection.php';

if(isset($_GET['btn-search'])){
    $SEARCH = $database->prepare("SELECT * FROM visitors WHERE username LIKE :value  OR  password LIKE :value");
    $SEARCH_VALUE = "%".$_GET['search']."%";
    $SEARCH->bindParam("value",$SEARCH_VALUE);
    $SEARCH->execute();

    foreach($SEARCH AS $data){
        echo '
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                <div class="card-header">' .$data['username'] .'</div>
                <div class="card-body">
                    <h5 class="card-title">' .$data['password'] .'</h5>
                </div>
            </div>
        ';
    }
}
?>

<form method="GET" >
    <input class="form-control " style="display:inline-block; width:300px; " type="text" name="search" placeholder="search ...." />
    <button class="btn btn-outline-warning" type="submit" name="btn-search">Search</button>
</form>

<!-- filter : 
https://www.youtube.com/watch?v=6MZUzOZt7Qw&list=PLMTdZ61eBnyrUuvaVAsnGBQdLuNDGlxEg&index=19
https://github.com/codershiyar/php-mysql/tree/master/LESSON%2018%20-%20%D8%A7%D9%84%D8%AF%D8%B1%D8%B3
-->
