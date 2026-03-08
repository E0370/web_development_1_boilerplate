const emailInput = document.getElementById('email');
const emailFeedback = document.getElementById('emailFeedback');
const sendBtn = document.getElementById('sendBtn');

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function updateButton() {
  const email = emailInput.value.trim();

  if (email === '') {
    emailFeedback.textContent = '';
    sendBtn.disabled = true;
    return;
  }

  if (!isValidEmail(email)) {
    emailFeedback.textContent = 'Please enter a valid email.';
    sendBtn.disabled = true;
    return;
  }

  emailFeedback.textContent = '';
  sendBtn.disabled = false;
}

emailInput.addEventListener('input', updateButton);
updateButton();