function update_information(){

	// document.forms['account']['user_id'].value;
	employee_id = document.forms['account']['employee_id'].value;
    company 	= document.forms['account']['company'].value;
    fname 		= document.forms['account']['fname'].value;
    lname 		= document.forms['account']['lname'].value;
    gender 		= document.forms['account']['gender'].value;
    email 		= document.forms['account']['email'].value;
    phone 		= document.forms['account']['phone'].value;

    if (confirm("You wanna change your information ?") == true) {

    	$.post("assets/php/update_account.php", {

		employee_id : employee_id,
		company : company,
		fname : fname,
		lname : lname,
		gender : gender,
		email : email,
		phone : phone

		}, function(data,status) {

		if (data) {

		alert(data);

		} else {
		window.location.replace("public/503.html");
		console.log("System Can\'t received authentication data");
		}        

		});

    } 	

	

}

function change_pass(){

	if (	document.forms['changePassword']['current'].value == "" ||	
			document.forms['changePassword']['new_pass'].value == "" ||
			document.forms['changePassword']['re_new_pass'].value == ""  ) {

		alert("Please Typing");

	} else {

		current = calcMD5(document.forms['changePassword']['current'].value);
		new_pass = calcMD5(document.forms['changePassword']['new_pass'].value);
		re_new_pass = calcMD5(document.forms['changePassword']['re_new_pass'].value);

		if (new_pass != re_new_pass) {

	      alert("New Password No Match");

	    } else {

		    	if (confirm("Sure!") == true) {

		    		$.post("assets/php/updatepass.php", {

			                     current : current,
			                     new_pass : new_pass

			              }, function(data,status) {

			                     if (data) {

			                     	var json = JSON.parse(data);

			                     	alert(json.message);
			                     	$('#changePassword').modal('hide');
			                     	document.forms['changePassword']['current'].value = null;
			                     	document.forms['changePassword']['new_pass'].value = null;
			                     	document.forms['changePassword']['re_new_pass'].value = null;
			                            
			                     }

			              });	        

				}
	    	
	    }

	}

}