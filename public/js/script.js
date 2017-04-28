$(document).ready(function(){  
   //แยก user กับ admin
   var test1 = "user";
   if(test1 =="admin"){
         $('#all').hide();
   }
   
   //เรื่มอัดเสียง
    $("#record").click(function(){        
        $("#bottom").empty(); 
        $("#wavAPI").append("<div>ส่วนของ Wav API</div>");   
        $("#bottom").append("<button class =\"w3-btn w3-hover-opacity w3-indigo w3-section w3-round w3-border-bottom w3-border-indigo\" id = \"submit1\">submit</button>&nbsp;");   
    
        $("#submit1").click(function(){
            /*
            function recognize(input) {
            $.ajax({
            type: "POST",
            url: 'http://127.0.0.1:8000/api/recognize/function',
            data: xxx.wav ,
            contentType : "wav",
            success: function(response){
               
            },
            error: function(error) {
                console.log(error);
            }
            });
            }  */ 
            $("#top").append("<h2>คำสั่งที่ท่านพูด คือ</h2>");   
            $("#top").append("<h2>แสดงรายวิชาทั้งหมด</h2>");
            $("#bottom").empty(); 
            $("#bottom").append("<button class =\"w3-btn w3-hover-opacity w3-indigo w3-section w3-round w3-border-bottom w3-border-indigo\" id = \"submit2\">submit</button>&nbsp;");   
 
            $("#submit2").click(function(){ 
            $("#top").empty();   
            $("#mid").empty();       
            $("#bottom").empty();   
            $("#wavAPI").empty();          
            $("#top").append("<h2>ผลลัพธ์ที่ได้จากการทำงาน </h2>");  
            $("#bottom").append("<button class =\"w3-btn w3-hover-opacity w3-indigo w3-section w3-round w3-border-bottom w3-border-indigo\" id = \"reset\">เริ่มใหม่</button>&nbsp;");   
            $("#reset").click(function(){  
             location.reload();
            });
            //test call 
            function_name = "get_all_courses";    
            if(function_name == "get_all_courses") {
                $.ajax({
                type: "GET",
                url: 'http://localhost:8000/api/courses/all',
                contentType : "application/json",    
                success: function(data){
                  result = JSON.stringify(data, null, 4)    
                  console.log(result);
                  $("#mid").append("<p>"+result+"</p>");  
                },
                error: function(error) {
                    console.log(error);
                }
                });  
            }    
            });   
        });                    
    });
    
    $("#allDisplay").click(function(){ 
      $("#contain").empty();
      $("#topBar").text("รายวิชาทั้งหมด");
      function_name = "get_all_courses";    
            if(function_name == "get_all_courses") {
                $.ajax({
                type: "GET",
                url: 'http://localhost:8000/api/courses/all',
                contentType : "application/json",    
                success: function(data){
                  result = JSON.stringify(data, null, 4)    
                  console.log(result);
                  $("#contain").append("<p>"+result+"</p>");  
                },
                error: function(error) {
                    console.log(error);
                }
                });  
        }    
      });   
      $("#courseDisplay").click(function(){ 
      $("#contain").empty();
      $("#topBar").text("รายวิชาที่ลงทะเบียน");
      function_name = "get-enrolled";    
            if(function_name == "get-enrolled") {
                $.ajax({
                type: "GET",
                url: 'http://localhost:8000/api/courses/',
                contentType : "application/json",    
                success: function(data){
                  result = JSON.stringify(data, null, 4)    
                  console.log(result);
                  $("#contain").append("<p>"+result+"</p>");  
                },
                error: function(error) {
                    console.log(error);
                }
                });  
        }    
      });   
     $("#mainPage").click(function(){ 
        location.reload();  
     });  
    
    
    
    //ส่งเสียง wav ไปบัง asr
    
});   
