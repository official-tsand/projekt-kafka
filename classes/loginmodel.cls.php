<?php
  class LoginModel extends DbConnection
    {
      // this method is called by "loginUser()" and checks if THIS user exists
      protected function getUser($userUsernameEmail, $userPassword)
        {
          $urlValues = "&usernameoremail=" . $userUsernameEmail;

          $stmt = $this->connect()->prepare("SELECT column_password FROM users WHERE column_username = ? OR column_email = ?;");

          if (!$stmt->execute(array($userUsernameEmail, $userUsernameEmail)))
            {
              $stmt = null;
              header("location: ../login.pge.php?error=stmtfailed");
              exit();
            }

          if ($stmt->rowCount() == 0)
            {
              $stmt = null;
              header("location: ../login.pge.php?error=usernotfound" . $urlValues);
              exit();
            }

          $hashedPassword = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // check if the password is correct
          $checkPassword = password_verify($userPassword, $hashedPassword[0]["column_password"]);

          if ($checkPassword == false)
            {
              $stmt = null;
              header("location: ../login.pge.php?error=wrongpassword" . $urlValues);
              exit();
            }
          else
            {
              $stmt = $this->connect()->prepare("SELECT * FROM users WHERE column_username = ? OR column_email = ? AND column_password = ?;");

              if (!$stmt->execute(array($userUsernameEmail, $userUsernameEmail, $hashedPassword[0]["column_password"])))
                {
                  $stmt = null;
                  header("location: ../login.pge.php?error=stmtfailed");
                  exit();
                }

              if ($stmt->rowCount() == 0)
                {
                  $stmt = null;
                  header("location: ../login.pge.php?error=usernotfound" . $urlValues);
                  exit();
                }

              $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

              session_start();
              $_SESSION["user-id"] = $user[0]["column_id"];
              $_SESSION["user-name"] = $user[0]["column_username"];
              $_SESSION["personal-status"] = -1;
              $_SESSION["sort-status"] = 1;
              $_SESSION["navigation-status"] = 0;

              $stmt = null;
            }
        }
    }
