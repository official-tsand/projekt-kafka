<?php
  // start session
  session_start();

  // import "header"
  require_once "elements/header.els.php";
?>
      <?php
        // if the user is logged in (means that the session is active), the "quotes-container", "add-container" & "settings-container" will show up. If not, then the "introduction-container" is the only thing visible
        if (!isset($_SESSION["user-id"]))
          {
      ?>
      <!-- the "welcome-screen" contains a heading, text and an image -->
      <section id="introduction-container" class="introduction-container paper-container flex">
      <div class="introduction-side-container flex">
        <div class="introduction-text-container">
          <h1 class="introduction-title">Quotes & Thoughts</h1>
          <p class="introduction-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <div class="introduction-navigation-container flex">
            <a class="navigation-link background-color-filled" href="signup.pge.php">Sign up</a>
            <p>or</p>
            <a class="navigation-link" href="login.pge.php">Log in</a>
          </div>
        </div>
      </div>
      <div class="introduction-side-container flex">
        <img src="images/introduction-image.png" alt="image-description: Franz Kafka, Johann Wolfgang von Goethe und Friedrich Nietzsche" />
      </div>
      </section>
      <?php
          }
        else
          {
      ?>
      <!-- the "browse-container" lets the user see the entries and interact with them -->
      <section id="quotes-container" class="quotes-container paper-container flex" aria-hidden="true"></section>

      <!-- the "add-container" gives the user the ability to add a new thought (inclusive author) -->
      <section id="add-container" class="paper-container flex hidden" aria-hidden="false">
        <h2>Add Quote</h2>
        <div class="submit-form flex">
          <textarea id="quote-input" rows="5" cols="50" placeholder="Quote..."></textarea>
          <input id="author-input" type="text" placeholder="By which author...?" />
          <button id="quote-submit" class="background-color-filled"><i class="fa-regular fa-plus"></i> Add quote</button>
        </div>
      </section>

      <!-- the "settings-container" allows the user to change and control personal-data -->
      <section id="settings-container" class="paper-container flex hidden" aria-hidden="false">
         <form action="includes/logout.inc.php" method="POST">
            <button class="background-color-filled" name="logout-submit">Log out</button>
         </form>
          <button id="termination-button" class="delete-button">Delete Account</button>
      </section>

      <!-- the "message-screen" pops up, if an error occures -->
      <div id="message-screen" class="alert-screen flex hidden">
        <div class="alert-container flex">
          <h2 id="message-error-heading"></h2>
        <div class="alert-controls-container flex">
          <button id="message-cancel"><i class="fa-solid fa-xmark"></i> Okay</button>
        </div>
      </div>
      <?php
          }
      ?>
<?php
  // import "footer"
  require_once "elements/footer.els.php";
?>
