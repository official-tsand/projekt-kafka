<?php
  class EntryContr extends EntryModel
    {
      // define methods here
      // this method is called by "quote-submit"
      public function postQuote($inputUsername, $inputQuote, $inputAuthor)
        {
          $quoteKey = uniqid('q', true);
          $quoteText = $inputQuote;
          $quoteAuthor = $inputAuthor;
          $quoteUser = $inputUsername;
          $date = date("Y / m / d - H:i");

          // check if input-values are empty
          if (empty($inputQuote) || empty($inputAuthor))
            {
              sendAjaxData(array("error", true), array("error_message", "Fill in all fields!"));
              exit();
            }
          else
            {
              $this->submitQuote($quoteKey, $quoteText, $quoteAuthor, $quoteUser, $date);
              sendAjaxData(array("error", false));
            }
        }

      // this method is called by "comment-submit"
      public function postComment($inputUsername, $inputValue, $targetQuote)
        {
          $commentKey = uniqid('c', true);
          $commentQuoteKey = $targetQuote;
          $commentText = $inputValue;
          $commentUser = $inputUsername;
          $date = date("Y / m / d - H:i");

          // check if input-value is empty
          if (empty($inputValue))
            {
              sendAjaxData(array("error", true), array("error_message", "Type something first!"));
              exit();
            }
          else
            {
              $this->submitComment($commentKey, $commentQuoteKey, $commentText, $commentUser, $date);
              sendAjaxData(array("error", false));
            }
        }

      // this method is called by "reply-submit"
      public function postReply($inputUsername, $inputValue, $targetComment)
        {
          $replyKey = uniqid('r', true);
          $replyCommentKey = $targetComment;
          $replyText = $inputValue;
          $replyUser = $inputUsername;
          $date = date("Y / m / d - H:i");

          // check if input-value is empty
          if (empty($inputValue))
            {
              sendAjaxData(array("error", true), array("error_message", "Type something first!"));
              exit();
            }
          else
            {
              $this->submitReply($replyKey, $replyCommentKey, $replyText, $replyUser, $date);
              sendAjaxData(array("error", false));
            }
        }

      // this method is called by "delete-submit"
      public function removeElement($inputUsername, $targetElement)
        {
          // delete account of the user
          if ($targetElement == "account")
            {
              $this->deleteAccount($inputUsername);
              session_unset();
              session_destroy();
              sendAjaxData(array("error", false), array("reload", true));
              exit();
            }

          // this status is related to the personal-button
          $statusRemoveClass = false;

          // check if user is authorized to make changes
          if ($this->checkAuthorization($inputUsername, $targetElement) == false)
            {
              sendAjaxData(array("error", true), array("error_message", "Not allowed!"));
              exit();
            }

          if ($targetElement[0] == "q")
            {
              if ($this->returnNumberQuotes($inputUsername) == 1)
                {
                  $_SESSION["personal-status"] = -1;

                  // set status "removeclass" of personal-button to true
                  $statusRemoveClass = true;
                }

              // this if-statement ensures that the navigation-status stays in-between allowed range
              if ($_SESSION["navigation-status"] != 0)
                {
                  $_SESSION["navigation-status"] = $_SESSION["navigation-status"] - 1;
                }

              $this->deleteQuote($targetElement);
              sendAjaxData(array("error", false), array("removeclass", $statusRemoveClass));
              exit();
            }
          else if ($targetElement[0] == "c")
            {
              $this->deleteComment($targetElement);
              sendAjaxData(array("error", false));
              exit();
            }
          else if ($targetElement[0] == "r")
            {
              $this->deleteReply($targetElement);
              sendAjaxData(array("error", false));
              exit();
            }
          else
            {
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }
        }

      // this method is called by "edit-submit"
      public function editComment($inputUsername, $editedComment, $targetOldComment)
        {
          $date = date("Y / m / d - H:i");

          $this->updateComment($inputUsername, $editedComment, $targetOldComment, $date);
          sendAjaxData(array("error", false));
        }
    }
