
"use strict";
/*get variables from form and check rules*/
function validate(){
	
	var errMsg = "";								/* stores the error message */
	var result = true;								/* assumes no errors */
	
	var firstname = document.getElementById("fname").value;
	if (!firstname.match(/^[a-zA-Z]+$/)){
		errMsg = errMsg + "Your first name must only contain alpha characters\n"
		result = false;  
	}
	
	var lastname = document.getElementById("lname").value;
	if (!lastname.match(/^[a-zA-Z-]+$/)){
		errMsg = errMsg + "Your last name must only contain alpha characters or a hyphen\n"
		result = false;  
	}
	
	var pass = document.getElementById("password").value;
	var i = 0;
	var cha = '';
	var num = 0;
	var up = 0;
	var lo = 0;
	var spe = 0;
	
		while (i <= pass.length){	
		character = pass.charAt(i);
			if(isNaN(cha * 1)){
				num++;
			}
			if (cha == cha.toUpperCase()){
				up++;
			}
			if (cha == cha.toLowerCase()){
				lo++;
			}
		}
		
	if ((lo || up || num)==0){
		errMsg = errMsg + "Your passoword must contain a number, contain a lower case and a upper case and a special character\n";
		result = false;  
	}
		
	else if(pass.length < 8){
			errMsg = errMsg + "Your passoword must be 8 or more characters\n";
			result = false;  
	}
	else{
		return result;
	}
}


function init () {
  var register = document.getElementById("register");// link the variable to the HTML element
  echo("potato");
  register.onsubmit = validate;          /* assigns functions to corresponding events */
 }

window.onload = init;