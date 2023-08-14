<?php 
session_start();
include('db_connect.php');
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

$productID = $_GET['id'];
// Make the query and get the result
$sql = "SELECT * FROM doge_info WHERE ID = $productID";
$result = mysqli_query($connect, $sql);
// Fetch the resulting row as an array
$doge_name = mysqli_fetch_assoc($result);
$old_name=$doge_name['Name'];
$old_price=$doge_name['Price'];
$old_image=$doge_name['image'];
$old_origin=$doge_name['Origin'];

//description-to-post

if (isset($_POST["submit"])) {
    
    
    $name=$_POST["Name-to-post"];
    $Price=$_POST["Price-to-post"];
    $Origin=$_POST["Origin-to-post"];
    $Lifespan=$_POST["Lifespan-to-post"];
    $Weight=$_POST["Weight-to-post"];
    $Image = $_FILES['input-image']['tmp_name'];  // Temporary location of the uploaded file
   if($Image!=NULL)
    {$imageData = file_get_contents($Image);  // Read the contents of the file
    $imageData = mysqli_real_escape_string($connect, $imageData); } // Escape the data to prevent SQL injection
    $Description=$_POST["description-to-post"];
    $queryName = "SELECT Name FROM doge_info";
    $resultName = mysqli_query($connect, $queryName);
if (mysqli_num_rows($resultName) >= 0) {
    $existingNames = [];
    while ($row = mysqli_fetch_assoc($resultName)) {
        $existingNames[] = $row['Name'];
    }
    if (in_array($name, $existingNames) and $name!=$old_name ) {
        echo "<script>alert('Name is already in DB');</script>";
        
    }else if(!isset($_POST["dynamic-input"])) {
        if($Image!=NULL)
       { $updateDoges = "UPDATE doge_info SET Name = '$name', Price = $Price,Lifespan='" . strval($Lifespan) . "',Weight='" . strval($Weight) . "' ,Origin = '$Origin', image = '$imageData',Description='$Description' WHERE ID = $productID";
        $updateCart = "UPDATE cart SET Name = '$name', Price = $Price, img = '$imageData' WHERE Name = '$old_name'";
       
        $updateComment = "UPDATE comment SET Product_reviewed = '$name' WHERE Product_reviewed = '$old_name'";
       }
       else if($Image==NULL)
        {
            $updateDoges = "UPDATE doge_info SET Name = '$name', Price = $Price, Lifespan='" . strval($Lifespan) . "', Weight='" . strval($Weight) . "', Origin = '$Origin', Description='$Description' WHERE ID = $productID";
        $updateCart = "UPDATE cart SET Name = '$name', Price = $Price WHERE Name = '$old_name'";
       
        $updateComment = "UPDATE comment SET Product_reviewed = '$name' WHERE Product_reviewed = '$old_name'";
        }
        if(mysqli_query($connect, $updateDoges) and mysqli_query($connect, $updateCart) and mysqli_query($connect, $updateComment)) 
            {
                echo "<script>alert('Success');</script>";
            }
        
            
        

       
    }
    else if(isset($_POST["dynamic-input"])){

        $dynamic_input = $_POST['dynamic-input'];
        $first_time = true; 
        $input = array();
       
        foreach ($dynamic_input as $key => $value) {
          $column_id = 'dynamic-id-' . ($key); 
          $column = $_POST[$column_id];
          echo  $column_id . "<br>";
          echo  $column . "<br>";
          array_push($input,$value);
     
          if($Image!=NULL)
          { $updateDoges = "UPDATE doge_info SET Name = '$name', Price = $Price,Lifespan='" . strval($Lifespan) . "',
            Weight='" . strval($Weight) . "' ,Origin = '$Origin', 
            image = '$imageData',$column = '" . strval($input[$key]) . "',
            Description='$Description' WHERE ID = $productID";
           $updateCart = "UPDATE cart SET Name = '$name', Price = $Price, img = '$imageData' WHERE Name = '$old_name'";
          
           $updateComment = "UPDATE comment SET Product_reviewed = '$name' WHERE Product_reviewed = '$old_name'";
           if(mysqli_query($connect, $updateDoges) and mysqli_query($connect, $updateCart) and mysqli_query($connect, $updateComment)) 
            {
                echo "<script>alert('Success');</script>";
            }
          }
          else if($Image==NULL)
           
            {
               $updateDoges = "UPDATE doge_info SET Name = '$name', Price = $Price, Lifespan='" . strval($Lifespan) . "',
                Weight='" . strval($Weight) . "', Origin = '$Origin',$column = '" . strval($input[$key]) . "', 
                Description='$Description' WHERE ID = $productID";
           $updateCart = "UPDATE cart SET Name = '$name', Price = $Price WHERE Name = '$old_name'";
          
           $updateComment = "UPDATE comment SET Product_reviewed = '$name' WHERE Product_reviewed = '$old_name'";
           if(mysqli_query($connect, $updateDoges) and mysqli_query($connect, $updateCart) and mysqli_query($connect, $updateComment)) 
            {
                echo "<script>alert('Success');</script>";
            }
           }
          
          
          
          
          
      }
      }
    header('Location: ./Edit_product.php?id='.$productID);

    
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $doge_name['Name']; ?> - Edit Product</title>
    <link rel="stylesheet" href="Edit_product.css">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light ">
  <div class="container-fluid ">
    <a class="navbar-brand" href="#"><i class="fa-solid fa-pen-to-square"></i>Edit Product</a>
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
    <h1 style="text-align:center">Edit the Doge</h1>
        <div class="form-container">
                <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-floating mb-3">
                        
                        <input type="number" class="form-control"  id="ID"  value="<?php echo $doge_name['ID']; ?>" disabled>
                        <label for="ID"><b>ID</b></label>
                    </div>
                    <div class="form-floating mb-3">
                        
                        <input type="text" class="form-control" name="Name-to-post" id="name" placeholder="Enter the name" value="<?php echo $doge_name['Name']; ?>" required>
                        <label for="name"><b>Name</b></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="Price-to-post" id="price" placeholder="Enter the Price" value="<?php echo $doge_name['Price']; ?>"required>
                        <label for="price"><b>Price</b></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="Lifespan-to-post" id="Lifespan" placeholder="Enter the Lifespan" value="<?php echo $doge_name['Lifespan']; ?>"required>
                        <label for="Lifespan"><b>Lifespan</b></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="Weight-to-post" id="Weight" placeholder="Enter the Weight" value="<?php echo $doge_name['Weight']; ?>"required>
                        <label for="Weight"><b>Weight</b></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="Origin-to-post" id="origin" placeholder="Enter the Origin" value="<?php echo $doge_name['Origin']; ?>" required>
                        <label for="origin"><b>Origin</b></label>
                    </div>
                    <div class="form-floating mb-3">
                       
                        <input class="form-control" type="file" name="input-image" id="image" placeholder="image" >
                        
                        <label for="image"><b>Image Upload</b></label>
                     </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="description-to-post" id="description" placeholder="Enter the description" value="<?php echo $doge_name['Description']; ?>"required>
                        <label for="description"><b>Description</b></label>
                    </div>
                    <div class="form-floating mb-3" id="Add-form" hidden>
                    <input class="form-control" type="text" name="dynamic-input-name" id="dynamic-input-name" placeholder="Enter Input Field Name">
                    <label for="dynamic-input-name"><b>Input Field Name</b></label>
                  </div>

                  <!-- Button to add new input field -->
                  <button type="button" id="add-input-btn" class="btn btn-secondary">More</button>
                    <button type="submit" value="Submit" name="submit" class="btn btn-primary">Submit</button>    
                </form>
        </div>
    </div>     

    <?php 
    $columns = array_slice($doge_name, 8, null, true);
    $columnNames = array_keys($columns); // Extract the column names
    $columnValues = array_values($columns);//extract the column values
    
    // Convert the column names to a JavaScript array
    $columnNamesJson = json_encode($columnNames);
    $columnValuesJson = json_encode($columnValues);
    ?>

<!-- $columns = array_slice($doge_name, 7, null, true); // Extract columns starting from the 8th column

foreach ($columns as $column => $value) {
    // Perform operations on each column
    echo "Column: $column, Value: $value<br>";
}  -->
        
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

  var column_names=[];
  var column_values=[];
 column_names=<?php echo $columnNamesJson; ?>;
 column_values=<?php echo $columnValuesJson; ?>;
  var max_col=column_names.length;

  document.addEventListener('DOMContentLoaded', function() {
    

    const insertBeforeElement = document.getElementById('Add-form');
    const form = document.getElementById('product-form');
    const addInputBtn = document.getElementById('add-input-btn');
    const dynamicInputName = document.getElementById('dynamic-input-name');
    let inputCounter = 0;
    
    

    addInputBtn.addEventListener('click', function() {
       
      const   name= column_names[inputCounter];
      const table_value =column_values[inputCounter];
    
            
      const newInputDiv = document.createElement('div');
      newInputDiv.classList.add('form-floating', 'mb-3','mt-3');

      const newInput = document.createElement('input');
      newInput.className = 'form-control';
      newInput.type = 'text';
      newInput.name = 'dynamic-input[]';
      newInput.id = 'dynamic-input-' + inputCounter;
      newInput.placeholder = name;
      newInput.required = false;
      newInput.setAttribute('value',  table_value);
      
     
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
      if (inputCounter==max_col) {
    addInputBtn.disabled = true;
    
  }
    });
  });
  </script>
    
   

    
</body>
</html>