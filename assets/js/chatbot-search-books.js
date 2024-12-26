

jQuery(document).ready(function ($) {
  // Lắng nghe sự kiện khi người dùng nhấn nút gửi
  $('#sendButton').on('click', function () {
  var question = $('#chatInput').val().trim();

  console.log('Function call ajax khi click #sendButton');

  if (question) {
    // Hiển thị câu hỏi của người dùng trong cửa sổ chat
    $('#chatbot-message .chat-body').append('<div class="message sent">' + question + '</div>');
    $('#chatInput').val(''); // Xóa ô nhập sau khi gửi câu hỏi

      // Gửi câu hỏi cho server để xử lý
      $.ajax({
        url: chatbotAjax.ajaxUrl, // URL gửi yêu cầu AJAX
        type: 'POST',
        data: {
          action: 'chatbot_search_books', // Tên action đã đăng ký ở PHP
          question: question
        },
        success: function (response) {
          if (response.success) {
            var resultsHtml = '<div class="message received">Dưới đây là kết quả tìm kiếm:</div>';
            response.data.forEach(function (book) {
              resultsHtml += `
              <div class="book-item">
              <h3>${book.title}</h3>
              <p><strong>Nhà xuất bản:</strong> ${book.publisherName}</p>
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
        }
      });
    }
  });

  // Lắng nghe sự kiện khi người dùng nhấn Enter để gửi câu hỏi
  $('#chatInput').on('keypress', function (e) {
    if (e.which == 13) { // Nếu nhấn Enter
      $('#sendButton').click();
    }
  });
});