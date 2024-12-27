// script.js
// document.addEventListener('DOMContentLoaded', () => {
//   const sendButton = document.getElementById('sendButton');
//   const input = document.getElementById('chatInput');
//   const chatBody = document.querySelector('.chat-body');

//   if (!sendButton || !input || !chatBody) {
//     console.error('Không tìm thấy các phần tử cần thiết trên trang.');
//     return;
//   }

  // // Function send Message
  // function sendMessage() {
  //   const message = input.value.trim();
  //   console.log('Main ', message);

  //   if (!message) return;

  //   // Remove default question when sending new message
  //   const defaultQuestionDiv = document.querySelector('.ai-chatbot-questions-default');
  //   if (defaultQuestionDiv) {
  //     defaultQuestionDiv.remove(); 
  //   }

  //   const newMessage = document.createElement('div');
  //   newMessage.classList.add('message', 'sent');
  //   newMessage.textContent = message;
  //   chatBody.appendChild(newMessage);

  //   scrollToBottom();

  //   input.value = ''; // clear message content in input after sending

  //   // Auto reply after 0.5 second
  //   setTimeout(() => {
  //     const reply = document.createElement('div');
  //     reply.classList.add('message', 'received');
  //     reply.textContent = 'Cảm ơn bạn đã liên hệ. Nhân viên trực của chúng tôi sẽ phản hồi ngay!';
  //     chatBody.appendChild(reply);
  //     scrollToBottom();
  //   }, 500);
  // }

  // // Function to scroll down to the bottom of the chatBody
  // function scrollToBottom() {
  //   chatBody.scrollTop = chatBody.scrollHeight;
  // }

  // sendButton.addEventListener('click', sendMessage);

  // input.addEventListener('keydown', (event) => {
  //   if (event.key === 'Enter') {
  //     event.preventDefault();
  //     sendMessage();
  //   }
  // });
// });

// Event click icon chatbot 
document.addEventListener('DOMContentLoaded', () => {
  const chatToggle = document.querySelector('.chat-toggle');
  const chatbox = document.querySelector('.chatbox');
  const closeButton = document.querySelector('.chatbot-ai-button-close');

  chatToggle.addEventListener('click', () => {
    chatbox.classList.toggle('active');
  });

  closeButton.addEventListener('click', () => {
    chatbox.classList.remove('active');
  });
});
