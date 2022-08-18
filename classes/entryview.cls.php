<?php
  class EntryView extends EntryModel
    {
      // define properties here
      private $personalStatus;
      private $sortStatus;
      private $navigationStatus;
      private $user;


      // define methods here
      // this is the "constructor"
      public function __construct($personalStatus, $sortStatus, $navigationStatus, $user)
        {
          $this->personalStatus = $personalStatus;
          $this->sortStatus = $sortStatus;
          $this->navigationStatus = $navigationStatus;
          $this->user = $user;
        }

      // this method is called by "load-entry"
      public function showEntry()
        {
          // if the user tries to load his quotes, but does not have any quotes - then display error message
          if ($this->returnNumberQuotes($this->user) == 0 && $this->personalStatus == 1)
            {
              // "reset" session "personal-status"
              $_SESSION["personal-status"] = -1;

              sendAjaxData(array("error", true), array("error_message", "You have to add a quote first!"));
              exit();
            }

          // get results from "getQuote", count them and fetch them
          $resultQuotes = $this->getQuote($this->personalStatus, $this->sortStatus, $this->user);
          $countQuotes = $resultQuotes->rowCount();
          $fetchedQuotes = $resultQuotes->fetchAll(PDO::FETCH_ASSOC);

          // get results from "getComments", count them and fetch them
          $resultComments = $this->getComments($fetchedQuotes[$this->navigationStatus]["column_key"]);
          $countComments = $resultComments->rowCount();
          $fetchedComments = $resultComments->fetchAll(PDO::FETCH_ASSOC);


          // beginning of the entry-element (HTML)
          $output = '<div class="thought-navigation-container flex">';

          // check if navigation-status is in-between range of the number of existing quotes
          if ($this->navigationStatus != 0)
            {
              $output .= '<div>
                            <button id="navigate-back-button" class="action-button"><i class="fa-solid fa-angle-left"></i></button>
                          </div>';
            }

          $output .= '<div id="quote-container" class="quote-container flex" data-key="'.$fetchedQuotes[$this->navigationStatus]["column_key"].'">
                        <div>
                          <span class="username-text">'.$fetchedQuotes[$this->navigationStatus]["column_user"].'</span><span class="date-text">'.date("Y / m / d - H:i", strtotime($fetchedQuotes[$this->navigationStatus]["column_date"])).'</span>
                        </div>
                        <h2 class="quote-text">'.$fetchedQuotes[$this->navigationStatus]["column_text"].'</h2>
                        <span class="author-text">'.$fetchedQuotes[$this->navigationStatus]["column_author"].'</span>';

          // check if user is allowed to have controls
          if ($fetchedQuotes[$this->navigationStatus]["column_user"] == $this->user)
            {
              $output .= '<div class="entry-controls-container flex">
                            <button class="action-button delete-button" data-button="delete"><i class="fa-solid fa-trash"></i></button>
                          </div>';
            }

          $output .= '</div>';

          // check if navigation-status is in-between range of the number of existing quotes
          if ($this->navigationStatus + 1 < $countQuotes)
            {
              $output .= '<div>
                            <button id="navigate-forward-button" class="action-button"><i class="fa-solid fa-angle-right"></i></button>
                          </div>';
            }

          $output .= '</div>
                      <div class="comments-container flex">
                        <span class="comment-heading">Comments:</span>';

          // if there are comments, display them - otherwise show message
          if ($countComments == 0)
            {
              $output .= '<p style="text-align: center;">Sorry, no comments yet :(<br />But you can be the first one :)</p>';
            }
          else
            {
              // display each comment in a container
              foreach($fetchedComments as $rowComment)
                {
                  $output .= '<div class="comment-container flex" data-key="'.$rowComment["column_key"].'">
                                <div>
                                  <span class="username-text">'.$rowComment["column_user"].'</span><span class="date-text">'.date("Y / m / d - H:i", strtotime($rowComment["column_date"])).'</span>
                                 </div>
                               <div>
                                 <p class="comment-text" aria-hidden="true">'.$rowComment["column_text"].'</p>
                                 <div class="entry-controls-container flex" action="" method="POST">
                                   <button class="action-button" data-button="reply" aria-hidden="true"><i class="fa-solid fa-reply"></i></button>';

                  // check if user is allowed to have controls
                  if ($rowComment["column_user"] == $this->user)
                    {
                      $output .= '<button class="action-button" data-button="edit" aria-hidden="true"><i class="fa-solid fa-pen"></i></button>
                                  <button class="action-button delete-button" data-button="delete" aria-hidden="true"><i class="fa-solid fa-trash"></i></button>
                                  <button class="accept-button hidden" data-button="accept" aria-hidden="false"><i class="fa-solid fa-check"></i> Accept</button>
                                  <button class="hidden" data-button="cancel" aria-hidden="false"><i class="fa-solid fa-xmark"></i> Cancel</button>';
                    }

                  $output .= '  </div>
                              </div>';

                  // if there are replies to a comment, display them - otherwise not
                  if ($rowComment["column_check_replies"] == 1)
                    {
                      $output .= '<details open>
                                    <summary><span class="replies-summary-heading">Replies to <span class="bold">'.$rowComment["column_user"].'</span>:</span></summary>
                                    <div class="comments-container flex">';

                      // create variables
                      $resultReplies = $this->getReplies($rowComment["column_key"]);
                      $fetchedReplies = $resultReplies->fetchAll(PDO::FETCH_ASSOC);

                      // display each reply in a container
                      foreach ($fetchedReplies as $rowReply)
                        {
                          $output .= '<div class="comment-container flex" data-key="'.$rowReply["column_key"].'">
                                    <div>
                                  <span class="username-text">'.$rowReply["column_user"].'</span><span class="date-text">'.date("Y / m / d - H:i", strtotime($rowReply["column_date"])).'</span>
                                    </div>
                                    <div>
                                      <p class="comment-text">'.$rowReply["column_text"].'</p>';

                          // check if user is allowed to have controls
                          if ($rowReply["column_user"] == $this->user)
                            {
                              $output .= '<div class="entry-controls-container flex" action="" method="POST">
                                          <button class="action-button" data-button="edit" aria-hidden="true"><i class="fa-solid fa-pen"></i></button>
                                          <button class="action-button delete-button" data-button="delete" aria-hidden="true"><i class="fa-solid fa-trash"></i></button>
                                          <button class="accept-button hidden" type="submit" data-button="accept" aria-hidden="false"><i class="fa-solid fa-check"></i> Accept</button>
                                          <button class="hidden" data-button="cancel" aria-hidden="false"><i class="fa-solid fa-xmark"></i> Cancel</button>
                                        </div>';
                            }

                        $output .= '</div>
                                    </div>';

                        // as long as there are replies display an divider in-between them
                        if ($rowReply !== end($fetchedReplies))
                          {
                            $output .= '<hr class="replies-divider" />';
                          }
                        }

                      $output .= '</div>
                              </details>
                            </div>';

                      // as long as there are comments display an divider in-between them
                      if ($rowComment !== end($fetchedComments))
                        {
                          $output .= '<hr class="comments-divider" />';
                        }
                      }
                    else
                      {
                        $output .= '</div>';

                        // as long as there are comments display an divider in-between them
                        if ($rowComment !== end($fetchedComments))
                          {
                            $output .= '<hr class="comments-divider" />';
                          }
                      }
                    }
                  }

                $output .= '<div class="write-comment-container flex">
                              <div class="flex">
                                <textarea id="comment-input" name="comment-input" rows="5" cols="50" placeholder="Share your thoughts..." data-reply-target="general"></textarea>
                                <button id="comment-submit" class="background-color-filled" type="submit"><i class="fa-solid fa-comment"></i> Comment</button>
                              </div>';

              sendAjaxData(array("output", $output));
            }
    }
