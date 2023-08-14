<?php
session_start();

include('db_connect.php');
// Write a query for the doge name
$sql = 'SELECT * FROM doge_info';
$search=NULL;


// Make the query and get the result
$result = mysqli_query($connect, $sql);


// Fetch the resulting rows as an array
$doge_name = mysqli_fetch_all($result, MYSQLI_ASSOC);
//FETCHING PAGES NUMBER
$page = isset($_GET['page']) ? $_GET['page'] : 1;
//FETCH PAGE LIMIT
$page_limit = ceil(mysqli_num_rows($result) / 6);
//FETCHING LOCATION OF THE START PRODUCT OF EACH PAGE
$page_starting_result = ($page - 1) * 6;
$query_page = "SELECT * FROM doge_info LIMIT $page_starting_result, 6";
$result_page = mysqli_fetch_all(mysqli_query($connect, $query_page), MYSQLI_ASSOC);

if (isset($_POST["submit"])) {
  $searchName = $_POST["searchname"];
  if ($searchName == "") {
    $search = NULL;
  } else {
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page_starting_result = ($page - 1) * 6;
    $SQLsearchName = "SELECT * FROM doge_info WHERE Name LIKE '%$searchName%'";
    $querySearchName = mysqli_query($connect, $SQLsearchName);
    $resultSearchName = mysqli_fetch_all($querySearchName, MYSQLI_ASSOC);
    if (empty($resultSearchName)) {
      $search = "N/A"; // Set $search to an empty array if no name is found
      $page_limit=0;
    } else {
      $search = $resultSearchName;
      $page_limit = ceil(mysqli_num_rows($querySearchName) / 6);
    }
  }
}
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
    <link rel="stylesheet" href="main1.css">
    <link rel="stylesheet" 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Document</title>
</head>
<body>
<div id="account_success"> Product has been added to cart <i class="fa-sharp fa-solid fa-badge-check"></i></div>
<nav class="navbar navbar-expand-lg navbar-light bg-light" >
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><i class="fa-solid fa-house-user"></i> Main</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
        <?php if (isset($_SESSION['loggedin'])) : ?>
        

            <a class="nav-link active" aria-current="page" href="#">Welcome <?php echo $_SESSION['username'] ?></a>
            <?php endif; ?>
          
        </li>
        <?php if(isset($_SESSION['loggedin'])): ?>
        <?php if($_SESSION['IsAdmin']): ?>
        <li class="nav-item">         
            <a class="nav-link active" aria-current="page" href="manager.php"><i class="fa-solid fa-list-check"></i>Manage Product</a>   
        </li>
        <?php endif; ?>
         <?php endif; ?>  
       
        <li class="nav-item">         
            <a class="nav-link active" aria-current="page" href="category.php"><i class="fa-sharp fa-solid fa-tags"></i>Category</a>   
        </li>
       
        <?php if(isset($_SESSION['loggedin'])): ?>
              <li class="nav-item">         
                  <a class="nav-link active" aria-current="page" href="cart.php"><i class="fa-solid fa-cart-shopping"></i>Cart</a>   
              </li>
        <?php endif; ?>
        
        <li class="nav-item">
            <?php if (isset($_SESSION['loggedin'])):  ?>
              <a class="nav-link btn btn-danger logout_button" href="logout.php" >Logout</a>

            <?php  else:  ?>
              <a class="nav-link btn btn-primary login_button" href="login.php" >Log In</a>
            <?php endif ?>
      </li>
       
      </ul>
      <form class="d-flex" method="POST" action="">
        <input id="searchname" name="searchname" class="form-control me-2" type="search" placeholder="Leave blank to show all product" aria-label="Search">
        <button class="btn btn-outline-success" type="submit" name="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<section style="background-color: #eee;">
  <div class="container py-5">
    <div class="row">
      <?php if($search==NULL): ?>
    <?php foreach($result_page as $doge): ?>
      <?php 
            $imageData = $doge['image']; 
            //$imageBase64 = base64_encode($imageData);
      ?>
      <div class="col-md-12 col-lg-4 mb-4 mb-lg-0">
        <div class="product card text-black">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($imageData); ?>" alt="Image">
           
          <div class="card-body">
            <div class="text-center mt-1">
              <h4 class="card-title"><?php echo $doge["Name"]; ?></h4>
              <h6 class="text-primary mb-1 pb-3">Price: $<?php echo $doge["Price"]; ?></h6>
              <h6 class="text-primary mb-1 pb-3">Origin: <?php echo $doge["Origin"]; ?></h6>
            </div>

           

            <div class="d-flex flex-row">
              <button type="button" class="btn btn-primary flex-fill me-1" data-mdb-ripple-color="dark" onclick="viewProduct(<?php echo $doge['ID']; ?>)" >
                View Detail
              </button>
              <button type="button" class="btn btn-danger flex-fill ms-1 " onclick="addToCartPHP(<?php echo $doge['ID']; ?>)">Buy now</button>
            </div>
          </div>
        </div>
      </div>
   
      <?php endforeach;?>
      <?php elseif($search!=NULL and $search!="N/A"): ?>
        <?php foreach($search as $doge): ?>
          <?php 
            $imageData = $doge['image']; 
            $imageBase64 = base64_encode($imageData);
      ?>
          <div class="col-md-12 col-lg-4 mb-4 mb-lg-0">
        <div class="product card text-black">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($imageData); ?>" alt="Image" />
          <div class="card-body">
            <div class="text-center mt-1">
              <h4 class="card-title"><?php echo $doge["Name"]; ?></h4>
              <h6 class="text-primary mb-1 pb-3">$<?php echo $doge["Price"]; ?></h6>
            </div>
            <div class="d-flex flex-row">
              <button type="button" class="btn btn-primary flex-fill me-1" data-mdb-ripple-color="dark" onclick="viewProduct(<?php echo $doge['ID']; ?>)" >
                View Detail
              </button>
              <button type="button" class="btn btn-danger flex-fill ms-1 " onclick="addToCartPHP(<?php echo $doge['ID']; ?>)">Buy now</button>
            </div>
          </div>
        </div>
      </div>
          <?php endforeach; ?>
          <?php else: ?>
            
        There is no result for your search :(

      <?php endif; ?>
      <div class="pagination">
      <ul class="pagination pagination-lg">
    <?php if ($page > 1 and $page_limit>1) : ?>
        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a></li>
    <?php endif; ?>

    <?php
    $firstPage = max(1, $page - 1);
    $lastPage = min($page_limit, $page + 1);

    if ($lastPage - $firstPage < 2) {
        if ($firstPage > 1) {
            $firstPage--;
        } elseif ($lastPage < $page_limit) {
            $lastPage++;
        }
    }

    if ($firstPage > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
        if ($firstPage > 2) {
            echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
        }
    }

    for ($i = $firstPage; $i <= $lastPage; $i++) {
        $activeClass = ($i == $page) ? 'active' : '';
        echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }

    if ($lastPage < $page_limit) {
        if ($lastPage < $page_limit - 1) {
            echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
        }
        echo '<li class="page-item"><a class="page-link" href="?page=' . $page_limit . '">' . $page_limit . '</a></li>';
    }
    ?>

    <?php if ($page < $page_limit) : ?>
        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a></li>
    <?php endif; ?>
</ul>
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
          account_success()
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