<?php
  // the following if-statement checks if the login-button was pressed
  if (!isset($_POST["login-submit"]))
    {
      header("location: ../login.pge.php?error=notallowed");
      exit();
    }
  else
    {
      // "load" classes
      require_once "autoloader.inc.php";

      // grab the data from form
      $userUsernameEmail = $_POST["login-username-email"];
      $userPassword = $_POST["login-password"];

      // instantiate class "LoginContr"
      $objLogin = new LoginContr($userUsernameEmail, $userPassword);

      // run error handlers and log in user
      $objLogin->loginUser();

      // go back to the front-page
      header("location: ../index.php");
      exit();
    }
