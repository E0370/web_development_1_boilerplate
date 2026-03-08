const resetBtn = document.getElementById('resetBtn');

const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('confirmPassword');
const confirmFeedback = document.getElementById('confirmFeedback');

const rules = {
  length: document.getElementById('rule-length'),
  uppercase: document.getElementById('rule-uppercase'),
  lowercase: document.getElementById('rule-lowercase'),
  number: document.getElementById('rule-number'),
  special: document.getElementById('rule-special')
};

function validateRules() {
  const pwd = passwordInput.value;

  const lengthValid = pwd.length >= 8;
  const uppercaseValid = /[A-Z]/.test(pwd);
  const lowercaseValid = /[a-z]/.test(pwd);
  const numberValid = /[0-9]/.test(pwd);
  const specialValid = /[\W]/.test(pwd);

  rules.length.classList.toggle('passed', lengthValid);
  rules.uppercase.classList.toggle('passed', uppercaseValid);
  rules.lowercase.classList.toggle('passed', lowercaseValid);
  rules.number.classList.toggle('passed', numberValid);
  rules.special.classList.toggle('passed', specialValid);

  return lengthValid && uppercaseValid && lowercaseValid && numberValid && specialValid;
}

function validateConfirm() {
  const pwd = passwordInput.value;
  const confirm = confirmInput.value;

  if (confirm === '') {
    confirmFeedback.textContent = '';
    return false;
  }

  if (pwd !== confirm) {
    confirmFeedback.textContent = 'Passwords do not match.';
    return false;
  }

  confirmFeedback.textContent = '';
  return true;
}

function updateButton() {
  resetBtn.disabled = !(validateRules() && validateConfirm());
}

passwordInput.addEventListener('input', updateButton);
confirmInput.addEventListener('input', updateButton);
updateButton();