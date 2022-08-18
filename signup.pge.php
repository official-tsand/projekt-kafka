<?php
  // import "header"
  include_once "elements/header.els.php";
?>
      <!-- the "signup-container" allows the user to put in his personal-data and submit it to the database -->
      <section class="paper-container flex">
       <div class="form-navigation-container flex">
         <a href="index.php"><i class="fa-solid fa-arrow-left-long"></i></a> <h2>Sign up</h2>
       </div>
       <form class="submit-form flex" action="includes/signup.inc.php" method="POST">
         <input type="text" name="signup-firstname" placeholder="First name..." <?php if (isset($_GET["firstname"])) { echo 'value="' . $_GET["firstname"] . '"'; } ?> />
         <input type="text" name="signup-lastname" placeholder="Last name..." <?php if (isset($_GET["lastname"])) { echo 'value="' . $_GET["lastname"] . '"'; } ?> />
         <input type="text" name="signup-username" placeholder="Username..." <?php if (isset($_GET["username"])) { echo 'value="' . $_GET["username"] . '"'; } ?> />
         <input type="text" name="signup-email" placeholder="E-Mail..." <?php if (isset($_GET["email"])) { echo 'value="' . $_GET["email"] . '"'; } ?> />
         <input type="password" name="signup-password" placeholder="Password..." />
         <input type="password" name="signup-re-password" placeholder="Repeat Password..." />
         <button class="background-color-filled" type="submit" name="signup-submit">Submit</button>
       </form>
       <p>Already signed up? Then log in <a href="login.pge.php">here</a></p>
      </section>

      <?php
        // if an error occurs, an alert-screen will show up
        if (isset($_GET["error"]))
          {
      ?>
      <div id="message-screen" class="alert-screen flex">
        <div class="alert-container flex">
      <?php
            if ($_GET["error"] == "incomplete")
              {
                echo "<h2>Fill in all fields!</h2>";
              }
            else if ($_GET["error"] == "invalidusername")
              {
                echo "<h2>Usernames are only allowed to have letters, numbers and underscores!</h2>";
              }
            else if ($_GET["error"] == "invalidemail")
              {
                echo "<h2>Choose a proper E-Mail!</h2>";
              }
            else if ($_GET["error"] == "invalidpassword")
              {
                echo "<h2>Password must have atleast 8 characters, 1 special character and 1 number!</h2>";
              }
            else if ($_GET["error"] == "inequalpasswords")
              {
                echo "<h2>Passwords don't match</h2>";
              }
            else if ($_GET["error"] == "usedusernameoremail")
              {
                echo "<h2>Username or E-Mail already taken!</h2>";
              }
            else if ($_GET["error"] == "stmtfailed")
              {
                echo "<h2>Unable to process data!</h2>";
              }
            else if ($_GET["error"] == "notallowed")
              {
                echo "<h2>Not allowed!</h2>";
              }
        ?>
        <div class="alert-controls-container flex">
          <button id="message-cancel"><i class="fa-solid fa-xmark"></i> Okay</button>
        </div>
      </div>
      <?php
          }
      ?>
<?php
  // import "footer"
  include_once "elements/footer.els.php";
?>
