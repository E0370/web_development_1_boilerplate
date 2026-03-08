(() => {
  'use strict';

  const forms = document.querySelectorAll('.needs-validation');

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();

const submitBtn = document.getElementById('submitBtn');

const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm-password');
const confirmFeedback = document.getElementById('confirmpassword-feedback');

const rules = {
  length: document.getElementById('rule-length'),
  uppercase: document.getElementById('rule-uppercase'),
  lowercase: document.getElementById('rule-lowercase'),
  number: document.getElementById('rule-number'),
  special: document.getElementById('rule-special')
};

function validatePasswordRules() {
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

function validateConfirmPassword() {
  const pwd = passwordInput.value;
  const confirmPwd = confirmPasswordInput.value;

  if (confirmPwd === '') {
    confirmFeedback.textContent = '';
    return false;
  }

  if (pwd !== confirmPwd) {
    confirmFeedback.textContent = 'Passwords do not match.';
    return false;
  }

  confirmFeedback.textContent = '';
  return true;
}

function updateSubmitButton() {
  const rulesOk = validatePasswordRules();
  const confirmOk = validateConfirmPassword();
  submitBtn.disabled = !(rulesOk && confirmOk);
}

passwordInput.addEventListener('input', updateSubmitButton);
confirmPasswordInput.addEventListener('input', updateSubmitButton);

if (submitBtn) submitBtn.disabled = true;