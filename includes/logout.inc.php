<?php
  // the following if-statement checks if the logout-button was pressed
  if (!isset($_POST["logout-submit"]))
    {
      header("location: ../index.php?error=notallowed");
      exit();
    }
  else
    {
      session_start();
      session_unset();
      session_destroy();

      header("location: ../index.php");
      exit();
    }
