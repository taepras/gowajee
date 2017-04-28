@extends('layouts.app')

@section('content')
<?php include(base_path().'/resources/views/templates/tableTemplate.handlebars'); ?>
<head>
        <title>finalProject</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js">
        </script>
        

</head>

    <body>
    <div>    
     <div class="w3-sidebar w3-light-grey w3-bar-block" style="width:25%">
      <h3 class="w3-bar-item">Menu</h3>
      <a href="#"  id = "mainPage"  class="w3-bar-item w3-button">อัดเสียง</a>
      <a href="#" id = "courseDisplay" class="w3-bar-item w3-button">รายวิชาที่ลงทะเบียน</a>
      <a href="#"  id ="allDisplay" class="w3-bar-item w3-button">รายวิชาทั้งหมด</a>
      <a href="#" class="w3-bar-item w3-button">ออกจากระบบ</a>          
    </div>   
   
    <div style="margin-left:25%">
        <div class="w3-container w3-teal w3-center">
            <div id = "topBar">อัดเสียง</div>
        </div>
        <div id ="contain">
            
        <div  id="top" class ="w3-center">    
        </div>   
            
        <div id ="mid" class ="w3-center w3-section">
            
            <img id ="mainImage"  src="image/microphone.png">
        </div>
            
        <div id ="wavAPI" class ="w3-center"> 
        </div>
            
        <div id ="bottom" class ="w3-center">
         <button class="w3-btn w3-hover-opacity w3-indigo w3-section w3-round w3-border-bottom w3-border-indigo  w3-layout-cell time" id ="record" >เริ่มการอัดเสียง <i class="fa fa-microphone" style="font-size:25px"></i></button> 
        </div>  
        </div>    
    </div>  
    </div>
</body>



<!--
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Courses</h1>
            <div id="slot"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>


<script type="text/javascript">
    var source = $('#table-template').html();
    var tableTemplate = Handlebars.compile(source);

    $.ajax({
        url: 'http://localhost:8000/api/courses/mon/morning',
        method: 'get'
    }).done(function (data) {
        console.log({section:data})
        $('#slot').html(tableTemplate({ section: data }));
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        console.log(jqXHR)
        console.log(textStatus)
        console.log(errorThrown)
    })
</script>
--> 

@endsection
