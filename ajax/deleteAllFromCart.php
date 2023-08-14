<?php
session_start();
include('../db_connect.php');

$sql_delete = "DELETE FROM cart WHERE owner = '" . $_SESSION["username"] . "'";
if (mysqli_query($connect, $sql_delete)) {
  $response = array('success' => true);
} else {
  $response = array('success' => false, 'error' => mysqli_error($connect));
}

header('Content-Type: application/json');
echo json_encode($response);
?>