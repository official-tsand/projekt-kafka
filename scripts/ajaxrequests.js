$(function(){
  // define variables here
  // variables of control-buttons come here
  var personalButton = $("#personal-toggle-button");
  var sortNewButton = $("#sort-new-toggle-button");
  var sortOldButton = $("#sort-old-toggle-button");
  var pageRefreshButton = $("#page-refresh-button");
  var entryRefreshButton = $("#entry-refresh-button");
  var navigateBackButton = $("#navigate-back-button");
  var navigateForwardButton = $("#navigate-forward-button");

  // comment-related variables come here
  var commentButton = $("#comment-submit");
  var commentInputBox = $("#comment-input");

  // variables of containers come here
  var quotesContainer = $("#quotes-container");
  var addContainer = $("#add-container");
  var settingsContainer = $("#settings-container");

  // alert-screen-related variables come here
  var alertScreen = $("#alert-deletion-screen");
  var alertDeletionButton = $("#alert-delete-button");
  var messageScreen = $("#message-screen");
  var messageText = $("#message-error-heading");


  // reusable functions come here
  // the function "loadEntry()" is used to load the interface for the user
  function loadEntry(interactionButton)
    {
      // specific variables come here
      var personalStatus = 0;
      var sortStatus = 0;
      var navigationStatus = 0;
      var refreshStatus = 0;

      // check which button was clicked, so the the script knows what to do
      if (interactionButton == "personal-toggle-button")
        {
          personalStatus = -1;
        }
      else if (interactionButton == "sort-new-toggle-button")
        {
          sortStatus = 1;
        }
      else if (interactionButton == "sort-old-toggle-button")
        {
          sortStatus = -1;
        }
      else if (interactionButton == "navigate-back-button")
        {
          navigationStatus = -1;
        }
      else if (interactionButton == "navigate-forward-button")
        {
          navigationStatus = 1;
        }
      else if (interactionButton == "page-refresh-button")
        {
          refreshStatus = 1;
        }

      // make an ajax request
      $.ajax({
        url: "includes/ajaxrequests.inc.php",
        type: "POST",
        data: {
          "load-entry": true,
          "personal-status": personalStatus,
          "sort-status": sortStatus,
          "navigation-status": navigationStatus,
          "refresh-status": refreshStatus
        },
        dataType: "JSON",
        success: function(data)
          {
            if (!data.error)
              {
                // change the interface (header buttons) depending on which button was clicked
                if (interactionButton == "personal-toggle-button")
                  {
                    personalButton.toggleClass("background-color-filled");
                  }
                else if (interactionButton == "sort-new-toggle-button")
                  {
                    sortNewButton.addClass("background-color-filled");
                    sortOldButton.removeClass("background-color-filled");
                  }
                else if (interactionButton == "sort-old-toggle-button")
                  {
                    sortOldButton.addClass("background-color-filled");
                    sortNewButton.removeClass("background-color-filled");
                  }

                quotesContainer.html(data.output);
              }
            else
              {
                displayError(data.error_message);
              }
          }
      });
    }

    // this function takes an error message and displays it
    function displayError(errorMessage)
      {
        showElements(messageScreen);
        messageText.text(errorMessage);
      }

      // this function displays containers
      function showElements()
        {
          var elements = Array.prototype.slice.call(arguments);
          var argumentsLength = elements.length;

          for (i = 0; i < argumentsLength; i++)
            {
              elements[i]
              .removeClass("hidden")
              .attr("aria-hidden", "true");
            }
        }

      // this function hides containers
      function hideElements()
        {
          var elements = Array.prototype.slice.call(arguments);
          var argumentsLength = elements.length;

          for (i = 0; i < argumentsLength; i++)
            {
              elements[i]
              .addClass("hidden")
              .attr("aria-hidden", "false");
            }
        }


    // the document is now ready for action
    // load interface
    loadEntry();

    // load the inferace depending on which button was clicked
    $(document).on("click", "#personal-toggle-button, #sort-new-toggle-button, #sort-old-toggle-button, #page-refresh-button, #entry-refresh-button, #navigate-back-button, #navigate-forward-button", function(){
      // specific variables come here
      var nameButton = $(this).attr("id");

      // load interface
      loadEntry(nameButton);
    });

  // submit quote (with author)
  $(document).on("click", "#quote-submit", function(){
    // specific variables come here
    var quotesContainer = $("#quotes-container");
    var addContainer = $("#add-container");
    var quoteInput = $("#quote-input").val();
    var authorInput = $("#author-input").val();

    $.ajax({
      url: "includes/ajaxrequests.inc.php",
      type: "POST",
      data: {
        "quote-submit": true,
        "quote-input": quoteInput,
        "author-input": authorInput
    },
      dataType: "JSON",
      success: function(data){
          if (!data.error)
            {
              // refresh interface
              loadEntry();

              personalButton.addClass("background-color-filled");
              sortNewButton.addClass("background-color-filled");
              sortOldButton.removeClass("background-color-filled");

              // show "quotes-container" and hide "add-container"
              hideElements(addContainer);
              showElements(quotesContainer);

              $("#quote-input").val("");
              $("#author-input").val("");
            }
          else
            {
              displayError(data.error_message);
            }
      }});
  });

  // submit a comment
  $(document).on("click", "#comment-submit", function(){
    // specific variables come here
    var commentButton = $("#comment-submit");
    var commentInputBox = $("#comment-input");
    var inputValue = commentInputBox.val();
    var target = commentInputBox.attr("data-reply-target");

    // check if the comment is directed to a quote or another comment
    if (target == "general")
      {
        // specific variables come here
        var targetQuote = $("#quote-container").attr("data-key");

        // make an ajax request
        $.ajax({
          url: "includes/ajaxrequests.inc.php",
          type: "POST",
          data: {
            "comment-submit": true,
            "input-value": inputValue,
            "target-quote": targetQuote
          },
          dataType: "JSON",
          success: function(data){
            if (!data.error)
              {
                loadEntry();
                $("html, body").animate({scrollTop: 100}, 500);
              }
            else
              {
                displayError(data.error_message);
              }
        }});
      }
    else
      {
        // make an ajax request
        $.ajax({
          url: "includes/ajaxrequests.inc.php",
          type: "POST",
          data: {
            "reply-submit": true,
            "input-value": inputValue,
            "target-comment": target
          },
          dataType: "JSON",
          success: function(data){
            if (!data.error)
              {
                loadEntry();
              }
            else
              {
                displayError(data.error_message);
              }
        }});
      }
  });

  // delete targeted element
  alertDeletionButton.on("click", function(){
    // specific variables come here
    var targetElement = $(this).attr("data-target");

    $.ajax({
      url: "includes/ajaxrequests.inc.php",
      type: "POST",
      data: {
        "delete-submit": true,
        "target-element": targetElement
      },
      dataType: "JSON",
      success: function(data){
          if (!data.error)
            {
              if (data.removeclass == true)
                {
                  personalButton.removeClass("background-color-filled");
                }

              if (data.reload == true)
                {
                  window.location.reload();
                }

              loadEntry();
              hideElements(alertScreen);
              alertDeletionButton.attr("data-target", "none");
            }
          else
            {
              displayError(data.error_message);
            }
      }});
  });

  // save edited comment
  $(document).on("click", '[data-button="accept"]', function(){
    // specific variables come here
    var commentContainer = $(this).closest("[data-key]");
    var targetComment = commentContainer.attr("data-key");
    var editedCommentText = $("#edited-comment").text();

    $.ajax({
      url: "includes/ajaxrequests.inc.php",
      type: "POST",
      data: {
        "edit-submit": true,
        "edited-comment": editedCommentText,
        "target-comment": targetComment
      },
      dataType: "JSON",
      success: function(data){
        if (!data.error)
          {
            loadEntry();
          }
        else
          {
            displayError(data.error_message);
          }
    }});
  });
});
