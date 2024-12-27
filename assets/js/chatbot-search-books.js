jQuery(document).ready(function ($) {

  // Function process event click
  function sendMessage() {
    const input = document.getElementById('chatInput');
    var question = input.value.trim();

    console.log('Function 1', question);

    if (question) {
      // Display quesion to Chatbody
      $('#chatbot-message .chat-body').append('<div class="message sent">' + question + '</div>');
      $('#chatInput').val(''); // clear input text

      $.ajax({
        url: chatbotAjax.ajaxUrl,
        type: 'POST',
        data: {
          action: 'chatbot_search_books',
          question: question,
        },
        success: function (response) {
          if (response.success) {
            var resultsHtml = '<div class="message received">Dưới đây là kết quả tìm kiếm:</div>';
            response.data.forEach(function (book) {
              resultsHtml += `
                <div class="book-item">
                <h3>${book.title}</h3>
                </div>
                `;
            });
            $('#chatbot-message .chat-body').append(resultsHtml);
          } else {
            $('#chatbot-message .chat-body').append('<div class="message received">' + response.data + '</div>');
          }
        },
        error: function () {
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
