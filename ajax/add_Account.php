<?php
session_start(); // Start the session

include('../db_connect.php');

    $data = array(); 
    $name = $_POST["username"];
    $password = $_POST["password"];
  
    $queryAccount = "SELECT account FROM account";
    $resultAccount=mysqli_query($connect, $queryAccount);
    while ($row = mysqli_fetch_assoc($resultAccount)) {
        if($name==$row["account"])
        {
            $data['added'] = 0;
            echo json_encode($data);
            exit();
        }
    }
    $sql = "INSERT INTO account (account,password) VALUE ('$name', '$password')";
    if (mysqli_query($connect, $sql)) {
        $data['added'] = 1;

    }
    echo json_encode($data);

?>