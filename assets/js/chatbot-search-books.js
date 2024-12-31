jQuery(document).ready(function ($) {

  // Function process event click
  function sendMessage() {
    const input = document.getElementById('chatInput');
    var question = input.value.trim();

    console.log('Function 1', question);

    if (typeof chatbotAjax === 'undefined' || !chatbotAjax.ajaxUrl) { 
      console.error('AJAX object or AJAX URL is not defined.');
      return;
    } else {
      console.log('Ajax called');   
    }

    if (question) {
      // Display question to Chatbody
      $('#chatbot-message .chat-body').append('<div class="message sent">' + question + '</div>');
      $('#chatInput').val(''); // clear input text

      // Show loading spinner
      $('#loading-spinner').show();

      $.ajax({
        url: chatbotAjax.ajaxUrl,
        type: 'POST',
        data: {
          action: 'chatbot_search_books',
          question: question,
        },         
        success: function (response) {

          // Hide loading spinner
          $('#loading-spinner').hide();

          if (response.success) {
            // Build HTML for all results
            let resultsHtml = `
              <div class="message received message-search">
              <img class="logo-main" src="${chatbotAjax.logoUrl}" alt="Logo">
              <div><div class="label-message">Kết quả tìm kiếm:</div>
              <ul>
            `;
            
            response.data.forEach(function (book) {
              resultsHtml += `
                <li class="list-item-search">
                  <a href="/book/${book.id}" target="_blank">
                      <div class="chatbot-heading-product">${book.title}</div>
                  </a>
                </li>
              `;
            });

            resultsHtml += `
                </ul>
              </div>
            </div>
            `;
            // Append results as one message
            $('#chatbot-message .chat-body').append(resultsHtml);
          } else {
            // Display error message from server
            $('#chatbot-message .chat-body').append(`<div class="message received">${response.data}</div>`);
          }
        },
        error: function () {
          // Hide loading spinner if error occurs
          $('#loading-spinner').hide();
          
          $('#chatbot-message .chat-body').append('<div class="message received">Có lỗi xảy ra. Vui lòng thử lại sau.</div>');
        },
      });
    }
  }

  // Function to remove default question
  function removeDefaultQuestion() {
    var defaultQuestionDiv = document.querySelector('.ai-chatbot-questions-default');
    if (defaultQuestionDiv) {
      defaultQuestionDiv.remove();
    }
  }

  // Click sendButton
  $('#sendButton').on('click', function() {
    removeDefaultQuestion(); 
    sendMessage();
  });

  // Enter sendButton
  $('#chatInput').on('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      removeDefaultQuestion();
      sendMessage();
    }
  });
});