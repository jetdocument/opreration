var casenumber = "Init Value";

// $(document).bind("contextmenu", function(event) {
//     event.preventDefault();
//     $(".context")
//         .show()
//         .css({top: event.pageY + 15, left: event.pageX + 10});
// });

$(document).click(function() {
  isHovered = $(".context").is(":hover");
  if (isHovered == true) {
    //nothing
  } else {
    $(".context").fadeOut("fast");
  }
});

function getid(){
    alert(casenumber);
    $(".context").fadeOut("fast");
}

function ShowEdit() {

    document.forms["editcase"]["case_id"].value = casenumber;                    

    $.post("assets/php/get_case.php", {
        service_id : casenumber
     }, function(data,status) {

        if (data) {

            // alert(data);                                    
            // alert(data.length);           

            var json = JSON.parse(data);

            document.forms["editcase"]["subject"].value = json[0][0].service_subject;
            document.forms["editcase"]["c_name"].value = json[0][0].contact_name;
            document.forms["editcase"]["c_number"].value = json[0][0].contact_number;
            document.forms["editcase"]["c_email"].value = json[0][0].contact_email;
            document.forms["editcase"]["c_company"].value = json[0][0].contact_company;
            document.forms["editcase"]["estimate_id"].value = json[0][0].estimate_id;
            document.forms["editcase"]["project_name"].value = json[0][0].project_name;
            document.getElementById(json[0][0].urgent).checked = true;
            // document.forms["editcase"]["urgent"].options[json[0][0].urgent].selected;
            document.forms["editcase"]["desc"].value = json[0][0].description;
            // document.forms["editcase"]["request_date"].value = json[0][0].request_date;

            $('#EditCaseModal').modal('show');            

        } else {

            alert("Not get data");

        }        

    });

}


function EditCase() {

  if (confirm("Are you sure?") == true) {

         $.post("assets/php/editcase.php", {

          case_id       : document.forms["editcase"]["case_id"].value,
          subject       : document.forms["editcase"]["subject"].value,
          c_name        : document.forms["editcase"]["c_name"].value,
          c_number      : document.forms["editcase"]["c_number"].value,
          c_email       : document.forms["editcase"]["c_email"].value,
          c_company     : document.forms["editcase"]["c_company"].value,
          estimate_id   : document.forms["editcase"]["estimate_id"].value,
          project_name  : document.forms["editcase"]["project_name"].value,
          urgent        : document.forms["editcase"]["urgent"].value,
          desc          : document.forms["editcase"]["desc"].value

        }, function(data,status) {

               if (data) {

                  alert(data);
                  location.reload();
                  console.log(data);

               }

        });

     }
}

function ShowDevice() {
  $('#ProductModal').modal('show');  
}

function EditProduct() {
  $('#ProductModal').modal('show');  
}
function editproductList() {
  $('#ProductModal').modal('hide');
  document.forms['editdevice']['project_name'].value = "I got it";
  $('#ProductModal').modal('show');
}

function updatecase() {

      var arraydata = 
          document.forms['updateform']['case_id'].value + 
          document.forms['updateform']['status'].value + 
          document.forms['updateform']['desc'].value + 
          document.forms['updateform']['assign'].value + 
          document.forms['updateform']['assign_email'].value ;

      

      if ( 
            document.forms['updateform']['case_id'].value == "" || 
            document.forms['updateform']['status'].Value  == "" ||
            document.forms['updateform']['desc'].value    == "" ) {

            alert("Some field with * need to typing");

      } else {
        
           $.post("assets/php/updatecase.php", {

                   case_id        : document.forms['updateform']['case_id'].value,
                   old_status     : document.forms['updateform']['old_status'].value,
                   status         : document.forms['updateform']['status'].value,
                   desc           : document.forms['updateform']['desc'].value,
                   assign         : document.forms['updateform']['assign'].value,
                   assign_email   : document.forms['updateform']['assign_email'].value

            }, function(data,status) {

                   if (data) {

                        // alert(data);

                        $.post("assets/php/updatecase_send_mail.php", {

                               case_id        : document.forms['updateform']['case_id'].value,
                               old_status     : document.forms['updateform']['old_status'].value,
                               status         : document.forms['updateform']['status'].value,
                               desc           : document.forms['updateform']['desc'].value,
                               assign         : document.forms['updateform']['assign'].value,
                               assign_email   : document.forms['updateform']['assign_email'].value

                        }, function(data,status) {

                               if (data) {

                                  alert("Email hasbeen sent");
                                  location.reload();
                                  console.log(data);

                               }

                        });

                   }

            });
      }
      
       
}

$('.updatecase').click(function() {

document.forms["updateform"]["case_id"].value     = $(this).closest('tr').find("td:eq(3)").text();
document.forms['updateform']['old_status'].value  = $(this).closest('tr').find("td:eq(0)").text();                   

 $.post("assets/php/get_case.php", { service_id : $(this).closest('tr').find("td:eq(3)").text()}, function(data,status) {

        if (data) {

            var json = JSON.parse(data);

            if (json[1].length > 0) {

                /*Clear Table*/
                $("#action_table > tbody:last").children().remove();

                for (var prop in json[1]) {

                    var create_date = mysqlTimeStampToDate(json[1][prop].created_at);                    
                    
                    var tr = $('<tr><td>'+json[1][prop].action_time+'</td>'
                                +'<td>'+json[1][prop].status+'</td>'
                                +'<td>'+create_date.getUTCDate()+'/'+(create_date.getMonth()+1)+'/'+create_date.getFullYear()+'</td>'
                                +'<td>'+json[1][prop].action_by+'</td>'
                                +'<td>'+json[1][prop].action_to+'</td>'
                                +'<td>'+json[1][prop].action_desc+'</td>'
                                +'</tr>');
                    $('#action_table').append(tr);
                }           

                $('#updateModal').modal('show');

            } else {
                /*Clear Table*/
                $("#action_table > tbody:last").children().remove();               
                $('#updateModal').modal('show');
            }                                    

        } else {

            alert("Not get data");

        }        

    });

});

$('#dataTable').find('tr').closest("tr").contextmenu(function(event) {

  if (user_group == "Admin") {
                            
  casenumber = $(this).find("td:eq(3)").text();
  event.preventDefault();
  $(".context")
    .show()
    .css({top: event.pageY + 15, left: event.pageX + 10});
  }
                           
});

// $('#dataTable').find('tr').click( function(){
$('#dataTable').find('tr').dblclick( function(){

if (user_group == "Admin") {

    var currentRow=$(this).closest("tr"); 

    // var col1=currentRow.find("td:eq(0)").text(); // get current row 1st TD value
    var col2=currentRow.find("td:eq(3)").text(); // get current row 2nd TD

    document.forms["editcase"]["case_id"].value = col2;                    

    $.post("assets/php/get_case.php", {
        service_id : col2
     }, function(data,status) {

        if (data) {

            // alert(data);                                    
            // alert(data.length);           

            var json = JSON.parse(data);

            document.forms["editcase"]["subject"].value = json[0][0].service_subject;
            document.forms["editcase"]["c_name"].value = json[0][0].contact_name;
            document.forms["editcase"]["c_number"].value = json[0][0].contact_number;
            document.forms["editcase"]["c_email"].value = json[0][0].contact_email;
            document.forms["editcase"]["c_company"].value = json[0][0].contact_company;
            document.forms["editcase"]["estimate_id"].value = json[0][0].estimate_id;
            document.forms["editcase"]["project_name"].value = json[0][0].project_name;
            document.getElementById(json[0][0].urgent).checked = true;
            // document.forms["editcase"]["urgent"].options[json[0][0].urgent].selected;
            document.forms["editcase"]["desc"].value = json[0][0].description;
            // document.forms["editcase"]["request_date"].value = json[0][0].request_date;

            $('#EditCaseModal').modal('show');

            // alert(json[1].length);

            // if (json[1].length > 0) {

            //     // alert(json[0][0].service_subject);
            //     // alert(json[1][0].action_by);                                    
            //     // $('#action_table').remove("tr:gt(0)");
            //     $("#action_table > tbody:last").children().remove();
            //     for (var prop in json[1]) {                                        
            //         // alert("Value:" + json[1][prop].action_time);
            //         var tr = $('<tr><td>'+json[1][prop].action_time+'</td>'
            //                     +'<td>'+json[1][prop].created_at+'</td>'
            //                     +'<td>'+json[1][prop].action_by+'</td>'
            //                     +'<td>'+json[1][prop].action_desc+'</td>'
            //                     +'</tr>');
            //         $('#action_table').append(tr);
            //     }

            //     // alert(json[0][0].service_subject);
            //     document.forms["action"]["case_status"].value = json[0][0].status;
            //     document.forms["action"]["case_id"].value = json[0][0].service_id;
            //     document.forms["action"]["subject"].value = json[0][0].service_subject;

            //     $('#EditCaseModal').modal('show');

            //     // json.data.case_id

            // } else {

            //     $("#action_table > tbody:last").children().remove();

            //     document.forms["action"]["case_status"].value = json[0][0].status;
            //     document.forms["action"]["case_id"].value = json[0][0].service_id;
            //     document.forms["action"]["subject"].value = json[0][0].service_subject;

            //     $('#EditCaseModal').modal('show');

            // }                                    

        } else {

            alert("Not get data");

        }        

    });


}
  
});


function mysqlTimeStampToDate(timestamp) {
  //function parses mysql datetime string and returns javascript Date object
  //input has to be in this format: 2007-06-05 15:26:02
  var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
  var parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
  return new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
}

                            