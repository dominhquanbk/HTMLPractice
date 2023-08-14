<?php 
session_start();
include('db_connect.php');

$productID = $_GET['id'];
// Make the query and get the result
$sql = "SELECT * FROM doge_info WHERE ID = $productID";
$result = mysqli_query($connect, $sql);
// Fetch the resulting row as an array
$doge_name = mysqli_fetch_assoc($result);


$sql_comment = "SELECT * FROM comment WHERE Product_reviewed = '" . $doge_name['Name'] . "'";
$query_comment = mysqli_query($connect, $sql_comment);
$comment = mysqli_fetch_all($query_comment, MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $doge_name['Name']; ?> - View Product</title>
    <script src="demo.js"></script>
    <link rel="stylesheet" href="viewproduct.css">

</head>
<body>
<div id="account_success">Product has been added to cart</div>
<div id="comment_success">Comment has been posted</div>
<nav class="navbar navbar-expand-lg navbar-light bg-light ">
  <div class="container-fluid ">
    <a class="navbar-brand" href="#">View Product</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <?php if (isset($_SESSION['loggedin'])) :?>
        <li class="nav-item">         
            <a class="nav-link active" aria-current="page" href="main1.php"><i class="fa-solid fa-house-user"></i>Home</a>   
        </li>      
        <li class="nav-item">         
            <a class="nav-link active" aria-current="page" href="#">Welcome <?php echo $_SESSION['username'] ?></a>         
        </li>
        <?php  else: ?>
          <li class="nav-item">         
            <a class="nav-link active" aria-current="page" href="main1.php">Home</a>   
        </li> 
          <?php endif; ?>
          <li class="nav-item">         
            <a class="nav-link active" aria-current="page" href="category.php"><i class="fa-sharp fa-solid fa-tags"></i>Category</a>   
        </li>
        <li class="nav-item">
        <?php if (isset($_SESSION['loggedin'])) :?>
              <a class="nav-link btn btn-danger logout_button" href="logout.php" >Logout</a>

            <?php  else : ?>
              <a class="nav-link btn btn-primary login_button" href="login.php" >Log In</a>
            <?php endif;?>
      </li>
       
      </ul>
      
    </div>
  </div>
</nav>


        <div class="container mt-5">

        <div class="row">

        <div class="col-lg-6">
        <div class="row">
       
              <div id="demo" class="carousel slide col-lg-12 big_img" data-bs-ride="carousel">

                    <!-- Indicators/dots -->
                    <div class="carousel-indicators">
                      <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                      <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                      <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
                      <button type="button" data-bs-target="#demo" data-bs-slide-to="3"></button>
                    </div>

                    <!-- The slideshow/carousel -->
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                      <?php 
                            $imageData = $doge_name['image']; 
                            $imageBase64 = base64_encode($imageData);
                      ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($imageData); ?>" alt="Doge" class="d-block w-100">
                      </div>
                      <div class="carousel-item">
                        <img src="https://imgflip.com/s/meme/Doge.jpg" alt="Shibe" class="d-block w-100">
                      </div>
                      <div class="carousel-item">
                        <img src="https://akm-img-a-in.tosshub.com/sites/visualstory/stories/2022_12/story_16611/assets/2.jpeg?time=1672399209" alt="Cheems" class="d-block w-100">
                      </div>
                      <div class="carousel-item">
                        <img src="https://blockworks.co/_next/image?url=https://cms.blockworks.co/wp-content/uploads/2022/08/Shutterstock_2041211669.jpg&w=1920&q=75&webp=false" alt="Cheems" class="d-block w-100">
                      </div>
                    </div>

                    <!-- Left and right controls/icons -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                      <span class="carousel-control-next-icon"></span>
                    </button>
                  </div>
          <div class="col-lg-4 mini_img">
            <img src="https://imgflip.com/s/meme/Doge.jpg" class="img-fluid" alt="Product Image">
          </div>
          <div class="col-lg-4 mini_img" >
            <img src="https://akm-img-a-in.tosshub.com/sites/visualstory/stories/2022_12/story_16611/assets/2.jpeg?time=1672399209" class="img-fluid" alt="Product Image">
          </div>
          <div class="col-lg-4 mini_img" >
            <img src="https://blockworks.co/_next/image?url=https://cms.blockworks.co/wp-content/uploads/2022/08/Shutterstock_2041211669.jpg&w=1920&q=75&webp=false" class="img-fluid" alt="Product Image">
          </div>
        </div>
      </div>

          <div class="col-lg-6 fill-height position-relative">
            <h1><?php echo $doge_name['Name']; ?></h1>
            
            <h2>$<?php echo $doge_name['Price']; ?></h2>
            <p class="lead"><i class="fas fa-globe blue-icon"></i> Origin:<?php echo $doge_name['Origin']; ?></p>
            <p class="lead"><i class="fas fa-truck blue-icon"></i> Transportation Fee: $w0</p>
            <?php foreach ($doge_name as $column => $value) :?>
              <?php if($column!="image" && $value!="" &&$value!=NULL and $column!="Description" and $column!="Name" and $column!="Origin"and $column!="Price" and $column!="ID"): ?> 
                <p class="lead"><i class="fa-solid fa-star blue-icon"></i> <?php echo $column ?>: <?php echo $value  ?></p>
                <?php endif; ?>
              <?php endforeach; ?>

            
            <!-- <p class="lead"><i class="fas fa-dog blue-icon"></i> Suitable for those who love medium-sized doge</p>
            <p class="lead"><i class="fas fa-bone blue-icon"></i> Lifespan: Around <?php echo $doge_name['Lifespan']; ?> years</p>
            <p class="lead"><i class="fas fa-weight blue-icon"></i> Weight: Around <?php echo $doge_name['Weight']; ?> kg</p>
            <p class="lead"><i class="fas fa-utensils blue-icon"></i> Dietary Needs: Balanced diet with high-quality dog food</p>
            -->
            <!-- <div class="card-body d-flex justify-content-between position-absolute start-0 " style="top: 90%">
            <button type="button" class="btn btn-primary" onclick="addToCartPHP(<?php echo $doge_name['ID']; ?>)">Buy Product</button>
            
          </div> -->
          <div class="card-body d-flex justify-content-between">
            <button type="button" class="btn btn-primary" onclick="addToCartPHP(<?php echo $doge_name['ID']; ?>)">Buy Product</button>
            
          </div>
          </div>

        </div>

        <hr>

       

        </div>
        <div class="container ">
    <ul class="nav nav-tabs">
   
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#Description">Description</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#recentComments"> Comments</a>
      </li>
     
      

    </ul>
    

    <div class="tab-content">
      <div class="tab-pane fade show active" id="Description">  
          <div class="row d-flex justify-content-center">
          <p class="lead"><?php echo $doge_name['Description']; ?></p>
          </div>
      </div>
      <div class="tab-pane fade" id="recentComments">
        <div class="container  py-5">
          <div class="row d-flex justify-content-center">
            <div class="col-md-12 col-lg-10">
              
              <div class="card text-dark">
                <div class="card-body Commentbox p-4">
                    <?php if (isset($_SESSION['loggedin'])) :?>
                      <div class="d-flex flex-start w-100">
                          <img class="rounded-circle shadow-1-strong me-3" src="https://imgflip.com/s/meme/Doge.jpg"
                            alt="avatar" width="65" height="65" />
                          <div class="w-100">
                            <h5>Add a comment</h5>
                            <ul class="rating comment mb-3 d-flex" data-mdb-toggle="rating">
                              <li>
                                <i class="far fa-star fa-sm text-danger" title="Bad" onclick="selectRating(1)"></i>
                              </li>
                              <li>
                                <i class="far fa-star fa-sm text-danger" title="Poor" onclick="selectRating(2)"></i>
                              </li>
                              <li>
                                <i class="far fa-star fa-sm text-danger" title="OK" onclick="selectRating(3)"></i>
                              </li>
                              <li>
                                <i class="far fa-star fa-sm text-danger" title="Good" onclick="selectRating(4)"></i>
                              </li>
                              <li>
                                <i class="far fa-star fa-sm text-danger" title="Excellent" onclick="selectRating(5)"></i>
                              </li>
                            </ul>
                            <div class="form-outline">
                              <textarea class="form-control" id="textAreaExample" rows="4"></textarea>
                              <label class="form-label" for="textAreaExample">What is your view?</label>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                              <button type="button" class="btn btn-success" id="submitBtn">
                                Send <i class="fas fa-long-arrow-alt-right ms-1"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                        <?php endif;?>
                  <h4 class="mb-0">Recent comments</h4>
                  <p class="fw-light mb-4 pb-2">Latest Comments section by users</p>

                  <?php foreach($comment as $doge): ?>
                    <hr class="my-0" />
                    <div class="card-body p-4">
                      <div class="d-flex flex-start">
                        <img class="rounded-circle shadow-1-strong me-3" src="https://imgflip.com/s/meme/Doge.jpg"
                          alt="avatar" width="60" height="60" />
                        <div>
                          <h6 class="fw-bold mb-1"><?php echo $doge['User']; ?></h6>
                          <div class="d-flex align-items-center mb-3">
                            <p class="mb-0">
                            <?php echo $doge['Comment_Time']; ?>
                              <span class="badge bg-success">Approved</span>
                            </p>
                            
                          </div>
                          <ul class="rating mb-3 d-flex" data-mdb-toggle="rating">
                            <?php
                              $rating = $doge['Rating'];
                              for ($i = 1; $i <= 5; $i++) {
                                $starClass = ($i <= $rating) ? 'fas fa-star fa-sm text-danger' : 'far fa-star fa-sm text-danger';
                                $starTitle = ($i <= $rating) ? 'Rated' : 'Not Rated';

                                echo '<li><i class="' . $starClass . '" title="' . $starTitle . '"></i></li>';
                              }
                            ?>
                          </ul>
                          <p class="mb-0">
                            <?php echo $doge['Comment'];  ?>
                          </p>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
        
<script>
  function account_success(){
            var myDiv = $("#account_success");
            myDiv.fadeIn(500, function(){
                setTimeout(function(){
                    myDiv.fadeOut(500);
                }, 2000); // 3000 milliseconds = 3 seconds
            });
        };
        function comment_success(){
            var myDiv = $("#comment_success");
            myDiv.fadeIn(500, function(){
                setTimeout(function(){
                    myDiv.fadeOut(500);
                }, 2000); // 3000 milliseconds = 3 seconds
            });
        };
        function navigateToMain() {
    window.location.href = 'main1.php'; 
  }
  $(document).ready(function() {
    <?php 
                            $imageData = $doge_name['image']; 
                            $imageBase64 = base64_encode($imageData);
                      ?>
  var currentIndex = 0;
  var images = [
    "data:image/jpeg;base64,<?php echo base64_encode($imageData); ?>",
      "https://imgflip.com/s/meme/Doge.jpg",
    "https://akm-img-a-in.tosshub.com/sites/visualstory/stories/2022_12/story_16611/assets/2.jpeg?time=1672399209",
    "https://blockworks.co/_next/image?url=https://cms.blockworks.co/wp-content/uploads/2022/08/Shutterstock_2041211669.jpg&w=1920&q=75&webp=false"
  ];

  function showImage(index) {
    $('#ImageModal').attr('src', images[index]);
    currentIndex = index;
  }

  $('.mini_img').click(function() {
    var index = $(this).index();
    showImage(index);
    $('#imageModal').modal('show');
  });

  $('#prevImage').click(function() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    showImage(currentIndex);
  });

  $('#nextImage').click(function() {
    currentIndex = (currentIndex + 1) % images.length;
    showImage(currentIndex);
  });
});

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
        } 
      },
      error: function(e) {
        alert(e.message);
        throw e;
      }
    });
  }
}
let selectedRating = 0;

function selectRating(rating) {
  selectedRating = rating;
  const stars = document.querySelectorAll('.rating.comment i');
  stars.forEach((star, index) => {
    if (index < rating) {
      star.classList.remove('far');
      star.classList.add('fas');
    } else {
      star.classList.remove('fas');
      star.classList.add('far');
    }
  });
}

var name_php = '<?php echo $doge_name['Name']; ?>';
var ID_frontend='<?php echo $productID; ?>'
$(document).ready(function() {
    // Handle the click event on the submit button
    $('#submitBtn').click(function() {
 
      var doge_name=name_php;
      var comment = $('#textAreaExample').val();
      var rating = selectedRating; 
      // Create an object with the comment and rating data
     
      // Send the AJAX request
      if(comment==""){
        alert("comment cannot be empty");
      }
      else if(rating==0){
        alert("please rate");
      }
      else{
        $.ajax({
          method: 'POST',
        url: 'http://localhost/ajax/addToComment.php',
        data: {
            comment:comment,
            rating:rating,
            doge_name:doge_name

        },
        dataType: 'json',
        success: function(response) {
          
              // Assuming the response contains the new comment data
              comment_success()

              // Create the HTML for the new comment
              var newCommentHTML = '<hr class="my-0" />' +
                '<div class="card-body p-4">' +
                '<div class="d-flex flex-start">' +
                '<img class="rounded-circle shadow-1-strong me-3" src="https://imgflip.com/s/meme/Doge.jpg" alt="avatar" width="60" height="60" />' +
                '<div>' +
                '<h6 class="fw-bold mb-1">' + response.User + '</h6>' +
                '<div class="d-flex align-items-center mb-3">' +
                '<p class="mb-0">' +  response.commentTime  + '<span class="badge bg-success">Approved</span></p>' +
                '</div>' +
                '<ul class="rating mb-3 d-flex" data-mdb-toggle="rating">';
              
              // Append the rating stars to the HTML
              for (var i = 1; i <= 5; i++) {
                var starClass = (i <= response.Rating) ? 'fas fa-star fa-sm text-danger' : 'far fa-star fa-sm text-danger';
                var starTitle = (i <= response.Rating) ? 'Rated' : 'Not Rated';

                newCommentHTML += '<li><i class="' + starClass + '" title="' + starTitle + '"></i></li>';
              }

              newCommentHTML += '</ul>' +
                '<p class="mb-0">' + response.Comment + '</p>' +
                '</div>' +
                '</div>' +
                '</div>';

              // Append the new comment HTML to the recent comments section
              $('#recentComments .Commentbox').append(newCommentHTML);
},
        error: function(error) { 
          
          console.log(error);
        }
      });
      }
    });
  });
  </script>
    
   

    <div class="modal fade" id="imageModal" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="clicked_img d-flex justify-content-center align-items-center">
          <button type="button" class="btn btn-secondary" id="prevImage">&lt;</button>
          <img id="ImageModal" src="" class="img-fluid" alt="Product Image">
          <button type="button" class="btn btn-secondary" id="nextImage">&gt;</button>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>