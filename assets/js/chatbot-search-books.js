jQuery(document).ready(function ($) {

  // Function process event click
  function sendMessage() {
    const input = document.getElementById('chatInput');
    var question = input.value.trim();

    if (typeof chatbotAjax === 'undefined' || !chatbotAjax.ajaxUrl) { 
      console.error('AJAX object or AJAX URL is not defined.');
      return;
    } else {
      console.log('Ajax called');   
    }

    if (question) {
      // Display question to Chatbody
      $('#chatbot-message .chat-body').append('<div class="message sent">' + question + '</div>');
      $('#chatInput').val('');

      // Tạo phần tử loading ngay sau tin nhắn vừa gửi
      const loadingHTML = '<div class="chatbot-loading-message chatbot-box-loader"> <span></span> <span></span> <span></span></div>';
      $('#chatbot-message .chat-body').append(loadingHTML); // Thêm loading vào container chat

      // Cuộn xuống cuối container chat
      $('#chatbot-message .chat-body').scrollTop($('#chatbot-message .chat-body')[0].scrollHeight);

      $.ajax({
        url: chatbotAjax.ajaxUrl,
        type: 'POST',
        data: {
          action: 'chatbot_search_books',
          question: question,
        },         
        success: function (response) {
          $('.chatbot-loading-message').remove();

          if (response.success) {
            // Build HTML for all results
            let resultsHtml = `
              <div class="message received message-search">
              <div class="chatbot-logo-ajax"><img class="logo-main" src="${chatbotAjax.logoUrl}" alt="Logo"></div>
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
            $('#chatbot-message .chat-body').append(resultsHtml);
          } else {
            $('#chatbot-message .chat-body').append(`<div class="message received">${response.data}</div>`);
          }

          // Scroll bottom of chat container
          $('#chatbot-message .chat-body').scrollTop($('#chatbot-message .chat-body')[0].scrollHeight);
        },
        error: function () {
          $('.chatbot-loading-message').remove();

          $('#chatbot-message .chat-body').append('<div class="message received">Có lỗi xảy ra. Vui lòng thử lại sau.</div>');

          // Scroll bottom of chat container
          $('#chatbot-message .chat-body').scrollTop($('#chatbot-message .chat-body')[0].scrollHeight);
        }
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