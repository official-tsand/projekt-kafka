<?php
  class EntryModel extends DbConnection
    {
      // this method returns an array (depeding on whether the value is an array or not) - main use-case for deletion
      private function returnArray($data)
        {
          // create empty array
          $array = array();

          if (is_array($data))
            {
              // push all the data to this array
              $array = $data;
            }
          else
            {
              // push data to this array
              array_push($array, $data);
            }

          return $array;
        }

      // this method checks if the user has atleast one quote
      protected function returnNumberQuotes($user)
        {
          $stmt = $this->connect()->prepare("SELECT * FROM quotes WHERE column_user = ?;");

          if(!$stmt->execute(array($user)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $countValue = $stmt->rowCount();
          $stmt = null;

          return $countValue;
        }

      // check if comment has atleast one reply
      private function checkReplyValue($targetComment)
        {
          $stmt = $this->connect()->prepare("SELECT column_check_replies FROM comments WHERE column_key = ?;");

          if (!$stmt->execute(array($targetComment)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $statusReplyValue = $stmt;
          $stmt = null;

          return $statusReplyValue;
        }

      // check if the user is authorized to make some changes
      protected function checkAuthorization($user, $targetElement)
        {
          // this if-statement checks which table to query - to verify authorization
          if ($targetElement[0] == "q")
            {
              $table = "quotes";
            }
          else if ($targetElement[0] == "c")
            {
              $table = "comments";
            }
          else if ($targetElement[0] == "r")
            {
              $table = "replies";
            }

          $stmt = $this->connect()->prepare("SELECT column_user FROM $table WHERE column_key = ?;");

          if (!$stmt->execute(array($targetElement)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if ($results[0]["column_user"] == $user)
            {
              $authorizationStatus = true;
            }
          else
            {
              $authorizationStatus = false;
            }

          return $authorizationStatus;
        }

      // this method finds the parent comment
      private function findParentComments($childReply)
        {
          $array = $this->returnArray($childReply);
          $placeholder = str_repeat('?,', count($array) - 1) . '?';

          $stmt =  $this->connect()->prepare("SELECT column_linking_key FROM replies WHERE column_key IN ($placeholder);");

          if (!$stmt->execute($array))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $parentComments = array();

          // fill new (empty) array with items from results-array
          for ($i = 0; $i < count($results); $i++)
            {
              $parentComments[] = $results[$i]["column_linking_key"];
            }

          $stmt = null;

          return $parentComments;
        }

      // this method is called by "postQuote()"
      protected function submitQuote($quoteKey, $quoteText, $quoteAuthor, $quoteUser, $quoteDate)
        {
          $stmt = $this->connect()->prepare('INSERT INTO quotes (column_key, column_text, column_author, column_user, column_date) VALUES (?, ?, ?, ?, STR_TO_DATE(?, "%Y / %m / %d - %H:%i"));');

          if (!$stmt->execute(array($quoteKey, $quoteText, $quoteAuthor, $quoteUser, $quoteDate)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $stmt = null;
        }

      // this method is called by "postComment()"
      protected function submitComment($commentKey, $commentQuoteKey, $commentText, $commentUser, $commentDate)
        {
          $stmt = $this->connect()->prepare('INSERT INTO comments (column_key, column_linking_key, column_check_replies, column_text, column_user, column_date) VALUES (?, ?, ?, ?, ?, STR_TO_DATE(?, "%Y / %m / %d - %H:%i"));');

          if (!$stmt->execute(array($commentKey, $commentQuoteKey, 0, $commentText, $commentUser, $commentDate)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $stmt = null;
        }

      // this method is called by "postReply()"
      protected function submitReply($replyKey, $replyCommentKey, $replyText, $replyUser, $replyDate)
        {
          if ($this->checkReplyValue($replyCommentKey) == 1)
            {
              $stmt = $this->connect()->prepare('INSERT INTO replies (column_key, column_linking_key, column_text, column_user, column_date) VALUES (?, ?, ?, ?, STR_TO_DATE(?, "%Y / %m / %d - %H:%i"));');

              if (!$stmt->execute(array($replyKey, $replyCommentKey, $replyText, $replyUser, $replyDate)))
                {
                  $stmt = null;
                  sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
                  exit();
                }

              $stmt = null;
            }
          else
            {
              $stmt = $this->connect()->prepare('INSERT INTO replies (column_key, column_linking_key, column_text, column_user, column_date) VALUES (?, ?, ?, ?, STR_TO_DATE(?, "%Y / %m / %d - %H:%i")); UPDATE comments SET column_check_replies = ? WHERE column_key = ?;');

              if (!$stmt->execute(array($replyKey, $replyCommentKey, $replyText, $replyUser, $replyDate, 1, $replyCommentKey)))
                {
                  $stmt = null;
                  sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
                  exit();
                }

              $stmt = null;
            }
        }

      // this method is called by "removeElement()"
      protected function deleteQuote($targetQuote)
        {
          $array = $this->returnArray($targetQuote);
          $placeholder = str_repeat('?,', count($array) - 1) . '?';

          $stmt = $this->connect()->prepare("DELETE FROM quotes WHERE column_key IN ($placeholder);");

          // not only the quote, but all the comments and replies associated with the quote need to be eliminated (comments and replies are not deleted until the "deleteComment"-method -- see below)
          $stmt2 = $this->connect()->prepare("SELECT column_key FROM comments WHERE column_linking_key IN ($placeholder);");

          if (!$stmt->execute($array))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $stmt = null;

          if (!$stmt2->execute($array))
            {
              $stmt2 = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          // call "deleteComment"-method only if there are more than "0" comments
          if ($stmt2->rowCount() > 0)
            {
              $results = $stmt2->fetchAll(PDO::FETCH_ASSOC);
              $comments = array();

              // fill new (empty) array with items from results-array
              for ($i = 0; $i < count($results); $i++)
                {
                  $comments[] = $results[$i]["column_key"];
                }

              $this->deleteComment($comments);
            }

          $stmt2 = null;
        }

      // this method is called by "removeElement()"
      protected function deleteComment($targetComment)
        {
          $array = $this->returnArray($targetComment);
          $placeholder = str_repeat('?,', count($array) - 1) . '?';

          // prepare statement to delete the comments and the associated replies
          $stmt = $this->connect()->prepare("DELETE FROM comments WHERE column_key IN ($placeholder);");
          $stmt2 = $this->connect()->prepare("DELETE FROM replies WHERE column_linking_key IN ($placeholder);");

          // if either statement fails, send error message
          if (!$stmt->execute($array) || !$stmt2->execute($array))
            {
              $stmt = null;
              $stmt2 = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $stmt = null;
          $stmt2 = null;
        }

      // this method is called by "removeElement()"
      protected function deleteReply($targetReply)
        {
          $array = $this->returnArray($targetReply);
          $placeholder = str_repeat('?,', count($array) - 1) . '?';

          $parentComments = $this->findParentComments($array);
          $placeholder2 = str_repeat('?,', count($parentComments) - 1) . '?';

          // delete those replies and set column_check_replies of parent-comments to "0"
          $stmt = $this->connect()->prepare("DELETE FROM replies WHERE column_key IN ($placeholder);");
          $stmt2 = $this->connect()->prepare("UPDATE comments SET column_check_replies = 0 WHERE column_key IN ($placeholder2);");

          // if either statement fails, send error message
          if (!$stmt->execute($array) || !$stmt2->execute($parentComments))
            {
              $stmt = null;
              $stmt2 = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $stmt = null;
          $stmt2 = null;

          // prepare statement which checks which comments should not be set to "0"
          $stmt = $this->connect()->prepare("SELECT column_linking_key FROM replies WHERE column_linking_key IN ($placeholder2)");

          if (!$stmt->execute($parentComments))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $commentsWithReplies = array();

          // fill new (empty) array with items from results-array
          for ($i = 0; $i < count($results); $i++)
            {
              $commentsWithReplies[] = $results[$i]["column_linking_key"];
            }

          $stmt = null;

          // set column_check_replies of comments with replies to "1" only if there are more than "0" of them
          if (count($commentsWithReplies) > 0)
            {
              $placeholder3 = str_repeat('?,', count($commentsWithReplies) - 1) . '?';

              $stmt = $this->connect()->prepare("UPDATE comments SET column_check_replies = 1 WHERE column_key IN ($placeholder3);");

              if (!$stmt->execute($commentsWithReplies))
                {
                  $stmt = null;
                  sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
                  exit();
                }

              $stmt = null;
            }
        }

      protected function deleteAccount($user)
        {
          // get all quotes associated with this user
          $stmt = $this->connect()->prepare("SELECT column_key FROM quotes WHERE column_user = ?");

          if (!$stmt->execute(array($user)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          // call "deleteQuote"-method only if there are more than "0" quotes
          if ($stmt->rowCount() > 0)
            {
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $quotes = array();

              // fill new (empty) array with items from results-array
              for ($i = 0; $i < count($results); $i++)
                {
                  $quotes[] = $results[$i]["column_key"];
                }

              $this->deleteQuote($quotes);
            }

          $stmt = null;

          // get all comments associated with this user
          $stmt = $this->connect()->prepare("SELECT column_key FROM comments WHERE column_user = ?");

          if (!$stmt->execute(array($user)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          // call "deleteComment"-method only if there are more than "0" comments
          if ($stmt->rowCount() > 0)
            {
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $comments = array();

              // fill new (empty) array with items from results-array
              for ($i = 0; $i < count($results); $i++)
                {
                  $comments[] = $results[$i]["column_key"];
                }

              $this->deleteComment($comments);
            }

          $stmt = null;

          // get replies associated with this user
          $stmt = $this->connect()->prepare("SELECT column_key FROM replies WHERE column_user = ?");

          if (!$stmt->execute(array($user)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          // call "deleteReply()"-method only if there are more than "0" replies
          if ($stmt->rowCount() > 0)
            {
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $replies = array();

              // fill new (empty) array with items from results-array
              for ($i = 0; $i < count($results); $i++)
                {
                  $replies[] = $results[$i]["column_key"];
                }

              $this->deleteReply($replies);
            }

          $stmt = null;

          // delete profile of the user
          $stmt = $this->connect()->prepare("DELETE FROM users WHERE column_username = ?;");

          if (!$stmt->execute(array($user)))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          $stmt = null;
        }

      // this method is called by "editComment()"
      protected function updateComment($user, $editedComment, $targetOldComment, $date)
        {
          if ($this->checkAuthorization($user, $targetOldComment) == false)
            {
              sendAjaxData(array("error", true), array("error_message", "Not allowed!"));
              exit();
            }
          else
            {
              if ($targetOldComment[0] == "c")
                {
                  $stmt = $this->connect()->prepare('UPDATE comments SET column_text = ?, column_date = STR_TO_DATE(?, "%Y / %m / %d - %H:%i") WHERE column_key = ?;');

                  if (!$stmt->execute(array($editedComment, $date, $targetOldComment)))
                    {
                      $stmt = null;
                      sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
                      exit();
                    }

                  $stmt = null;
                }
              else if ($targetOldComment[0] == "r")
                {
                  $stmt = $this->connect()->prepare('UPDATE replies SET column_text = ?, column_date = STR_TO_DATE(?, "%Y / %m / %d - %H:%i") WHERE column_key = ?;');

                  if (!$stmt->execute(array($editedComment, $date, $targetOldComment)))
                    {
                      $stmt = null;
                      sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
                      exit();
                    }

                  $stmt = null;
                }
            }
        }

      // this method is called by "showEntry()"
      protected function getQuote($personalStatus, $sortStatus, $user)
        {
          // sort database depending on whether sort-button is active or not
          $sortStatus == -1 ? $sort = "ASC" : $sort = "DESC";

          // if personal-status is active then only search for the user's quotes (otherwise all)
          $where = $personalStatus == 1 ? "WHERE column_user = ?" : null;
          $array = $personalStatus == 1 ? array($user) : null;

          $stmt = $this->connect()->prepare("SELECT column_key, column_text, column_author, column_user, column_date FROM quotes $where ORDER BY column_id $sort;");

          if (!$stmt->execute($array))
            {
              $stmt = null;
              sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
              exit();
            }

          return $stmt;

          $stmt = null;
        }

        // this method is called by "showEntry()"
        protected function getComments($quoteKey)
          {
            $stmt = $this->connect()->prepare("SELECT column_key, column_check_replies, column_text, column_user, column_date FROM comments WHERE column_linking_key = ? GROUP BY column_id DESC;");

            if (!$stmt->execute(array($quoteKey)))
              {
                $stmt = null;
                sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
                exit();
              }

            return $stmt;

            $stmt = null;
          }

        // this method is called by "showEntry()"
        protected function getReplies($commentKey)
          {
            $stmt = $this->connect()->prepare("SELECT column_key, column_text, column_user, column_date FROM replies WHERE column_linking_key = ? GROUP BY column_id DESC;");

            if (!$stmt->execute(array($commentKey)))
              {
                $stmt = null;
                sendAjaxData(array("error", true), array("error_message", "Something went wrong..."));
                exit();
              }

            return $stmt;

            $stmt = null;
          }
    }
