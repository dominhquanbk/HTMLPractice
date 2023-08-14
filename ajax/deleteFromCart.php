<?php 
session_start();
    include('../db_connect.php');
    $data = array();
    $name = $_POST['NAME'];
    $delete_item="DELETE FROM cart WHERE Name='$name' AND owner = '" . $_SESSION["username"] . "'";
    mysqli_query($connect, $delete_item); 
    echo json_encode($data)
?>