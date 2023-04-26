var sendOtpLink = document.getElementById("sendOtpLink");
sendOtpLink.addEventListener("click", sendOtp);


function sendOtp() {
  var emailInput = document.getElementById("userEmailAddressInput").value;
  console.log(emailInput);


  if (emailInput === '') {
    alert('Please Enter Your Email Address!')
  } 
  else {
  // disable the send OTP button
  var disableotpInput = document.getElementById("otpVerification");
  var sendOtpLink = document.getElementById("sendOtpLink");
  sendOtpLink.disabled = true;
  disableotpInput.disabled  = false;


  // start the timer
  var timeLeft = 300; // in seconds
  var timerId = setInterval(function() {
    if (timeLeft <= 0) {
      // enable the send OTP button and stop the timer
      sendOtpLink.disabled = false;
      clearInterval(timerId);
    } else {
      // update the timer display
      sendOtpLink.textContent = "Resend OTP in " + timeLeft + "s";
      timeLeft--;
    }
  }, 1000);

  // send the emailInput value to sendcode.php
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "send-code.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert("Verification has sent to your email " + emailInput);
        }
       
    };
    xhr.send("textValue=" + emailInput);

  }
  

  
}