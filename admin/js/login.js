var form = document.querySelector('#loginForm');
var userInput = document.querySelector('#userInput');
var passInput = document.querySelector('#passInput');
var button = document.querySelector('#submitButton');
var errorbox = document.querySelector('#errorbox');
var successbox = document.querySelector('#successbox');

function login(){
  button.innerText = "Loading...";
  $.get("php/ajax_login.php?user="+encodeURIComponent(userInput.value)+"&pass="+encodeURIComponent(passInput.value), function(data){
    data = JSON.parse(data);
    if(data.type == "success"){
        success("Successful, redirecting...");
        window.setInterval(function(){
          window.location.href = "index.php";
        }, 1500);
    }else if(data.type == "error"){
      button.innerText = "Login";
      error(data.message);
    }
  });
  return false;
}


function error(message){
  errorbox.innerHTML = message;
  $(errorbox).css("display", "block");
}

function success(message){
  successbox.innerHTML = message;
  $(successbox).css("display", "block");
  $(errorbox).css("display", "none");
}
