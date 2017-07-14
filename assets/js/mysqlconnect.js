

function mysqlConnect(){

	$.post("assets/php/mysqlconnect.php", function(data, status) {

	    if (data) {

	    } else {
	      // window.location.replace("public/503.html"); 
	      console.log("Unable to connect to MySQL"); }
	});

}
