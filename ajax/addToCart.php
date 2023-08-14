<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['loggedin'])) {
  echo "<script>alert('Please Log In');</script>";
  echo "<script>window.location.href = 'login.php';</script>";
  exit();
}

include('../db_connect.php');
$data = array(); // as a package to be delivered back to main1.php
$id = $_POST['ID_NUMBER'];
$product = "SELECT * FROM doge_info WHERE ID=$id";
$query = mysqli_query($connect, $product);
$result = mysqli_fetch_all($query, MYSQLI_ASSOC);

if (mysqli_num_rows($query) > 0) {
    $data['added'] = 0; // Default value for when the product already exists
   

    // Check if the product already exists in the cart
    $existingProduct = "SELECT * FROM cart WHERE Name='" . $result[0]['Name'] . "' AND owner='" . $_SESSION["username"] . "'";
    $existingQuery = mysqli_query($connect, $existingProduct);
    $imageData = $result[0]['image']; // Assuming the image data is stored in $result[0]['image']
    $imageData = mysqli_real_escape_string($connect, $imageData); // Escape the image data to prevent SQL injection

    if (mysqli_num_rows($existingQuery) == 0) {
        // Product is not already in the cart, proceed to add it
        $product_cart = "INSERT INTO cart (img, Name, Price, owner, Amount) VALUES ('$imageData', '" . $result[0]['Name'] . "', " . $result[0]['Price'] . ",'" . $_SESSION["username"] . "',1)";
        if (mysqli_query($connect, $product_cart)) {
            $data['added'] = 1; // Successfully added the product
        }
    } else {
        // Product already exists in the cart, increment its amount
        $incrementAmount = "UPDATE cart SET Amount = Amount + 1 WHERE Name='" . $result[0]['Name'] . "' AND owner='" . $_SESSION["username"] . "'";
        if (mysqli_query($connect, $incrementAmount)) {
            $data['added'] = 1; // Successfully incremented the product amount
        }
    }
}

echo json_encode($data);
?>
