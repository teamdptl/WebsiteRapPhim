const inputs = document.querySelectorAll(".input_otp");
var btnConfirm = document.getElementById("btnConfirm");
var modal = document.getElementById("myModal");
var sendBackOtp = document.getElementById("sendBackOtp");

// iterate over all inputs
inputs.forEach((input, index1) => {
input.addEventListener("keyup", (e) => {
  // This code gets the current input element and stores it in the currentInput variable
  // This code gets the next sibling element of the current input element and stores it in the nextInput variable
  // This code gets the previous sibling element of the current input element and stores it in the prevInput variable
  const currentInput = input,
    nextInput = input.nextElementSibling,
    prevInput = input.previousElementSibling;

  // if the value has more than one character then clear it
  if (currentInput.value.length > 1) {
    currentInput.value = "";
    return;
  }
  // if the next input is disabled and the current value is not empty
  //  enable the next input and focus on it
  if (nextInput && nextInput.hasAttribute("disabled") && currentInput.value !== "") {
    nextInput.removeAttribute("disabled");
    nextInput.focus();
  }

  // if the backspace key is pressed
  if (e.key === "Backspace") {
    // iterate over all inputs again
    inputs.forEach((input, index2) => {
      // if the index1 of the current input is less than or equal to the index2 of the input in the outer loop
      // and the previous element exists, set the disabled attribute on the input and focus on the previous element
      if (index1 <= index2 && prevInput) {
        input.setAttribute("disabled", true);
        input.value = "";
        prevInput.focus();
      }
    });
  }
  //if the fourth input( which index number is 3) is not empty and has not disable attribute then
  //add active class if not then remove the active class.
  if (!inputs[4].disabled && inputs[4].value !== "") {
    btnConfirm.classList.add("active");
    return;
  }
  btnConfirm.classList.remove("active");
});
});

//focus the first input which index is 0 on window load
window.addEventListener("load", () => inputs[0].focus());

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

const getOTPString = () =>{
  let otp = ""
  inputs.forEach(item => otp += item.value);
  return otp;
}

$("#btnConfirm").click((e)=>{
  e.preventDefault();
  $.ajax({
    url: "/signup/otp",
    method: "POST",
    data: {
      otp: getOTPString(),
      email: $("#email").val()
    },
    success: function(response) {
      console.log(JSON.parse(response));
      let data =  JSON.parse(response);

      if(data.status == 3 ){
        Swal.fire({
          icon: 'error',
          title: 'Vui lòng nhập lại',
          text: data.message,
      })
      }

      if(data.status == 1){
        inputs.forEach((input) => { 
          input.value = "";
          const nextInput = input.nextElementSibling;
          if (nextInput) {
            nextInput.disabled = true;
          }
        });
        btnConfirm.classList.remove("active");
        modal.style.display = "none";
        $.ajax({
          url: "/signin",
          method: "POST",
          data: {
              email: data.email,
              password: data.password,
          },
          success: function(res){
              console.log(res);
              location.reload();
          },
          
      })
        location.reload();
      }

      if(data.status == 4 ){
        Swal.fire({
          icon: 'error',
          title: 'Vui lòng nhấn gửi lại OTP',
          text: data.message,
      });
      sendBackOtp.style.display = 'flex';
      }
    }
  })
})
