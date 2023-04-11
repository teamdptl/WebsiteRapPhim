var showPasswordBtn = document.getElementById('show_password');
var showPasswordRecordBtn = document.getElementById('show_password_record');
var passwordInput = document.getElementById('password');
var passwordRecordInput = document.getElementById('password_record');

showPasswordBtn.addEventListener('click', function () {
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    showPasswordBtn.classList.remove('fa-eye');
    showPasswordBtn.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    showPasswordBtn.classList.remove('fa-eye-slash');
    showPasswordBtn.classList.add('fa-eye');
  }
});


showPasswordRecordBtn.addEventListener('click', function () {
  if (passwordRecordInput.type === 'password') {
    passwordRecordInput.type = 'text';
    showPasswordRecordBtn.classList.remove('fa-eye');
    showPasswordRecordBtn.classList.add('fa-eye-slash');
  } else {
    passwordRecordInput.type = 'password';
    showPasswordRecordBtn.classList.remove('fa-eye-slash');
    showPasswordRecordBtn.classList.add('fa-eye');
  }
});