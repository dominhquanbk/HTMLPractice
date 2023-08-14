<?php 
    include('../db_connect.php');
    $data = array();
    $product="SELECT * FROM cart";
    $query= mysqli_query($connect,$product);
    $result=mysqli_fetch_all($query, MYSQLI_ASSOC);

    
    
    
    echo json_encode($data)
?>