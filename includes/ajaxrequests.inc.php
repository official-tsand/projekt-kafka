<?php
  // start session
  session_start();

  // "load" classes
  require "autoloader.inc.php";

  // this file makes it possible to send data back to the ajax request ("sendAjaxData()")
  include "../includes/ajaxdata.inc.php";

  // provide username for the following methods
  $inputUsername = $_SESSION["user-name"];

  // the following if-statement checks which button was pressed and performs a specific script based on pressed button
  if (isset($_POST["load-entry"]))
    {
      // pack fetched data into variables
      $personalStatus = $_POST["personal-status"];
      $sortStatus = $_POST["sort-status"];
      $navigationStatus = $_POST["navigation-status"];
      $refreshStatus = $_POST["refresh-status"];

      // if a button was not clicked, its value is "0" - the following if-statements handle that situation
      if (!$_POST["personal-status"] == 0)
        {
          $personalStatus = $_SESSION["personal-status"] = $_SESSION["personal-status"] * $personalStatus;
          $sortStatus = $_SESSION["sort-status"];
          $_SESSION["navigation-status"] = 0;
        }
      else
        {
          $personalStatus = $_SESSION["personal-status"];
        }

      if (!$_POST["sort-status"] == 0)
        {
          $sortStatus = $_SESSION["sort-status"] = $sortStatus;
          $personalStatus = $_SESSION["personal-status"];
          $_SESSION["navigation-status"] = 0;
        }
      else
        {
          $sortStatus = $_SESSION["sort-status"];
        }

      if (!$_POST["refresh-status"] == 0)
        {
          $_SESSION["navigation-status"] = 0;
        }

      // this if-statement ensures that the navigation-status in-between allowed range
      if ($_POST["navigation-status"] != 0 && $_SESSION["navigation-status"] + $_POST["navigation-status"] >= 0)
        {
          $navigationStatus = $_SESSION["navigation-status"] = $_SESSION["navigation-status"] + $_POST["navigation-status"];
          $sortStatus = $_SESSION["sort-status"];
          $personalStatus = $_SESSION["personal-status"];
        }
      else
        {
          $navigationStatus = $_SESSION["navigation-status"];
        }

      // instantiate class "EntryContr"
      $objEntryView = new EntryView($personalStatus, $sortStatus, $navigationStatus, $inputUsername);
      $objEntryView->showEntry();

      exit();
    }
  else if(isset($_POST["quote-submit"]))
    {
      // pack fetched data into variables
      $inputQuote = $_POST["quote-input"];
      $inputAuthor = $_POST["author-input"];

      // set session status to change interface
      $_SESSION["personal-status"] = 1;
      $_SESSION["sort-status"] = 1;
      $_SESSION["navigation-status"] = 0;

      // instantiate class "EntryContr"
      $objPostQuote = new EntryContr();
      $objPostQuote->postQuote($inputUsername, $inputQuote, $inputAuthor);

      exit();
    }
  else if (isset($_POST["comment-submit"]))
    {
      // pack fetched data into variables
      $inputValue = $_POST["input-value"];
      $targetQuote = $_POST["target-quote"];

      // instantiate class "EntryContr"
      $objPostComment = new EntryContr();
      $objPostComment->postComment($inputUsername, $inputValue, $targetQuote);

      exit();
    }
  else if (isset($_POST["reply-submit"]))
    {
      // pack fetched data into variables
      $inputValue = $_POST["input-value"];
      $targetComment = $_POST["target-comment"];

      // instantiate class "EntryContr"
      $objPostReply = new EntryContr();
      $objPostReply->postReply($inputUsername, $inputValue, $targetComment);

      exit();
    }
  else if (isset($_POST["delete-submit"]))
    {
      // pack fetched data into variables
      $targetElement = $_POST["target-element"];

      // instantiate class "EntryContr"
      $objDeleteElement = new EntryContr();
      $objDeleteElement->removeElement($inputUsername, $targetElement);

      exit();
    }
  else if (isset($_POST["edit-submit"]))
    {
      // pack fetched data into variables
      $editedComment = $_POST["edited-comment"];
      $targetComment = $_POST["target-comment"];

      // instantiate class "EntryContr"
      $objEditComment = new EntryContr();
      $objEditComment->editComment($inputUsername, $editedComment, $targetComment);

      exit();
    }
  else
    {
      session_unset();
      session_destroy();
      header("location: ../index.php?error=notallowed");
      exit();
    }
