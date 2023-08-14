<?php 

session_start();
include('../db_connect.php');
    $sql = 'SELECT * FROM doge_info';
    $searchminPrice =  $_POST["minprice"] ;
    $searchmaxPrice = $_POST["maxprice"];
    $searchOrigin = isset($_POST["origin"]) ? $_POST["origin"]:null;
    $data = array(); 
    // Add search conditions to the query if any option is selected
   
      if ($searchminPrice!="" || $searchOrigin||$searchmaxPrice!="") {
        $sql .= ' WHERE';

        // Add price condition
        if ($searchminPrice!=""||$searchmaxPrice!="") {
            
            if ($searchminPrice!="" and $searchmaxPrice=="") {
                $sql .= " Price >= '$searchminPrice'";
            } elseif ($searchminPrice!="" and $searchmaxPrice!="") {
                $sql .= " Price BETWEEN '$searchminPrice' AND '$searchmaxPrice'";
            } elseif ($searchminPrice=="" and $searchmaxPrice!="") {
                $sql .= " Price <= '$searchmaxPrice'";
            }
        }

        // Add origin condition
        if ($searchOrigin) {
            if ($searchminPrice!=""||$searchmaxPrice!="") {
                $sql .= ' AND';
            }
            $sql .= " Origin = '$searchOrigin'";
        }
        
    }
// Make the query and get the result
$result = mysqli_query($connect, $sql);

//Fetch the resulting rows as an array
$search = mysqli_fetch_all($result, MYSQLI_ASSOC);
if (count($search) === 0) {
    $search = "";
}
else {$data['result']=$search; 
$x=0;
while($x<count($search)){

    $data['result'][$x]['image']=base64_encode($data['result'][$x]['image']);
    $x++;
}  
}

echo json_encode($data);

?>