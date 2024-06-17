<?php
session_start();


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require_once '../databaseConnection.php';


$data = file_get_contents('php://input') ;
//$jsonData=$jsonD->name;
// $data=json_encode($jsonData);
#echo $jsonData;
#$jsonData='{"name":"youssef","age":17}';
// $data=$jsonData . strval($_SESSION['user']->id);
$send = $database->prepare("INSERT INTO usersmessages (jsondata) VALUES(:jsondata)");
$send->bindParam('jsondata', $data);
if($send->execute()) {
    $response = ['status' => 1, 'message' => 'Record created successfully.'];
} else {
    $response = ['status' => 0, 'message' => 'Failed to create record.'];
}
echo json_encode($response);     



// if(isset($_SESSION['user'])){
//     if($_SESSION['user']->role === "user"){
//         $method = $_SERVER['REQUEST_METHOD'];
//         switch($method) {
//             case "POST":
//                 $jsonData = file_get_contents('php://input') ;
//                 $send = $database->prepare("INSERT INTO cv4 (skills,cv4_id) VALUES(:skills,:cv4_id)");
//                 $send->bindParam('skills', $jsonData);
//                 $send->bindParam('cv4_id', $_SESSION['user']->id);

//                 if($send->execute()) {
//                     $response = ['status' => 1, 'message' => 'Record created successfully.'];
//                 } else {
//                     $response = ['status' => 0, 'message' => 'Failed to create record.'];
//                 }
//                 echo json_encode($response);
//                 break;
//         }
//     }else{
//         header("location:http://localhost/server/login.php",true); 
//         die("");
//     }
// }else{
//     header("location:http://localhost/server/login.php",true); 
//     die(""); 
// }