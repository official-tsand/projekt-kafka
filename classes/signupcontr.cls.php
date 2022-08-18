<?php
  class SignupContr extends SignupModel
    {
      // define properties here
      private $userFirstname;
      private $userLastname;
      private $userUsername;
      private $userPassword;
      private $userRePassword;

      // define methods here
      // this is the "constructor"
      public function __construct($userFirstname, $userLastname, $userUsername, $userEmail, $userPassword, $userRePassword)
        {
          $this->userFirstname = $userFirstname;
          $this->userLastname = $userLastname;
          $this->userUsername = $userUsername;
          $this->userEmail = $userEmail;
          $this->userPassword = $userPassword;
          $this->userRePassword = $userRePassword;
        }

      // define methods for error handling
      // this method checks for empty input
      private function checkInput()
        {
          $statusInput;

          if (empty($this->userFirstname) || empty($this->userLastname) || empty($this->userUsername) || empty($this->userEmail) || empty($this->userPassword) || empty($this->userRePassword))
            {
              $statusInput = false;
            }
          else
            {
              $statusInput = true;
            }

          return $statusInput;
        }

      // this method checks if the username is valid
      private function checkUsername()
        {
          $statusUsername;

          if (!preg_match("/^[a-zA-Z0-9_]*$/", $this->userUsername))
            {
              $statusUsername = false;
            }
          else
            {
              $statusUsername = true;
            }

          return $statusUsername;
        }

      // this method checks if the email is valid
      private function checkEmail()
        {
          $statusEmail;

          if (!filter_var($this->userEmail, FILTER_VALIDATE_EMAIL))
            {
              $statusEmail = false;
            }
          else
            {
              $statusEmail = true;
            }

          return $statusEmail;
        }

      // this method checks if the password is strong enough
      private function checkPasswordStrength()
        {
          $statusPasswordStrength;

          if (strlen($this->userPassword) < 8)
            {
              $statusPasswordStrength = false;
            }
          elseif (!preg_match("#[0-9]+#", $this->userPassword))
            {
              $statusPasswordStrength = false;
            }
          elseif (!preg_match("#[a-zA-Z]+#", $this->userPassword))
            {
              $statusPasswordStrength = false;
            }
          else
            {
              $statusPasswordStrength = true;
            }

          return $statusPasswordStrength;
        }

      // this method checks if the two passwords match
      private function checkPasswordMatch()
        {
          $statusPasswordMatch;

          if ($this->userPassword !== $this->userRePassword)
            {
              $statusPasswordMatch = false;
            }
          else
            {
              $statusPasswordMatch = true;
            }

          return $statusPasswordMatch;
        }

      // this method checks if the user already exists
      private function checkUsernameAndEmail()
        {
          $statusUser;

          if (!$this->checkUser($this->userUsername, $this->userEmail))
            {
              $statusUser = false;
            }
          else
            {
              $statusUser = true;
            }

          return $statusUser;
        }


      // activate methods for error handling come here
      // this method is called by "signup-submit"
      public function signupUser()
        {
          $urlValues = "&firstname=" . $this->userFirstname . "&lastname=" . $this->userLastname . "&username=" . $this->userUsername . "&email=" . $this->userEmail;

          if ($this->checkInput() == false)
            {
              header("location: ../signup.pge.php?error=incomplete" . $urlValues);
              exit();
            }

          if ($this->checkUsername() == false)
            {
              header("location: ../signup.pge.php?error=invalidusername" . $urlValues);
              exit();
            }

          if ($this->checkEmail() == false)
            {
              header("location: ../signup.pge.php?error=invalidemail" . $urlValues);
              exit();
            }

          if ($this->checkPasswordStrength() == false)
            {
              header("location: ../signup.pge.php?error=invalidpassword" . $urlValues);
              exit();
            }

          if ($this->checkPasswordMatch() == false)
            {
              header("location: ../signup.pge.php?error=inequalpasswords" . $urlValues);
              exit();
            }

          if ($this->checkUsernameAndEmail() == false)
            {
              header("location: ../signup.pge.php?error=usedusernameoremail" . $urlValues);
              exit();
            }

          // if no errors arise then the user is ready to be signed up
          $this->setUser($this->userFirstname, $this->userLastname, $this->userUsername, $this->userEmail, $this->userPassword);
          $this->startLogin($this->userUsername);
        }
    }
