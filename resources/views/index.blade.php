<!DOCTYPE html>
<html>
<head>
        <title>โกวาจี</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/style.css">

        <?php include(base_path().'/resources/views/templates/tableTemplate.handlebars'); ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js"></script>
        <script src="/js/script.js"> </script>
</head>

    <body>
    <div>
        <div class="w3-sidebar w3-bar-block" id="sidebar">
            <div id="logo">
                <img src="/image/toplogo01.gif" alt="logo">
            </div>
            {{-- <h3 class="w3-bar-item">Menu</h3> --}}
            <a href="#" id="mainPage"  class="w3-bar-item menu">หน้าแรก</a>
            <a href="#" id="allDisplay" class="w3-bar-item menu">ค้นหารายวิชา</a>
            <a href="#" id="courseDisplay" class="w3-bar-item menu">รายวิชาที่ลงทะเบียน</a>
            {{-- <a href="/logout" class="w3-bar-item menu">ออกจากระบบ</a> --}}
            <a href="#" class="w3-bar-item menu"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                ออกจากระบบ
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>

            <hr>
            <p class="w3-bar-item">กดด้านล่าง หรือกดปุ่ม Spacebar ค้างไว้เพื่อพูด</p>
            <div class="w3-bar-item">
                <button class="w3-btn w3-hover-opacity w3-red w3-section w3-round w3-border-bottom w3-border-indigo  w3-layout-cell time" id ="record" >เริ่มการอัดเสียง <i class="fa fa-microphone" style="font-size:25px"></i></button>
                <form id="uploadForm" enctype="multipart/form-data">
                    <input type="file" name="wavfile" id="wavfile">
                    <button type="submit">upload</button>
                </form> 
            </div>
        </div>

        <div id="page">
            <div class="w3-container w3-center" id="topBar">
                เข้าสู่ระบบลงทะเบียนเรียน
            </div>
            <div id ="contain">
                <div  id="top" class ="w3-center">
                </div>

                <div id ="mid" class ="w3-center w3-section">
                    <h1 class="txt-center">
                    ขณะนี้ท่านได้เข้าสู่ระบบลงทะเบียนเรียนแล้ว
                    <br>กรุณาเลือกบริการที่ต้องการจากรายการด้านซ้ายมือ
                    <br>
                    <br>นิสิตต้องกด <span style="color: #f00">ออกจากระบบ</span> ทุกครั้งที่เสร็จสิ้นการใช้งาน
                    <br>เพื่อมิให้ผู้อื่นเข้าใช้งาน ในชื่อของท่านได้
                    </h1>
                    {{-- <img id ="mainImage"  src="image/microphone.png"> --}}
                </div>

                <div id ="wavAPI" class ="w3-center">
                </div>

                <div id ="bottom" class ="w3-center">
                    {{-- <button class="w3-btn w3-hover-opacity w3-indigo w3-section w3-round w3-border-bottom w3-border-indigo  w3-layout-cell time" id ="record" >เริ่มการอัดเสียง <i class="fa fa-microphone" style="font-size:25px"></i></button> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="recording not-rec">
        RECORDING...
    </div>
    <script>
        $("#uploadForm").submit(function(e){
            e.preventDefault();
            console.log('executing...')
            execute($('#wavfile')[0].files[0])
        });
    </script>
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

</html>
