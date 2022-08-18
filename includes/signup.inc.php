<?php
  // the following if-statement checks if the signup-button was pressed
  if (!isset($_POST["signup-submit"]))
    {
      header("location: ../signup.pge.php?error=notallowed");
      exit();
    }
  else
    {
      // "load" classes
      require_once "autoloader.inc.php";

      // grab data from form
      $userFirstname = $_POST["signup-firstname"];
      $userLastname = $_POST["signup-lastname"];
      $userUsername = $_POST["signup-username"];
      $userEmail = $_POST["signup-email"];
      $userPassword = $_POST["signup-password"];
      $userRePassword = $_POST["signup-re-password"];

      // instantiate class "SignupContr"
      $objSignup = new SignupContr($userFirstname, $userLastname, $userUsername, $userEmail, $userPassword, $userRePassword);

      // run error handlers and sign up user
      $objSignup->signupUser();

      // go back to the front-page
      header("location: ../index.php");
      exit();
    }
