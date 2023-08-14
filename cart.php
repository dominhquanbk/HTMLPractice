<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['loggedin'])) {
  echo "<script>alert('Please Sign In');</script>";
  echo "<script>window.location.href = 'login.php';</script>";
    exit();
}  
 include('db_connect.php');
 $sql_cart = "SELECT * FROM cart WHERE owner = '" . $_SESSION["username"] . "'";
 $result_cart = mysqli_query($connect, $sql_cart);
    $doge_cart = mysqli_fetch_all($result_cart, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="cart.css">
    <title>Document</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><i class="fa-solid fa-cart-plus"></i> Cart</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#" onclick="navigateToMain()"><i class="fa-solid fa-house-user"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Welcome <?php echo $_SESSION['username'] ?></a>
        </li>
        
      </ul>
      
    </div>
  </div>
</nav>
<section class="h-100" style="background-color: #eee;">
  <div class="container h-100 py-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-10">

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-normal mb-0 text-black">Shopping Cart</h3>
          
          <div id="total-price"> $0.0</div>

        </div>
          
        

        

        
        <?php foreach($doge_cart as $doge): ?>
          <?php 
            $imageData = $doge['img']; 
            
      ?>
        <div class="card rounded-3 mb-4">
          <div class="card-body p-4">
            <div class="row d-flex justify-content-between align-items-center">
              <div class="col-md-2 col-lg-2 col-xl-2 cartimg">
              <img src="data:image/jpeg;base64,<?php echo base64_encode($imageData); ?>" alt="Image" />
              </div>
              <div class="col-md-3 col-lg-3 col-xl-3">
                <p class="lead fw-normal mb-2"><?php echo $doge["Name"]; ?></p>
                
              </div>
              
              <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
              <button class="btn btn-link px-2" onclick="decrementQuantity(<?php echo $doge['ID']; ?>), calculateTotalPrice()">
                        <i class="fas fa-minus"></i>
              </button>

                <input id="quantity-<?php echo $doge['ID']; ?>" min="1" name="quantity" value="<?php echo $doge['Amount']; ?>" type="number" class="form-control form-control-sm" disabled/>


                <button class="btn btn-link px-2" onclick="incrementQuantity(<?php echo $doge['ID']; ?>), calculateTotalPrice()">
                        <i class="fas fa-plus"></i>
                </button>
              </div>
              <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                <h5 class="mb-0">$<?php echo $doge["Price"]; ?></h5>
              </div>
              <div class="col-md-1 col-lg-1 col-xl-1 text-end">
              <a href="#!" class="text-danger" onclick="deletefromcartphp('<?php echo $doge["Name"]; ?>')"><i class="fas fa-trash fa-lg"></i></a>

              </div>
            </div>
          </div>
        </div>
        <?php endforeach;?>
          

        <div id="card">
          <div class="card-body d-flex justify-content-between">
            <button type="button" class="btn btn-primary btn-block btn-lg" onclick="navigateToMain()">Back to Main</button>
            <button type="button" class="btn btn-warning btn-block btn-lg" onclick="confirmbuying()">Proceed to Pay</button>
          </div>
        </div>


      </div>
    </div>
    
  </div>
  
</section>
<script>
    calculateTotalPrice();

    function decrementQuantity(productId) {
    const quantityInput = $(`#quantity-${productId}`);
    const quantity = parseInt(quantityInput.val());
    if (quantity > 1) {
      const newQuantity = quantity - 1;
      quantityInput.val(newQuantity);
      updateQuantity(productId, newQuantity);
    }
  }

  function incrementQuantity(productId) {
    const quantityInput = $(`#quantity-${productId}`);
    const quantity = parseInt(quantityInput.val());
    const newQuantity = quantity + 1;
    console.log(newQuantity);
    quantityInput.val(newQuantity);
    
    updateQuantity(productId, newQuantity,1);
  }
  function updateQuantity(productId, newQuantity){
    $.ajax({
              url: 'http://localhost/ajax/adjust_value.php',
              method: 'POST',
              data: {
                productId: productId,
                newQuantity:newQuantity,
                
              },
              dataType: 'json',
              success: function(response){
                
              },
              error: function(e){
                  alert(e.message)
                throw e
          }
            }
              
            )
  }
    function deletefromcartphp(name){
            $.ajax({
              url: 'http://localhost/ajax/deleteFromCart.php',
              method: 'POST',
              data: {
                NAME: name,
                
              },
              dataType: 'json',
              success: function(response){
                window.location.href='cart.php';
              },
              error: function(e){
                  alert(e.message)
                throw e
          }
            }
              
            )
        }
        function confirmbuying() {
  var cartItems = document.getElementsByClassName('card');
  if (cartItems.length === 0) {
    alert("Your cart is empty");
    return;
  }

  if (confirm("Are you sure about buying?")) {
    $.ajax({
      url: 'http://localhost/ajax/deleteAllFromCart.php',
      method: 'POST',
      dataType: 'json',
      success: function(response) {
        // Handle success response if needed
        alert("Thank you for buying");
        window.location.href = 'cart.php'; // Redirect to the cart page
      },
      error: function(e) {
        alert(e.message);
        throw e;
      }
    });
  }

}
function calculateTotalPrice() {
    var cards = document.getElementsByClassName('card');
    var totalPrice = 0;
    for (var i = 0; i < cards.length; i++) {
      var card = cards[i];
      var quantity = parseInt(card.querySelector('input[name="quantity"]').value);
      var price = parseFloat(card.querySelector('.col-md-3 h5').innerText.replace('$', ''));
      totalPrice += quantity * price;
    }
    document.getElementById('total-price').innerHTML = '<i class="fa-solid fa-cart-shopping"></i>' + '$' + totalPrice.toFixed(2);

  }
  function navigateToMain() {
    window.location.href = 'main1.php'; // Replace 'anotherpage.html' with the desired destination page
  }
</script>
</body>
</html>