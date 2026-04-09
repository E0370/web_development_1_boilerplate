const messageForm = document.getElementById('messageForm');
const chatBox = document.getElementById('chatBox');
const messageInput = document.getElementById('messageInput');
const itemIdInput = document.getElementById('itemId');
const receiverIdInput = document.getElementById('receiverId');
const messageError = document.getElementById('messageError');

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

function formatMessageTime(dateString) {
  const date = new Date(dateString);
  const day = String(date.getDate()).padStart(2, '0');
  const month = date.toLocaleString('en-US', { month: 'long' });
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  return `${day} ${month} ${hours}:${minutes}`;
}

function renderMessages(messages, currentUserId) {
  if (!messages || messages.length === 0) {
    chatBox.innerHTML = '<p class="text-muted text-center mb-0">No messages yet. Start the conversation.</p>';
    return;
  }

  chatBox.innerHTML = messages.map(msg => {
    const isMine = Number(msg.sender_id) === Number(currentUserId);

    return `
      <article class="d-flex mb-3 ${isMine ? 'justify-content-end' : 'justify-content-start'}" role="listitem">
        <div class="messagebubble ${isMine ? 'mymessage' : 'othermessage'}">
          <div class="messagetext">${escapeHtml(msg.message)}</div>
          <div class="messagetime">${formatMessageTime(msg.created_at)}</div>
        </div>
      </article>
    `;
  }).join('');

  chatBox.scrollTop = chatBox.scrollHeight;
}

async function loadMessages() {
  try {
    const itemId = itemIdInput.value;
    const receiverId = receiverIdInput.value;

    const response = await fetch(`/api/messages/${itemId}/${receiverId}`);
    const data = await response.json();

    if (!data.success) {
      throw new Error(data.message || 'Failed to load messages.');
    }

    renderMessages(data.messages, data.currentUserId);
  } catch (error) {
    messageError.textContent = error.message;
  }
}

if (messageForm && chatBox && messageInput && itemIdInput && receiverIdInput) {
  messageForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    messageError.textContent = '';

    const formData = new FormData(messageForm);

    try {
      const response = await fetch('/api/sendmessage', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (!data.success) {
        throw new Error(data.message || 'Failed to send message.');
      }

      renderMessages(data.messages, data.currentUserId);
      messageInput.value = '';
      chatBox.scrollTop = chatBox.scrollHeight;
    } catch (error) {
      messageError.textContent = error.message;
    }
  });

  loadMessages();
}