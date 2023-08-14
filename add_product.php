<?php
include('db_connect.php');
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['loggedin'])) {
  echo "<script>alert('Please Sign In');</script>";
  echo "<script>window.location.href = 'login.php';</script>";
    exit();
}
// add a condition where collumn IsAdmin in table Account==1 and user has already logged in
else if (isset($_SESSION['loggedin']) && $_SESSION['IsAdmin'] != 1) {
    echo "<script>alert(\"You don't have permission to enter this page\");</script>";
    echo "<script>window.location.href = 'main1.php';</script>";
    exit();
  }

        if (isset($_POST["submit"])) {
          //input-lifespan
            $name = $_POST["input-name"];
            $price = $_POST["input-price"];
            $Image = $_FILES['input-image']['tmp_name'];  // Temporary location of the uploaded file
            $imageData = file_get_contents($Image);  // Read the contents of the file
            $imageData = mysqli_real_escape_string($connect, $imageData);  // Escape the data to prevent SQL injection
            $origin=$_POST["input-origin"];
            $description=$_POST["input-description"];
            $queryName = "SELECT Name FROM doge_info";
            $resultName = mysqli_query($connect, $queryName);
            if (mysqli_num_rows($resultName) >= 0) {
                $existingNames = [];
                while ($row = mysqli_fetch_assoc($resultName)) {
                    $existingNames[] = $row['Name'];
                }
                if (in_array($name, $existingNames)) {
                    echo "<script>alert('Name is already in DB');</script>";
                    
                } else{
                    // Find the first vacant ID in the database
            $query = "SELECT ID  FROM doge_info ORDER BY ID ASC";
            $result = mysqli_query($connect, $query);
        
            
            $availableID = 1; // Default ID if no vacant ID is found
        
            if (mysqli_num_rows($result) > 0) {
                $existingIDs = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $existingIDs[] = $row['ID'];
                }
        
                // Find the first vacant ID
                $maxID = max($existingIDs);
                for ($i = 1; $i <= $maxID + 1; $i++) {
                    if (!in_array($i, $existingIDs)) {
                        $availableID = $i;
                        break;
                    }
                }
            }
            
            if(!isset($_POST["dynamic-input"])){
              $sql = "INSERT INTO doge_info (ID, Name, Price,Origin,Description,Image) VALUE ('$availableID', '$name', '$price','$origin','$description','$imageData')";
        
            if (mysqli_query($connect, $sql)) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($connect);
            }
            }
            else if(isset($_POST["dynamic-input"])){
              $dynamic_input = $_POST['dynamic-input'];
              $first_time = true; 
              $input = array();
              foreach ($dynamic_input as $key => $value) {
                $column_id = 'dynamic-id-' . ($key); 
                $column = $_POST[$column_id];
                // echo  $column_id . "<br>";
                // echo  $column . "<br>";
                array_push($input,$value);
                // print_r($input);
                $query = "SELECT * FROM information_schema.columns WHERE table_name = 'doge_info' AND column_name = '$column'";
                $result = mysqli_query($connect, $query);
                
                if (mysqli_num_rows($result) == 0) {
                    // Column does not exist, so create it
                    $alterQuery = "ALTER TABLE doge_info ADD COLUMN $column VARCHAR(255)";
                    mysqli_query($connect, $alterQuery);
                }
                // Insert the dynamic value into the new column
                if($first_time)
                    {
                      $sql = "INSERT INTO doge_info (ID,Name, Price, Origin, Description, $column, Image) 
                      VALUES ('$availableID','$name', '$price', '$origin', '$description', '$input[$key]', '$imageData')";              
                      mysqli_query($connect,$sql);
                      $first_time=false;
                    }
                else
                {
                  $sql = "UPDATE doge_info SET $column = '" . strval($input[$key]) . "' WHERE ID = '$availableID'";
                  mysqli_query($connect,$sql);
                }
                
            }
            }
       
                }//end else
            
            }
            
            
        
            
        }
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="add_product1.css">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light ">
  <div class="container-fluid ">
    <a class="navbar-brand" href="#"><i class="fa-solid fa-square-plus"></i> Add Product</a>
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



        
            <div class="container">
            <h1 style="text-align:center">Add Doge into DB</h1>
            <form method="POST" action="" enctype="multipart/form-data" id="product-form">
                  <!-- Your existing input fields here -->

                  <div class="form-floating mb-3">
                    <i class="icon fa-solid fa-dog"></i>
                    <input class="form-control" type="text" name="input-name" id="name" placeholder="Name" required>
                    <label for="name"><b>Name</b></label>
                  </div>
                  <div class="form-floating mb-3">
                    <i class="icon fa-solid fa-sack-dollar"></i>
                    <input class="form-control" type="number" name="input-price" id="price" placeholder="Price" required>
                    <label for="price"><b>Price</b></label>
                  </div>
                  <div class="form-floating mb-3">
                    <i class="icon fa-solid fa-earth-asia"></i>
                    <input class="form-control" type="text" name="input-origin" id="origin" placeholder="origin" required>
                    <label for="origin"><b>Origin</b></label>
                  </div>
                  <div class="form-floating mb-3">
                    <i class="icon fa-solid fa-earth-asia"></i>
                    <input class="form-control" type="text" name="input-description" id="description" placeholder="description" required>
                    <label for="origin"><b>Description</b></label>
                  </div>
                  <div class="form-floating mb-3" id="image-form">
                    <i class="icon fa-solid fa-earth-asia"></i>
                    <input class="form-control" type="file" name="input-image" id="image" placeholder="image" required>
                    <label for="image"><b>Image Upload</b></label>
                  </div>

                  <div class="form-floating mb-3" id="Add-form">
                    <input class="form-control" type="text" name="dynamic-input-name" id="dynamic-input-name" placeholder="Enter Input Field Name">
                    <label for="dynamic-input-name"><b>Input Field Name</b></label>
                    <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" id="category-0" name="type" value="Text">
                      <label class="custom-control-label" for="category-0">Text</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" id="category-1" name="type" value="Number">
                      <label class="custom-control-label" for="category-1">Number</label>
                    </div>
                  </div>

                  <!-- Button to add new input field -->
                  <button type="button" id="add-input-btn" class="btn btn-secondary">Add New Input</button>

                  <!-- Submit button -->
                  <input type="submit" value="Submit" name="submit" id="input-submit" class="btn btn-primary">
                </form>
            </div>
                
    <script>
        function navigateToMain() {
    window.location.href = 'main1.php'; 
  }
 
  document.addEventListener('DOMContentLoaded', function() {
    const insertBeforeElement = document.getElementById('Add-form');
    const form = document.getElementById('product-form');
    const addInputBtn = document.getElementById('add-input-btn');
    const dynamicInputName = document.getElementById('dynamic-input-name');
    let inputCounter = 0;

    addInputBtn.addEventListener('click', function() {
      var originInput = document.querySelector('input[name="type"]:checked').value;
      const name = dynamicInputName.value.trim();

      if (name === '') {
        alert('Please enter a name for the dynamic input field.');
        return;
      }

      const newInputDiv = document.createElement('div');
      newInputDiv.classList.add('form-floating', 'mb-3','mt-3');

      const newInput = document.createElement('input');
      newInput.className = 'form-control';
      if (originInput === 'Text') newInput.type = 'text';
      else newInput.type = 'number';
      newInput.name = 'dynamic-input[]';
      newInput.id = 'dynamic-input-' + inputCounter;
      newInput.placeholder = name;
      newInput.required = true;

      const newInputLabel = document.createElement('label');
      newInputLabel.setAttribute('for', 'dynamic-input-' + inputCounter);
      newInputLabel.setAttribute('value',  name);
      newInputLabel.innerHTML = '<b>' + name + '</b>';
      

      const collumn = document.createElement('input');
      collumn.setAttribute('name', 'dynamic-id-' + inputCounter);
      collumn.setAttribute('value',  name);
      collumn.setAttribute('hidden', '');

      newInputDiv.appendChild(newInput);
      newInputDiv.appendChild(newInputLabel);
      newInputDiv.appendChild(collumn);

      insertBeforeElement.insertAdjacentElement('beforebegin', newInputDiv);

      dynamicInputName.value = '';
      inputCounter++;
    });
  });


  </script>
</body>
</html>