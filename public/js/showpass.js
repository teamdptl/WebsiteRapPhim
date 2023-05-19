var showPasswordBtn = document.getElementById('show_password');
var showPasswordRecordBtn = document.getElementById('show_password_record');
var passwordInput = document.getElementById('password');
var passwordRecordInput = document.getElementById('password_record');
var passwordChangePassword = document.getElementById('passwordChangePassword');
var passwordChangePasswordRecord = document.getElementById('passwordChangePasswordRecord');

var showPasswordBtnQuenMK = document.getElementById('show_password_quenMK');
var passwordInputQuenMK = document.getElementById('passwordChangePasswordQuenMK');

var showPasswordRecordBtnQuenMK = document.getElementById('show_password_record_quenMK');
var passwordInputRecordQuenMK = document.getElementById('passwordChangePasswordRecordQuenMK');

if(showPasswordRecordBtnQuenMK){
  showPasswordRecordBtnQuenMK.addEventListener('click', function () {
    if (passwordInputRecordQuenMK.type === 'password') {
      passwordInputRecordQuenMK.type = 'text';
      showPasswordRecordBtnQuenMK.classList.remove('fa-eye');
      showPasswordRecordBtnQuenMK.classList.add('fa-eye-slash');
    } else {
      passwordInputRecordQuenMK.type = 'password';
      showPasswordRecordBtnQuenMK.classList.remove('fa-eye-slash');
      showPasswordRecordBtnQuenMK.classList.add('fa-eye');
    }
  });
}


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

if (showPasswordRecordBtn){
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
}

if(showPasswordBtnQuenMK){
  showPasswordBtnQuenMK.addEventListener('click', function () {
    if (passwordInputQuenMK.type === 'password') {
      passwordInputQuenMK.type = 'text';
      showPasswordBtnQuenMK.classList.remove('fa-eye');
      showPasswordBtnQuenMK.classList.add('fa-eye-slash');
    } else {
      passwordInputQuenMK.type = 'password';
      showPasswordBtnQuenMK.classList.remove('fa-eye-slash');
      showPasswordBtnQuenMK.classList.add('fa-eye');
    }
  });
}
