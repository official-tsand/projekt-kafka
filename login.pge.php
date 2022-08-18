<?php
  // import "header"
  include_once "elements/header.els.php";
?>
      <!-- the "login-container" allows the user to log in -->
      <section id="login-container" class="paper-container flex">
        <div class="form-navigation-container flex">
          <a href="index.php"><i class="fa-solid fa-arrow-left-long"></i></a> <h2>Log in</h2>
        </div>
       <form class="submit-form flex" action="includes/login.inc.php" method="POST">
         <input type="text" name="login-username-email" placeholder="Username or E-Mail..." <?php if (isset($_GET["usernameoremail"])) { echo 'value="' . $_GET["usernameoremail"] . '"'; } ?> />
         <input type="password" name="login-password" placeholder="Password..." />
         <button class="background-color-filled" type="submit" name="login-submit">Submit</button>
       </form>
       <p>No account yet? Then sign up <a href="signup.pge.php">here</a></p>
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
            else if ($_GET["error"] == "wrongpassword")
              {
                echo "<h2>Password does not match username/email!</h2>";
              }
            else if ($_GET["error"] == "usernotfound")
              {
                echo "<h2>User does not exist! Try to sign up!</h2>";
              }
            else if ($_GET["error"] == "stmtfailed")
              {
                echo "<h2>Unable to process data!</h2>";
              }
            else if ($_GET["error"] == "loginfailed")
              {
                echo "<h2>Unable to login! Try to log in!</h2>";
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
