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
        <script src="/js/recorder/recorder.js"></script>
        <script src="/js/audioSetup.js"></script>
        <script src="/js/script.js"> </script>
</head>

    <body>
    <div id = "wrap">
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
            <div class="w3-bar-item w3-center">
                <button class="w3-btn w3-hover-opacity w3-red w3-section w3-round w3-border-bottom w3-border-indigo action-button w3-layout-cell time" id ="record" >กดเพื่อพูด <i class="fa fa-microphone" style="font-size:25px"></i></button>
                <!-- <input class="margin-left" id = "showInput" type="text" placeholder="No file choosen" readonly> -->
                <form id="uploadForm" enctype="multipart/form-data">
                  <label class="w3-btn w3-hover-opacity w3-blue w3-section w3-round w3-border-bottom w3-border-indigo upload action-button btn-file">
                    อัปโหลด .wav <i class="fa fa-upload" style="font-size:25px"></i>
                    <input type="file" name="wavfile" id="wavfile" hidden/>
                  </label>
                    <!-- <label class="w3-btn w3-hover-opacity w3-blue w3-round w3-border-bottom w3-border-indigo btn-pos-correction" for="wavfile" >Choose File</label> -->
                    <!-- <button class="w3-btn w3-hover-opacity w3-blue w3-round w3-border-bottom w3-border-indigo upload" type="submit" >upload</button> -->
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

    <!-- <div class="recording not-rec">
        RECORDING...
    </div> -->
    <script>
        $("#uploadForm").submit(function(e){
            e.preventDefault();
            console.log('executing...')
            execute($('#wavfile')[0].files[0])
            //console.log(currentState);
        });
    </script>

    <div id ="confirm" class="panel-anim panel-hide" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 1">
        <div style="width: 400px; margin: auto; background-color: white; position: relative; top: calc(50% - 150px); padding-top: 24px; border-radius: 6px">
            <p class="allTitile" style="text-align: center; font-size: 1.5em; margin-top: 0px; margin-bottom: 4px">กรุณายืนยันคำสั่ง</p>
            <p id="confirmFunc" style="text-align: center; font-size: 1.5em; margin-top: 0px; margin-bottom: 4px">Function</p>
            <br>
            <p class="w3-center">กดด้านล่าง หรือกดปุ่ม Spacebar ค้างไว้เพื่อพูด</p>
            <div class="w3-row ">
                <div class ="w3-container w3-center w3-half">
                <button class="w3-btn w3-hover-opacity w3-red w3-section w3-round w3-border-bottom w3-border-indigo  w3-layout-cell time action-button" id ="record" >กดเพื่อพูด <i class="fa fa-microphone" style="font-size:25px"></i></button>
                </div>
                <div class ="w3-container w3-half w3-center">
                <!-- <input class="" id = "showInput2" type="text" placeholder="No file choosen" readonly> -->
                <form  id="uploadForm2" enctype="multipart/form-data">
                  <label class="w3-btn w3-hover-opacity w3-blue w3-section  w3-round w3-border-bottom w3-border-indigo upload btn-file action-button">
                    อัปโหลด .wav <i class="fa fa-upload" style="font-size:25px"></i>
                    <input type="file" name="wavfile2" id="wavfile2" hidden/>
                  </label>
                </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#uploadForm2").submit(function(e){
            e.preventDefault();
            console.log('executing...')
            execute($('#wavfile2')[0].files[0])
            //console.log(currentState);
        });
    </script>

    <div id ="ajaxBusy" class="panel-anim panel-hide" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 1">
        <div style="width: 400px; margin: auto; background-color: white; position: relative; top: calc(50% - 100px); padding: 24px; border-radius: 6px">
            <p class="allTitile" style="text-align: center; font-size: 2em; margin-top: 0px; margin-bottom: 4px">กำลังถอดความ...</p>
            <div class="text-center"><img id ="load" src="image/loading.gif"></div>
        </div>
    </div>

    <div id="recording" class="panel-anim panel-hide" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 1">
        <div style="width: 400px; margin: auto; background-color: white; position: relative; top: calc(50% - 100px); padding: 24px; border-radius: 6px">
            <p class="allTitile" style="text-align: center; font-size: 1.5em; margin-top: 0px; margin-bottom: 4px">กำลังฟัง... <i class="fa fa-microphone"></i></p>
            <div class="record-circle"></div>
            <!-- <div class="text-center"><img id ="load" src="image/loading.gif"></div> -->
        </div>
    </div>

    <span id ="lastReg" style="position: fixed; top: 95%; left: 50%;">ผลการ Recognition ล่าสุด : none </span>
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
