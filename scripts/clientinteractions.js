$(function(){
  // define variables here
  // variables of containers come here
  var addContainer = $("#add-container");
  var settingsContainer = $("#settings-container");
  var quotesContainer = $("#quotes-container");

  // alert-screen-related variables come here
  var alertButton = $("#alert-delete-button");
  var alertScreen = $("#alert-deletion-screen");
  var messageScreen = $("#message-screen");
  var messageText = $("#message-error-heading");


  // reusable functions come here
  // display error message
  function displayError(errorMessage)
    {
      showElements(messageScreen);
      messageText.text(errorMessage);
    }

  // this function displays elements
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

  // this function hides elements
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
  // show/hide a specific container depending which show/hide-button was clicked
  $(document).on("click", "#add-container-button, #settings-container-button, #quotes-container-button", function(){
    // specific variables come here
    var nameButton = $(this).attr("id");

    if (nameButton == "add-container-button")
      {
        hideElements(settingsContainer, quotesContainer);
        showElements(addContainer);
      }
    else if (nameButton == "settings-container-button")
      {
        hideElements(addContainer, quotesContainer);
        showElements(settingsContainer);
      }
    else if (nameButton == "quotes-container-button")
      {
        hideElements(addContainer, settingsContainer);
        showElements(quotesContainer);
      }
  });

  // close the "alert-screen"
  $(document).on("click", "#alert-delete-cancel, #message-cancel", function(){
    // specific variables come here
    var alertScreen = $(this).closest(".alert-screen");

    hideElements(alertScreen);
    alertButton.attr("data-target", "none");
  });

  // the "reply"-button sets the data-"reply-target" to a specific "comment"
  $(document).on("click", '[data-button="reply"]', function(){
    // specific variables come here
    var commentInputBox = $("#comment-input");
    var allReplyButtons = quotesContainer.find('[data-button="reply"]');
    var entryElement = $(this).closest("[data-key]");
    var entryKey = entryElement.data("key");
    var usernameText = entryElement.find(".username-text:first").text();

    // if this "reply"-button is already active, remove its class and set "reply-target" to "general"
    if ($(this).hasClass("background-color-filled"))
      {
        allReplyButtons.removeClass("background-color-filled");

        commentInputBox
        .attr("data-reply-target", "general")
        .val("")
        .attr("placeholder", "Share your thoughts...");
      }
    else
      {
        allReplyButtons.removeClass("background-color-filled");

        commentInputBox
        .attr("data-reply-target", entryKey)
        .val("")
        .attr("placeholder", "Reply to '" + usernameText + "'")
        .focus();

        $(this).addClass("background-color-filled");
      }
  });

  // open "edit"-mode: show "accept"-button, "cancel"-button & editable "comment-text" and hide ("reply"-button), "edit"-button, "delete"-button and original "comment-text"
  $(document).on("click", '[data-button="edit"]', function(){
    // specific variables come here
    var commentInputBox = $("#comment-input");
    var entryElement = $(this).closest("[data-key]");
    var commentElement = entryElement.find(".comment-text:first");
    var allAcceptButtons = $(document).find('[data-button="accept"]').not(".hidden");
    var allReplyButtons = $(document).find('[data-button="reply"]');
    var replyButton = entryElement.find('[data-button="reply"]:first');
    var editButton = entryElement.find('[data-button="edit"]:first');
    var deleteButton = entryElement.find('[data-button="delete"]:first');
    var acceptButton = entryElement.find('[data-button="accept"]:first');
    var cancelButton = entryElement.find('[data-button="cancel"]:first');

    // enter "edit"-mode only if it is not activated anywhere else in the entry
    if (allAcceptButtons.length == 0)
      {
        if (replyButton.hasClass("background-color-filled"))
          {
            allReplyButtons.removeClass("background-color-filled");

            commentInputBox
            .attr("data-reply-target", "general")
            .val("")
            .attr("placeholder", "Share your thoughts...");
          }

        hideElements(replyButton, editButton, deleteButton, commentElement);
        showElements(acceptButton, cancelButton);

        commentElement
        .after(commentElement.clone().removeClass("hidden").attr({"id": "edited-comment", "contenteditable": "true", "aria-hidden": "true"}));

        entryElement
        .find(".comment-text[contenteditable='true']")
        .focus();
      }
    else
      {
        displayError("You're already editing a comment. To continue, please stop editing that comment.");
      }
  });

  // show "alert-container" and set data-"target" to the element (= the value comes from delete-button)
  $(document).on("click", '[data-button="delete"]', function(){
    // specific variables come here
    var entryElement = $(this).closest("[data-key]");
    var entryKey = entryElement.data("key");

    showElements(alertScreen);
    alertButton.attr("data-target", entryKey);
  });

// show "alert-container" and set data-"target" to "account"
$(document).on("click", "#termination-button", function(){
  showElements(alertScreen);
  alertButton.attr("data-target", "account");
});

  // close "edit"-mode
  $(document).on("click", '[data-button="cancel"]', function(){
    // specific variables come here
    var entryElement = $(this).closest("[data-key]");
    var originalCommentText = entryElement.find(".comment-text:first");
    var editableCommentText = entryElement.find(".comment-text[contenteditable='true']");
    var replyButton = entryElement.find('[data-button="reply"]:first');
    var editButton = entryElement.find('[data-button="edit"]:first');
    var deleteButton = entryElement.find('[data-button="delete"]:first');
    var acceptButton = entryElement.find('[data-button="accept"]:first');
    var cancelButton = entryElement.find('[data-button="cancel"]:first');

    hideElements(acceptButton, cancelButton);
    showElements(replyButton, editButton, deleteButton, originalCommentText);
    editableCommentText.remove();
  });
});
