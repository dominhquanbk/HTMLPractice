<?php 
    $connect = mysqli_connect('localhost', 'minhquan', 'qazwsx12', 'doge info', 3306);

    if (!$connect) {
        echo 'Connection error: ' . mysqli_connect_error();
    }
    
?>