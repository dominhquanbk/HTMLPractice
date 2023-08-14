<?php 
date_default_timezone_set('Asia/Bangkok');
session_start();
include('../db_connect.php');
$data = array();//as a package to be delivered back to main1.php
$doge_name = $_POST['doge_name'];
$rating=$_POST['rating'];
$comment=$_POST['comment'];
$commentTime = date('Y-m-d H:i:s');
$product = "SELECT * FROM doge_info WHERE Name='$doge_name'";
    $query = mysqli_query($connect, $product);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
$insert_comment="INSERT INTO comment (Comment, Product_reviewed, Rating, User,Comment_Time) VALUES ('" .$comment . "', '" . $result[0]['Name'] . "', " . $rating . ",'" . $_SESSION["username"] . "','" .$commentTime . "')";
if (mysqli_query($connect, $insert_comment)) {
    $data['Name'] = $doge_name;
    $data['Rating'] = $rating;
    $data['Comment'] = $comment;
    $data['User']=$_SESSION["username"];
    $data['commentTime'] = $commentTime;
}

echo json_encode($data);
?>
