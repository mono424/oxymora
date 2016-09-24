var form = document.querySelector('#loginForm');
var userInput = document.querySelector('#userInput');
var passInput = document.querySelector('#passInput');
var button = document.querySelector('#submitButton');
var errorbox = document.querySelector('#errorbox');

function login(){
  button.innerText = "Loading...";
  $.get("php/ajax_login.php?user="+encodeURIComponent(userInput.value)+"&pass="+encodeURIComponent(passInput.value), function(data){
    data = JSON.parse(data);
    if(data.type == "success"){
        button.innerText = "Thank you!";
        window.setInterval(function(){
          window.location.href = "index.php";
        }, 1000);
    }else if(data.type == "error"){
      button.innerText = "Login";
      error(data.message);
    }
  });
  return false;
}


function error(message){
  errorbox.innerHTML = message;
}
