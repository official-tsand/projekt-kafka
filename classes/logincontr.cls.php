<?php
  class LoginContr extends LoginModel
    {
      // define properties here
      private $userUsernameEmail;
      private $userPassword;

      // define methods here
      // this is the "constructor"
      public function __construct($userUsernameEmail, $userPassword)
        {
          $this->userUsernameEmail = $userUsernameEmail;
          $this->userPassword = $userPassword;
        }

      // define methods for error handling here
      // this method check if input is empty
      private function checkInput()
        {
          $statusInput;

          if (empty($this->userUsernameEmail) || empty($this->userPassword))
            {
              $statusInput = false;
            }
          else
            {
              $statusInput = true;
            }

          return $statusInput;
        }

      // activate methods for error handling come here
      // this method is called by "login-submit"
      public function loginUser()
        {
          $urlValues = "&usernameoremail=" . $this->userUsernameEmail;

          if ($this->checkInput() == false)
            {
              header("location: ../login.pge.php?error=incomplete" . $urlValues);
              exit();
            }

          // if no errors arise then the user is ready to be logged in
          $this->getUser($this->userUsernameEmail, $this->userPassword);
        }
    }
