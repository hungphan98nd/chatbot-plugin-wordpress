
// Event click icon chatbot on/off Chatbox
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