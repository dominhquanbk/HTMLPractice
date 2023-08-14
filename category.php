<?php
session_start();

include('db_connect.php');
//set the access query
$sql = 'SELECT * FROM doge_info';
$result = mysqli_query($connect, $sql);
$doge_name = mysqli_fetch_all($result, MYSQLI_ASSOC);
// Set the base query

$sql_origin = 'SELECT DISTINCT Origin FROM doge_info';
$query_origin=mysqli_query($connect, $sql_origin);
$results_origin=mysqli_fetch_all($query_origin, MYSQLI_ASSOC);
$search=NULL;




// Close the connection
mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-...your-sha512-hash-here..." crossorigin="anonymous" />
    <link rel="stylesheet" href="category.css">
    <link rel="stylesheet" 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Document</title>
</head>
<body>
<div id="account_success"> Product has been added to cart </div>
<div id="value_error"> Min value can not exceed max value </div>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><i class="fa-sharp fa-solid fa-tags"></i> Category</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
        <li class="nav-item">
          
        <?php if (!isset($_SESSION['loggedin'])):  ?>
          <a class="nav-link active" aria-current="page" href="main1.php"><i class="fa-solid fa-house-user"></i>Home</a>
          <?php  else:  ?>
            <a class="nav-link active" aria-current="page" href="#">Welcome <?php echo $_SESSION['username'] ?></a>
            <?php  endif;?>
          
        </li>
        <?php if(isset($_SESSION['loggedin'])): ?>
        <li class="nav-item">         
            <a class="nav-link active" aria-current="page" href="main1.php"><i class="fa-solid fa-house-user"></i>Home</a>   
        </li>
        <?php endif; ?>
        <?php if(isset($_SESSION['loggedin'])): ?>
        <li class="nav-item">         
            <a class="nav-link active" aria-current="page" href="cart.php"><i class="fa-solid fa-cart-shopping"></i>Cart</a>   
        </li>
        <?php endif; ?>
        <li class="nav-item">
            <?php if (isset($_SESSION['loggedin'])) { ?>
              <a class="nav-link btn btn-danger logout_button" href="logout.php" >Logout</a>

            <?php } else { ?>
              <a class="nav-link btn btn-primary login_button" href="login.php" >Log In</a>
            <?php } ?>
      </li>
      
       
      </ul>
     
    </div>
  </div>
</nav>
<section style="background-color: #eee;">
  <div class="container py-5">
    <div class="row">
    <div class="col-md-12 col-lg-4 mb-4 mb-lg-0 ">
        <h3 class="mt-0 mb-5">Search by Tag</h3>
        <form>
  <h6 class="text-uppercase font-weight-bold mb-3">Origin</h6>
  <div class="mt-2 mb-2 pl-2">
      <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="category-0" name="origin" value="">
        <label class="custom-control-label" for="category-0">All</label>
      </div>
    </div>
  <?php $value = 0; ?>
  <?php foreach ($results_origin as $origin) : ?>
    <?php $value += 1; ?>
    <div class="mt-2 mb-2 pl-2">
      <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" id="category-<?php echo $value; ?>" name="origin" value="<?php echo $origin["Origin"]; ?>">
        <label class="custom-control-label" for="category-<?php echo $value; ?>"><?php echo $origin["Origin"]; ?></label>
      </div>
    </div>
  <?php endforeach ?>

  <div class="divider mt-5 mb-5 border-bottom border-secondary"></div>
  <h6 class="text-uppercase mt-5 mb-3 font-weight-bold">Price</h6>
 
  <div class="mt-2 mb-2 pl-2">
    <div class="custom-control custom-radio">
      Min Price
      <input type="number" class="custom-control-input" id="filter-size-2" name="minprice" value="" >
    </div>
  </div>
  <div class="mt-2 mb-2 pl-2">
    <div class="custom-control custom-radio">
      Max Price
      <input type="number" class="custom-control-input" id="filter-size-3" name="maxprice" value="">
    </div>
  </div>

  <div class="divider mt-5 mb-5 border-bottom border-secondary"></div>
  <button class="btn btn-lg btn-block btn-primary" type="button" onclick="updateResults()">Update Results</button>
</form>        
      </div>
    <div class="col-md-12 col-lg-8 mb-8 mb-lg-0">
            <div class="row" id="product-list">
            <?php foreach($doge_name as $doge): ?>
              <?php 
            $imageData = $doge['image']; 
            $imageBase64 = base64_encode($imageData);
          ?>
                <div class="col-md-12 col-lg-6 mb-6 mb-lg-0">
                  <div class="product card text-black">
                    <img src="data:image/jpg;base64,<?php echo base64_encode($imageData); ?>" class="card-img-top" alt="Product">
                    <div class="card-body">
                      <div class="text-center mt-1">
                        <h4 class="card-title"><?php echo $doge["Name"]; ?></h4>
                        <h6 class="text-primary mb-1 pb-3">$<?php echo $doge["Price"]; ?></h6>
                        <h6 class="text-primary mb-1 pb-3">Origin: <?php echo $doge["Origin"]; ?></h6>
                      </div>
                      <div class="d-flex flex-row">
                        <button type="button" class="btn btn-primary flex-fill me-1" data-mdb-ripple-color="dark" onclick="viewProduct(<?php echo $doge['ID']; ?>)">View Product</button>
                        <button type="button" class="btn btn-danger flex-fill ms-1" onclick="addToCartPHP(<?php echo $doge['ID']; ?>)">Buy now</button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
     
      
      
    </div>
  </div>
</section>
<script>
  
   function account_success(){
            var myDiv = $("#account_success");
            myDiv.fadeIn(500, function(){
                setTimeout(function(){
                    myDiv.fadeOut(500);
                }, 2000); // 3000 milliseconds = 3 seconds
            });
        };
        function value_error(){
            var myDiv = $("#value_error");
            myDiv.fadeIn(500, function(){
                setTimeout(function(){
                    myDiv.fadeOut(500);
                }, 2000); // 3000 milliseconds = 3 seconds
            });
        };
  function updateResults() {
    var originInput = document.querySelector('input[name="origin"]:checked');
    var minpriceInput = document.querySelector('input[name="minprice"]');
    var maxpriceInput = document.querySelector('input[name="maxprice"]');

  var origin = originInput ? originInput.value : null;
  var minprice = minpriceInput ? minpriceInput.value : "";
  if(minprice!="") minprice=parseInt(minprice);
  var maxprice = maxpriceInput ? maxpriceInput.value : "";
  if(maxprice!="") maxprice=parseInt(maxprice);
  

  if(minprice>=maxprice && (maxprice && minprice)){
    
value_error();
  }else{
    $.ajax({
      url: 'http://localhost/ajax/search_category.php',
      method: 'POST',
      data: {
        origin: origin,
        minprice:minprice,
        maxprice:maxprice
      },
      dataType: 'json',
      success: function(response) {
        let results = response.result
  var productList = document.getElementById('product-list');
  productList.innerHTML = '';
  
  if (results!=null) {
    results.forEach(function(product) {
     
      var productHTML = '<div class="col-md-12 col-lg-6 mb-6 mb-lg-0">' +
        '<div class="product card text-black">' +
        '<img src="data:image/jpeg;base64,' + product.image + '" alt="Image">'+
        '<div class="card-body">' +
        '<div class="text-center mt-1">' +
        '<h4 class="card-title">' + product.Name + '</h4>' +
        '<h6 class="text-primary mb-1 pb-3">$' + product.Price + '</h6>' +
        '<h6 class="text-primary mb-1 pb-3">Origin: ' + product.Origin + '</h6>' +
        '</div>' +
        '<div class="d-flex flex-row">' +
        '<button type="button" class="btn btn-primary flex-fill me-1" data-mdb-ripple-color="dark" onclick="viewProduct(' + product.ID + ')">' +
        'View Product' +
        '</button>' +
        '<button type="button" class="btn btn-danger flex-fill ms-1" onclick="addToCartPHP(' + product.ID + ')">Buy now</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
      
      productList.innerHTML += productHTML;
    });
  } else {
    productList.innerHTML = '<div class="col-md-12 col-lg-4 mb-4 mb-lg-0 text-center mt-1">There are no results for your search :(</div>';
  }
},
      error: function(e) {
        alert(e.message);
        throw e;
      }
    });}

}
     function gotoCart() {
            window.location.href = 'cart.php';
        }
     
       
         function viewProduct(productId) {
            window.location.href = 'viewproduct.php?id=' + productId;
        }

        function addToCartPHP(id) {
  var isLoggedIn = <?php echo isset($_SESSION['loggedin']) ? 'true' : 'false'; ?>;

  if (!isLoggedIn) {
    alert("Please login before adding this item to your cart");
  } else {
    $.ajax({
      url: 'http://localhost/ajax/addToCart.php',
      method: 'POST',
      data: {
        ID_NUMBER: id
      },
      dataType: 'json',
      success: function(response) {
        if (response.added == 1) {
          account_success();
        } else if (response.added == 0) {
          alert("Product is already in the cart");
        }
      },
      error: function(e) {
        alert(e.message);
        throw e;
      }
    });
  }
}

</script>
</body>
</html>