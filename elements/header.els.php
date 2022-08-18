<!DOCTYPE HTML>
<html lang="en-US">
<head>
  <!-- meta-data -->
  <meta charset="utf-8" />
  <meta name="author" content="kafka Inc.">
  <meta name="description" content="kafka - A place to discuss the thoughts of the greatest minds in history" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- scripts -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="scripts/clientinteractions.js" charset="utf-8" async></script>
  <script src="scripts/ajaxrequests.js" charset="utf-8" async></script>

  <!-- styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="styling/main.css" />
  <link rel="icon" href="images/favicon.ico" />

  <title>kafka | Discuss great thoughts</title>
</head>
<body>
  <!-- this "grid-container" contains "header" and "main" -->
  <div class="wrapper flex">
    <!-- if the user deletes something, this "alert-screen" will show up -->
    <div id="alert-deletion-screen" class="alert-screen flex hidden" aria-hidden="false">
      <div class="alert-container flex">
        <h2>Do you really want to delete it?</h2>
        <div class="alert-controls-container flex">
          <button id="alert-delete-button" class="delete-button" data-target="none"><i class="fa-solid fa-trash"></i> Delete</button>
          <button id="alert-delete-cancel"><i class="fa-solid fa-xmark"></i> Cancel</button>
        </div>
      </div>
    </div>
    <!-- this header contains the controls -->
    <header class="flex">
      <div class="header-logo-container flex">
        <span class="logo-title">kafka</span>
      </div>
      <?php
        if (isset($_SESSION["user-id"]))
          {
      ?>
      <div class="header-menu-container flex">
        <div>
          <button id="personal-toggle-button" class="<?php if (isset($_SESSION["personal-status"])) { if ($_SESSION["personal-status"] == 1) { echo "background-color-filled"; } } ?> action-button"><i class="fa-solid fa-person-shelter"></i></button>
          <button id="sort-new-toggle-button" class="<?php if (isset($_SESSION["sort-status"])) { if ($_SESSION["sort-status"] == 1) { echo "background-color-filled"; } } ?> action-button"><i class="fa-solid fa-arrow-up"></i></button>
          <button id="sort-old-toggle-button" class="<?php if (isset($_SESSION["sort-status"])) { if ($_SESSION["sort-status"] == -1) { echo "background-color-filled"; } } ?> action-button"><i class="fa-solid fa-arrow-down"></i></button>
          <button id="page-refresh-button" class="action-button"><i class="fa-solid fa-arrows-rotate"></i></button>
          <button id="entry-refresh-button" class="action-button"><i class="fa-solid fa-arrow-rotate-right"></i></button>
          <button id="add-container-button" class="action-button"><i class="fa-solid fa-plus"></i></button>
        </div>
        <div>
          <button id="quotes-container-button" class="background-color-filled"><i class="fa-solid fa-house"></i> Quotes</button>
          <button id="settings-container-button" class="background-color-filled"><i class="fa-solid fa-gear"></i> Settings</button>
        </div>
      </div>
      <?php
          }
      ?>
    </header>
    <main class="flex">
