<?php 
session_start();
include('../db_connect.php');
$data = array();
$id = $_POST['productId'];
$newQuantity = $_POST['newQuantity'];


// Update the quantity in the database
$updateQuery = "UPDATE cart SET Amount = $newQuantity WHERE ID = $id";
mysqli_query($connect, $updateQuery);
echo json_encode($data);



?>