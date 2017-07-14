
  // $(document).keypress(function(e) {
  //       if(e.which == 13) {
  //           window.myFunction();    
  //       }
  //   }); 

  function mysqlConnect(){

  $.post("assets/php/mysqlconnect.php", function(data, status) {

      if (data) {

      } else {
        // window.location.replace("public/503.html"); 
        console.log("Unable to connect to MySQL"); }
  });

}

  function reload() {
    
    document.forms["newcase"]["subject"].value = null;
    document.forms["newcase"]["c_name"].value = null;
    document.forms["newcase"]["c_number"].value = null;
    document.forms["newcase"]["c_email"].value = null;
    document.forms["newcase"]["c_company"].value = null;
    document.forms["newcase"]["estimate_id"].value = null;
    document.forms["newcase"]["urgent"].selectedIndex[0];
    document.forms["newcase"]["desc"].value = null;
    document.forms["newcase"]["request_date"].value = null;
    document.forms["newcase"]["duty"].unselect;
    document.forms["newcase"]["action_email"].value = null;
    // location.reload();

  }


  function newCase() {    

        subject = document.forms["newcase"]["subject"].value;
        c_name = document.forms["newcase"]["c_name"].value;
        c_number = document.forms["newcase"]["c_number"].value;
        c_email = document.forms["newcase"]["c_email"].value;
        c_company = document.forms["newcase"]["c_company"].value;
        estimate_id = document.forms["newcase"]["estimate_id"].value;
        project_name = document.forms["newcase"]["project_name"].value;
        duty = document.forms["newcase"]["duty"].value;
        action_email = document.forms["newcase"]["action_email"].value;        
        file = "";
        picture = "";
        urgent = document.forms["newcase"]["urgent"].value;
        desc = document.forms["newcase"]["desc"].value;
        request_date = document.forms["newcase"]["request_date"].value;

        console.log(
          subject+"-"+
          c_name+"-"+
          c_number+"-"+
          c_email+"-"+
          c_company+"-"+
          estimate_id+"-"+
          project_name+"-"+
          duty+"-"+
          file+"-"+
          picture+"-"+
          urgent+"-"+
          desc+"-"+
          request_date);

        if (  subject       == "" ||
              c_name        == "" ||
              c_number      == "" ||
              c_company     == "" ||
              estimate_id   == "" ||
              project_name  == "" ||
              duty          == "" ||
              urgent        == "" ||              
              request_date  == "" ) {

          alert("Some field empty data");

        } else {

          if (confirm("Are You Sure!!!") == true) {

          $.post("assets/php/newcase.php", {

              subject         :subject      ,
              contact_name    :c_name       ,
              contact_number  :c_number     ,
              contact_email   :c_email      ,
              contact_company :c_company    ,
              estimate_id     :estimate_id  ,
              project_name    :project_name ,
              duty            :duty         ,
              file            :file         ,
              picture         :picture      ,
              urgent          :urgent       ,
              description     :desc         ,
              request_date    :request_date 

              }, function(data,status) {



                if (data) {

                  console.log("Comeback Page");

                  // alert(data); 
                  
                  var json = JSON.parse(data);
                  // alert(json.status + " : " + json.data.case_id + " : " + json.message + " : " + json.data.error + " : " + json.data.number);
                                    
                  // var json = $.parseJSON(data);        
                  // alert("User: " + json.user + "\nPass: " + json.pass + "\nStatus: " + status);
                  // alert(calcMD5(document.getElementById("user").value) + "\n" + json.user);
                  // alert(document.forms["login"]["user"].value + "\n" + json.user);

                  // if (document.forms["login"]["user"].value == json.user && 
                  //     calcMD5(document.forms["login"]["pass"].value) == json.pass) {

                  //   window.location.replace("service-job-insert.html");
                  //   console.log(document.forms["login"]["user"].value + " Login complete");

                  // } else {
                  //   alert("Please check your user or password");
                  //   console.log("System authentication fail");
                  // }
    
                  if (document.forms['newcase']['brand[]'].length > 1) {

                    // alert(document.forms['newcase']['brand[]'].length);

                    // var len = document.forms['newcase']['brand[]'].length;
                    // alert('Length is : '+len);
                    
                    // alert(brand[1].value);
                    var type = $(document.forms['newcase']['type[]']).serializeArray(); // Create array of object
                    var brand = $(document.forms['newcase']['brand[]']).serializeArray(); // Create array of object
                    var model = $(document.forms['newcase']['model[]']).serializeArray(); // Create array of object
                    var serial = $(document.forms['newcase']['serial[]']).serializeArray(); // Create array of object
                    var description = $(document.forms['newcase']['desc[]']).serializeArray(); // Create array of object
                    // var jsonConvertedData = JSON.stringify(formData);

                    $.post("assets/php/insert_case_details.php", {

                          case_id : json.data.case_id,          
                          type    : type,
                          brand   : brand,
                          model   : model,
                          serial  : serial,
                          description    : description

                    }, function(data,status) {

                           if (data) {

                                  console.log(data);                  

                                  

                           }

                    });
                  }                    

                  /*send the email when case created*/

                  $.post("assets/php/send_mail.php", {

                  case_id       : json.data.case_id,
                  subject       : subject,
                  c_name        : c_name,
                  c_number      : c_number,
                  c_email       : c_email,
                  action_email  : action_email,
                  c_company     : c_company,
                  estimate_id   : estimate_id,
                  project_name  : project_name,
                  duty          : duty,
                  urgent        : urgent,
                  desc          : desc,
                  request_date  : request_date

                  }, function(data,status) {

                    if (data) {

                     // var jsonMail = JSON.parse(data);
                     alert("Email has been sent");                     
                     console.log("Email has been sent");     

                      // document.forms["showcase"]["s_title"].value = json.data.case_id;
                      // document.forms["showcase"]["s_subject"].value = subject;
                      // document.forms["showcase"]["s_name"].value = c_name;
                      // document.forms["showcase"]["s_number"].value = c_number;
                      // document.forms["showcase"]["s_email"].value = c_email;
                      // document.forms["showcase"]["s_company"].value = c_company;
                      // document.forms["showcase"]["s_estimate_id"].value = estimate_id;
                      // document.forms["showcase"]["s_person"].value = action_email;
                      // document.forms["showcase"]["s_urgent"].value = urgent;
                      // document.forms["showcase"]["s_request_date"].value = request_date;                      

                      // $('#NewCaseModal').modal('show');

                      showreport(json.data.case_id,type,brand,model,serial,description);                       
                      
                    } else {
                      window.location.replace("public/503.html");
                      console.log("System Can\'t received authentication data");
                    } 

                  });



                  
                } else {
                  window.location.replace("public/503.html");
                  console.log("System Can\'t received authentication data");
                }        

              });


          } 

        }        
    
  }

function showreport(case_id,type,brand,model,serial,description){

    var table = document.getElementById("r_devices");
    model.forEach(function(element, index) {

        var row = table.insertRow();
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        cell1.innerHTML = model[index].value;
        cell2.innerHTML = serial[index].value;
        cell3.innerHTML = brand[index].value;
        cell4.innerHTML = type[index].value;
        cell5.innerHTML = description[index].value;

        // document.getElementById('r_case_id').innerHTML = element.value+index;
    });


    // var row = table.insertRow();
    // var cell1 = row.insertCell(0);
    // var cell2 = row.insertCell(1);
    // cell1.innerHTML = "NEW CELL1";
    // cell2.innerHTML = "NEW CELL2";   
                      
    document.getElementById('r_case_id').innerHTML = case_id;
    document.getElementById('r_subject').innerHTML = document.forms["newcase"]["subject"].value;
    document.getElementById('r_c_name').innerHTML = document.forms["newcase"]["c_name"].value;
    document.getElementById('r_c_email').innerHTML = document.forms["newcase"]["c_email"].value;
    document.getElementById('r_c_company').innerHTML = document.forms["newcase"]["c_company"].value;
    document.getElementById('r_c_number').innerHTML = document.forms["newcase"]["c_number"].value;
    document.getElementById('r_project_id').innerHTML = document.forms["newcase"]["estimate_id"].value;
    document.getElementById('r_project_name').innerHTML = document.forms["newcase"]["project_name"].value;
    document.getElementById('r_assign_to').innerHTML = document.forms["newcase"]["duty"].value;
    document.getElementById('r_assign_email').innerHTML = document.forms["newcase"]["action_email"].value;
    document.getElementById('r_request_date').innerHTML = document.forms["newcase"]["request_date"].value;
    document.getElementById('r_urgent').innerHTML = document.forms["newcase"]["urgent"].value;
    document.getElementById('redesc').innerHTML = document.forms["newcase"]["desc"].value;
    $('#NewCaseReport').modal('show');

}

function printlayer(argument) {
                      // body...                      
  var generator = window.open('','name','');
  var layertext = document.getElementById(argument);
  generator.document.write('<!doctype html> <html> <head>');
  generator.document.write('<style> table {font-family: arial, sans-serif; border-collapse: collapse; width: 100%; } td, th {border: 1px solid #dddddd; text-align: left; padding: 8px; width: 50%;} tr:nth-child(even) {background-color: #dddddd; } </style>');
  generator.document.write('</head><body>');
  // generator.document.write(layertext.innerHTML.replace("Print Me"));
  generator.document.write(layertext.outerHTML);
  generator.document.write('<hr>');
  generator.document.write('</body></html>');
  generator.document.close();
  generator.focus();
  generator.print();
  generator.close();
}


