<?php 
//http://localhost/dashboard/
//http://localhost/server

# connect to database
require_once 'databaseConnection.php';

# check if connected to database
if($database){
    echo 'connected to databse';
}

# write sql code and execute it
// $sql = $database->prepare("CREATE TABLE test1(name varchar(255) NOT NULL,age int(10) NULL)");
// $sql->execute();

# a test to send data to databse
if(isset($_POST['send'])){
    $name = $_POST['name'];
    $age = $_POST['age'];
    // $addData = $database->prepare("
    //     INSERT INTO test1(name,age) VALUES('$name','$age');
    // ");

    $addData = $database->prepare("
        INSERT INTO test1(name,age) VALUES(:name,:age);
    ");
    $addData->bindParam('name',$name);
    $addData->bindParam('age',$age);
     
    if($addData->execute()){
        echo 'data added';
    }else{
        echo 'failed to add data';
    }
}

# fetch data from database (foreach)
$sqlfetch = $database->prepare("SELECT * FROM posts ;"); 
$sqlfetch->execute();
foreach($sqlfetch AS $row){
    echo '
    <div style="width:200px; margin:10px; display:flex; flex-direction:column; justify-content:center; align-items:center; background:crimson; border-radius:30px;" >
       <h2 style="margin:10px; color:black;" >'.$row['title'].'</h2>
       <h4 style="margin:10px; color:black;" >'.$row['content'].'</h4>
    </div>
    ';
}

# fetch data from database (array,abject)
$getUser = $database->prepare("SELECT * FROM visitors WHERE ID = 2 ");
$getUser->execute();
//var_dump($getUser->errorInfo());

//$getUser = $getUser->fetch(PDO::FETCH_ASSOC);
//echo "<h1>" . $getUser['username'] . "</h1>";
///// var_dump($getUser ); // array

$getUser = $getUser->fetchObject();
echo $getUser->password;
///// var_dump($getUser); // object

#rowCount
echo "<br> row :" . $sqlfetch->rowCount();

#columnCount
echo "<br> column :" . $sqlfetch->columnCount();

?>

<form method="POST" >
    name : <input type='text' name='name' />
    <br>
    age : <input type='text' name='age' />
    <br>
    <button type='submit' name='send' style="padding:10px; background:orange; color:black; border-radius:50px;" >send</button>
</form>