
  // $(document).keypress(function(e) {
  //       if(e.which == 13) {
  //           window.myFunction();    
  //       }
  //   });

  function loginForm() {    
    
    if (document.forms["login"]["user"].value == "") { alert("Enter user"); } 
    else if (document.forms["login"]["pass"].value == "") { alert("Enter Password"); } 
    else { 

          $.post("assets/php/authen.php", {

          user : document.forms["login"]["user"].value,
          pass : calcMD5(document.forms["login"]["pass"].value)

          }, function(data,status) {

            

            if (data) {

              // alert(data);
              var json = JSON.parse(data);
              
              if (json.status == "error") {

                alert(json.status + " : " + json.message + " : " + json.data.error);

              } else {

                // alert(json.data.user + " : " + document.forms["login"]["user"].value + " : " + json.data.pass + " : " + calcMD5(document.forms["login"]["pass"].value));
                if (json.status == "error") {
                  alert(json.message);
                } else {
                  // alert(json.message + " : " + json.data.session);
                  window.location.replace("service-dashboard.html");
                }                
              }



            } else {

            }

            // if (data) {
            //   console.log("Comeback to login page");
            //   var json = JSON.parse(data);
            //   alert(json.status + " : " + json.message + " : " + json.data.user + " : " + json.data.pass + " : " + json.data.error);
                            
            // } else {
            //   // window.location.replace("public/503.html");
            //   // console.log("System Can\'t received authentication data");
            // }
            

            //   alert(json.status + " : " + json.message + " : " + json.data.user + " : " + json.data.pass + " : " + json.data.error);
              
            //   if (document.forms["login"]["user"].value == json.data.user && 
            //       calcMD5(document.forms["login"]["pass"].value) == json.data.pass) {

            //     window.location.replace("service-job-insert.html");
            //     console.log(document.forms["login"]["user"].value + " Login complete");

            //   } else {
            //     alert("Please check your user or password");
            //     console.log("System authentication fail");
            //   }
              
            // } else {
            //   window.location.replace("public/503.html");
            //   console.log("System Can\'t received authentication data");
            // }        

          });
    }    
// 
  }

  function recoverForm() {
    // alert("Recovery Not Available Now!!!" + "\n"  +
    //       "Your Data\n" +          
    //       "Email : " + document.forms["recover"]["email"].value          
    //       );

    if (document.forms["recover"]["email"].value == "") {
      alert("Please typing your email address");
    } else {

      $.post("assets/php/recover.php", {

             email : document.forms["recover"]["email"].value

      }, function(data,status) {

             if (data) {

                    alert(data); 

             }

      });

    }
    

  }
  
  function signupForm() { 

      if (document.forms["signup"]["user"].value == "") { alert("Enter user"); } 
    else if (document.forms["signup"]["email"].value == "") { alert("Enter Email"); }
    else if (document.forms["signup"]["pass"].value != document.forms["signup"]["repass"].value) { alert("Password No Match"); }
    else {        

        $.post("assets/php/signup.php", {

          user    : document.forms["signup"]["user"].value          ,
          pass    : calcMD5(document.forms["signup"]["pass"].value) ,

          company : document.forms["signup"]["company"].value       ,
          fname   : document.forms["signup"]["fname"].value         ,
          lname   : document.forms["signup"]["lname"].value         ,
          phone   : document.forms["signup"]["phone"].value         ,
          email   : document.forms["signup"]["email"].value         ,
          gender  : document.forms["signup"]["gender"].value           

          }, function(data,status) {

            if (data) {
              console.log("Comeback to signup page " + document.forms["signup"]["fname"].value);
              var json = JSON.parse(data);
              // alert(json.status + " : " + json.message + " : " + json.data.user + " : " + json.data.pass + " : " + json.data.error);
              alert("Welcome to service system ");
              window.location.replace("login.html");

            } else {
              window.location.replace("public/503.html");
              // console.log("System Can\'t received authentication data");
            }        

          });



        // $.post("assets/php/signup.php", function(data, status) {
        //   alert("Waiting");
        // }

        // $.post("assets/php/signup.php", {

        //   user: document.forms["signup"]["user"].value,
        //   pass: calcMD5(document.forms["signup"]["pass"].value),
        //   email: document.forms["signup"]["email"].value
        //   }, function(data,status) {
        //         if (data) {

        //             console.log("Comeback Page");

        //             alert(data); 
                    
        //             // var json = JSON.parse(data);

        //             // alert(json.status + " : " + json.data.case_id + " : " + json.message + " : " + json.data.error + " : " + json.data.number);
                   
        //         } else {

        //           alert("System Can't get data please try again");
        //           // window.location.replace("public/503.html");
        //           // console.log("System Can\'t received authentication data");

        //         }
        //   }
    }       

    // alert("Signup Not Available Now!!!" + "\n"  +
    //       "Your Data\n" +
    //       "User : " + document.forms["signup"]["user"].value + "\n" +
    //       "Password : " + calcMD5(document.forms["signup"]["pass"].value) + "\n" +
    //       "Re-Password : " + calcMD5(document.forms["signup"]["repass"].value) + "\n" +
    //       "Company : " + document.forms["signup"]["company"].value + "\n" +
    //       "F name : " + document.forms["signup"]["fname"].value + "\n" +
    //       "L nmae : " + document.forms["signup"]["lname"].value + "\n" +
    //       "Phone : " + document.forms["signup"]["phone"].value + "\n" +
    //       "Email : " + document.forms["signup"]["email"].value + "\n" +
    //       "Gender : " + document.forms["signup"]["gender"].value
    //       ); 


          // user_id : document.forms["signup"]["user"].value  ,
          // pass    : document.forms["signup"]["pass"].value  ,

          // company : document.forms["signup"]["company"].value ,
          // fname   : document.forms["signup"]["fname"].value ,
          // lname   : document.forms["signup"]["lname"].value ,
          // phone   : document.forms["signup"]["phone"].value ,
          // email   : document.forms["signup"]["email"].value ,
          // gender  : document.forms["signup"]["gender"].value   
    // window.location.replace("service-job-insert.html);
      
  }
