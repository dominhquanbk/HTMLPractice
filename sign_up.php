<?php
session_start(); // Start the session

include('db_connect.php');


if (isset($_SESSION['loggedin'])) {
    echo "<script>alert('You have already logged in');</script>";
    echo "<script>window.location.href = 'main1.php';</script>";
      exit();
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
    <link rel="stylesheet" href="login.css">
    <style>
        #passlength, #account_exist {
          position: fixed;
          display: none;
          padding: 20px;
          background-color: red;
          left: 0;
        right: 0;
        z-index: 9999;
        }
        #account_success {
          position: fixed;
            display: none;
            padding: 20px;
            background-color: rgb(0, 255, 170);
            left: 0;
          right: 0;
        z-index: 9999;
        }
    </style>

    <title>Document</title>
</head>
<body>
<div id="passlength">Your password must have at least 6 characters</div>
<div id="account_exist">Account already exist</div>
<div id="account_success">Account has been created successfully</div>
<div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="text-center text-dark mt-5">Sign Up Form</h2>
        <div class="text-center mb-5 text-dark">Join us and enjoy the cuteness of Doges</div>
        <div class="card my-5">

          <div class="card-body cardbody-color p-lg-5" >

            <div class="text-center">
              <img src="/IMAGE/sign_up_doge.jpg" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                width="200px" alt="profile">
            </div>

            <div class="mb-3">
              <input id="account" type="text" class="form-control" name="username" aria-describedby="emailHelp"
                placeholder="User Name" required>
            </div>
            <div class="mb-3">
              <input id="password" type="password" class="form-control" name="password" placeholder="password" required>
            </div>
            <div class="text-center"><button type="button"  class="btn btn-color px-5 mb-5 w-100" onclick="Account_create()">Create your account</button></div>
            <div id="emailHelp" class="form-text text-center mb-5 text-dark">Already a user? <a href="#" class="text-dark fw-bold" onclick="navigateToLogIn()"> Log in your
                Account</a>
            </div>
      </div>
        </div>

      </div>
    </div>
  </div>
  <script>
   function pass_error(){
            var myDiv = $("#passlength");
            myDiv.fadeIn(500, function(){
                setTimeout(function(){
                    myDiv.fadeOut(500);
                }, 2000); // 3000 milliseconds = 3 seconds
            });
        };
        function account_error(){
            var myDiv = $("#account_exist");
            myDiv.fadeIn(500, function(){
                setTimeout(function(){
                    myDiv.fadeOut(500);
                }, 2000); // 3000 milliseconds = 3 seconds
            });
        };
        function account_success(){
            var myDiv = $("#account_success");
            myDiv.fadeIn(500, function(){
                setTimeout(function(){
                    myDiv.fadeOut(500);
                }, 2000); // 3000 milliseconds = 3 seconds
            });
        };
        function Account_create() {
                var password = $("#password").val();
                var account = $("#account").val();
                if(password.length<6){
                  pass_error();
                }
                else{
                  $.ajax({
                    url: 'http://localhost/ajax/add_Account.php',
                    method: 'POST',
                    data: {
                      password: password,
                      username:account
                    },
                    dataType: 'json',
                    success: function(response) {
                      if (response.added == 1) {
                        account_success();
                      } else if (response.added == 0) {
                        account_error();
                      }
                    },
                    error: function(e) {
                      alert(e);
                      throw e;
                    }
                  });
                }
                
      }
   
    function navigateToLogIn(){
      window.location.href = 'login.php';
    }
  </script>
</body>
</html>