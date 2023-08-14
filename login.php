<?php
session_start(); // Start the session

include('db_connect.php');
if (isset($_SESSION['loggedin'])) {
  echo "<script>alert('You have already logged in');</script>";
  echo "<script>window.location.href = 'main1.php';</script>";
    exit();
}

if (isset($_POST["submit"])) {
    $name = $_POST["username"];
    $password = $_POST["password"];
    $queryAccount = "SELECT * FROM account";
    $resultAccount = mysqli_query($connect, $queryAccount);

    while ($row = mysqli_fetch_assoc($resultAccount)) {
        if ($name == $row['account'] && $password == $row['password']) {
            $_SESSION['loggedin'] = true; // Set the loggedin session variable
            $_SESSION['username'] = $name; // Store the username in session if needed
            $_SESSION['IsAdmin'] = $row['IsAdmin'];
            header("Location: main1.php"); // Redirect to main1.php or any other desired page
            exit();
        }
    }

    echo "<script>alert('Wrong account or password');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/bootstrap-icons.min.js"></script>
    <link rel="stylesheet" href="login.css">

    <title>Document</title>
</head>
<body>
<div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="text-center text-dark mt-5">Login Form</h2>
        <div class="text-center mb-5 text-dark">Welcome to Doge Selling Website</div>
        <div class="card my-5">

          <form class="card-body cardbody-color p-lg-5" method="POST" action="">

            <div class="text-center">
              <img src="https://akm-img-a-in.tosshub.com/sites/visualstory/stories/2022_12/story_16611/assets/2.jpeg?time=1672399209" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                width="200px" alt="profile">
            </div>

            <div class="mb-3">
              <input type="text" class="form-control" name="username" aria-describedby="emailHelp"
                placeholder="User Name" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="password" required>
            </div>
            <div class="text-center"><button type="submit" name="submit" class="btn btn-color px-5 mb-5 w-100">Login</button></div>
            <div id="emailHelp" class="form-text text-center mb-5 text-dark">Not
              Registered? <a href="#" class="text-dark fw-bold" onclick="navigateToSignUp()"> Create an
                Account</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
  <script>
    function navigateToSignUp(){
      window.location.href = 'sign_up.php';
    }
  </script>
</body>
</html>