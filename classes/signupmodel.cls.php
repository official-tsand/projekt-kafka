<?php
  class SignupModel extends DbConnection
    {
      // this method is called by "checkUsernameAndEmail()" and checks if user exists
      protected function checkUser($userUsername, $userEmail)
        {
          $stmt = $this->connect()->prepare("SELECT column_username FROM users WHERE column_username = ? OR column_email = ?;");

          if (!$stmt->execute(array($userUsername, $userEmail)))
            {
              $stmt = null;
              header("location: ../signup.pge.php?error=stmtfailed");
              exit();
            }

          $statusCheck;

          if ($stmt->rowCount() > 0)
            {
              $statusCheck = false;
            }
          else
            {
              $statusCheck = true;
            }

          return $statusCheck;
        }

      // this method is called by "signupUser()" and creates user (database)
      protected function setUser($userFirstname, $userLastname, $userUsername, $userEmail, $userPassword)
        {
          $stmt = $this->connect()->prepare("INSERT INTO users (column_firstname, column_lastname, column_username, column_email, column_password) VALUES (?, ?, ?, ?, ?);");

          // encrypt the password
          $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

          if (!$stmt->execute(array($userFirstname, $userLastname, $userUsername, $userEmail, $hashedPassword)))
            {
              $stmt = null;
              header("location: ../signup.pge.php?error=stmtfailed");
              exit();
            }

          $stmt = null;
        }

      // this method is called by "signupUser()" and starts the login
      protected function startLogin($userUsername)
        {
          $stmt = $this->connect()->prepare("SELECT column_id FROM users WHERE column_username = ?;");

          if (!$stmt->execute(array($userUsername)))
            {
              $stmt = null;
              header("location: ../login.pge.php?error=loginfailed");
              exit();
            }
          else
            {
              $userId = $stmt->fetchAll(PDO::FETCH_ASSOC);

              session_start();
              $_SESSION["user-id"] = $userId[0]["column_id"];
              $_SESSION["user-name"] = $userUsername;
              $_SESSION["personal-status"] = -1;
              $_SESSION["sort-status"] = 1;
              $_SESSION["navigation-status"] = 0;

              $stmt = null;
            }
        }
    }
