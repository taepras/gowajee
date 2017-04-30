const STATE_IDLE = 0;
const STATE_WAIT_CONFIRM = 1;
const STATE_RECORDING_FUNCTION = 2;
const STATE_RECORDING_CONFIRM = 3;

var currentState = STATE_IDLE;
var pendingFunction = function() {};
var pendingParams = null;

var recorder;
var queryTemplate = function() {};

// main ##########################################################
$(function() {
    initAudio();
    initTemplate();

    // for sound recording and function execution
    registerSoundRecordingCommands();

    $('#mainPage').click(showMainPage);
    $("#allDisplay").click(showAllCourses);
    $("#courseDisplay").click(showEnrolledCourses);
    registerListeners();

    speak(['กรุณาพูดใหม่อีกครั้ง']);
});
// end main ######################################################

function initTemplate() {
    var source = $('#table-template').html();
    queryTemplate = Handlebars.compile(source);
}

function registerListeners () {
    $(".course-id").off();
    $(".course-id").click(function () {
        var id = $(this).data('course-id');
        console.log('1')
        showCourseById({id: id});
    });

    $('#filter').off();
    $('#filter').submit(function(e) {
        e.preventDefault();
        var day = $('#select-day').val();
        var time = $('#select-time').val();
        console.log(day,time)
        if (day && time) {
            showCoursesByDayTime({ day: day, time: time });
        } else {
            alert('กรุณากรอกข้อมูลให้รครบถ้วน')
        }
    });

    $(".register").off();
    $(".register").click(function () {
        var cid = '' + $(this).data('course-id');
        var sno = '' + $(this).data('section-number');
        register({ course: cid, section: sno });
    });

    $(".withdraw").off();
    $(".withdraw").click(function () {
        var cid = '' + $(this).data('course-id');
        withdraw({ course: cid });
    });
}

function registerSoundRecordingCommands() {
    var record = function () {
        $('.recording').removeClass('not-rec');
        if (currentState === STATE_IDLE) {
            startRecording();
            currentState = STATE_RECORDING_FUNCTION;
        }
        else if (currentState === STATE_WAIT_CONFIRM) {
            startRecording();
            currentState = STATE_RECORDING_FUNCTION;
        }
    }
    var execute = function (soundBlob) {
        // try {
            $('.recording').addClass('not-rec');
            if (currentState === STATE_RECORDING_FUNCTION)
                recognizeFunction(soundBlob);
            else if (currentState === STATE_RECORDING_CONFIRM)
                recognizeConfirm(soundBlob); //, pendingFunction);
        // } catch (e) {
        //     alert('อัดเสียงผิดพลาด')
        //     currentState = STATE_IDLE;
        // }
    }

    $('#record').mousedown(record)
    $('#record').mouseup(function () { stopRecording(execute); })
    $(document).keydown(function(e) {
        if (e.keyCode == 32) {
            record();
        }
    });
    $(document).keyup(function(e) {
        if (e.keyCode == 32) {
            stopRecording(execute);
            // execute();
        }
    });
}

function startRecording() {
    console.log('recording')
    // TODO implement this
    record();
}

function recognizeFunction(soundBlob) {
    console.log('recognizeFunction')
    var fd = new FormData();
    fd.append('wavfile', soundBlob, 'sound.wav');

    $.ajax({
        url: 'http://localhost:8000/api/recognize/function',
        method: 'post',
        // contentType: 'application/json',
        data: fd, //{ file: 'xxx' } // TODO get real file
        processData: false,
        contentType: false
    }).done(function(data) {
        console.log(data)
        switch (data.functionName) {
            case 'get_all_courses':
                pendingFunction = showAllCourses;
                break;
            case 'get_enrolled_courses':
                pendingFunction = showEnrolledCourses;
                break;
            case 'get_course_by_id':
                pendingFunction = showCourseById;
                break;
            case 'get_courses_by_time':
                pendingFunction = showCourses;
                break;
            case 'register_course':
                pendingFunction = register;
                break;
            case 'withdraw_course':
                pendingFunction = withdraw;
                break;
        }

        // TODO make sure the format is compatible
        pendingParams = data.params || null;

        if (data.needsConfirm) {
            speak(['are_you_sure'])
            currentState = STATE_WAIT_CONFIRM;
        } else {
            pendingFunction(pendingParams);
            // pendingFunction = function () {};   // reset
            currentState = STATE_IDLE;
        }
    }).fail(function (j, t, e) {
        alert(e)
        currentState = STATE_IDLE;
    });
}

function recognizeConfirm(soundBlob) {
    console.log('recognizeConfirm')
    var fd = new FormData();
    fd.append('wavfile', soundBlob, 'sound.wav');

    $.ajax({
        url: 'http://localhost:8000/api/recognize/confirm',
        method: 'post',
        // contentType: 'application/json',
        data: fd, //{ file: 'xxx' } // TODO get real file
        processData: false,
        contentType: false
    }).done(function(data) {
        if (data.result == 'confirm') {
            pendingFunction(pendingParams);
            currentState = STATE_IDLE;
        } else if (data.result == 'confirm') {
            currentState = STATE_IDLE;
        } else if (data.result == 'confirm') {
            currentState = STATE_WAIT_CONFIRM;
        }
    }).fail(function (j, t, e) {
        alert(e)
        currentState = STATE_IDLE;
    });
}


function showMainPage() {
    console.log('showMainPage')
    location.reload();
}

function showAllCourses() {
    console.log('showAllCourses')
    $("#contain").empty();
    $("#topBar").text("ค้นหารายวิชา");
    $.ajax({
        type: "GET",
        url: 'http://localhost:8000/api/courses/all',
        contentType: "application/json",
        success: function(data) {
            console.log(data);
            var dataFormatted = flipCourseSection(data);
            $("#contain").html(queryTemplate({ section: dataFormatted }));
            registerListeners();
        },
        error: function(error) {
            console.log(error);
        }
    });

}

function showEnrolledCourses(shouldSpeak = true) {
    $("#contain").empty();
    $("#topBar").text("รายวิชาที่ลงทะเบียน");
    $.ajax({
        type: "GET",
        url: 'http://localhost:8000/api/courses/',
        contentType: "application/json",
        success: function(data) {
            console.log(data);
            $("#contain").html(queryTemplate({ section: data, hideFilter: true }));
            registerListeners();

            if(shouldSpeak) {
                if(data.length > 0)
                    speak(getCoursesSpeakList(['registered_list'], data));
                else
                    speck(['no_courses_registered'])
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function showCourseById(params, shouldSpeak = true) {
    var id = params.id;
    $("#contain").empty();
    $("#topBar").text("รายวิชา " + id);
    $.ajax({
        type: "GET",
        url: 'http://localhost:8000/api/courses/' + id,
        contentType: "application/json",
        success: function(data) {
            console.log(data);
            var dataFormatted = flipCourseSection([data]);
            console.log(dataFormatted);
            $("#contain").html(queryTemplate({ section: dataFormatted, hideFilter: true }));
            registerListeners();

            if(shouldSpeak) {
                speak(getSectionsSpeakList(['subj/' + data.name, 'available_for_registering', 'num/' + dataFormatted.length, 'sections_namely'], dataFormatted));
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function showCoursesByDayTime(params, shouldSpeak = true) {
    var day = params.day;
    var time = params.time;
    $("#contain").empty();
    $("#topBar").text("รายวิชาวัน " + day + " " + time);
    $.ajax({
        type: "GET",
        url: 'http://localhost:8000/api/courses/' + day + '/' + time,
        contentType: "application/json",
        success: function(data) {
            console.log(data);
            // var dataFormatted = flipCourseSection(data);
            // console.log(dataFormatted);
            $("#contain").html(queryTemplate({ section: data, day: day, time: time }));
            registerListeners();

            if(shouldSpeak) {
                if(data.length > 0)
                    speak(getCoursesSpeakList(['day/' + day, 'time/' + time, 'available_courses', 'num/' + data.length, 'courses_namely'], data));
                else
                    speak(['no_courses_available_for', 'day/' + day, 'time/' + time]);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function register(params, shouldSpeak = true) {
    console.log('register')
    console.log(params)
    // TODO implement this
    $.ajax({
        url: 'http://localhost:8000/api/courses/',
        method: 'post',
        // contentType: "application/json",
        data: params
    }).done(function (data) {
        console.log(data)
        showEnrolledCourses(false);
        if(shouldSpeak) {
            if (data.success)
                speak(['register', 'subj/' + data.section.course.name, 'section', 'num/' + data.section.section_number, 'success']);
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        console.log(jqXHR)
        console.log(textStatus)
        console.log(errorThrown)
        alert('registration error: ' + errorThrown)
    })
}

function withdraw(params, shouldSpeak = true) {
    console.log('withdraw')
    console.log(params)
    // TODO implement this
    $.ajax({
        url: 'http://localhost:8000/api/courses',
        method: 'delete',
        data: params
    }).done(function (data) {
        console.log(data)
        showEnrolledCourses(false);
        if(shouldSpeak) {
            if (data.success)
                speak(['withdraw', 'subj/' + data.course.name, 'success']);
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        console.log(jqXHR)
        console.log(textStatus)
        console.log(errorThrown)
        alert('withdrawal error: ' + errorThrown)
    })
}



// misc

function flipCourseSection (data) {
    // flip course-section inside-out to make section-course structure
    var dataFormatted = [];
    for (var i = 0; i < data.length; i++) {
        var courseInfo = data[i];
        for(var j = 0; j < data[i].sections.length; j++) {
            var section = data[i].sections[j];
            section.course = courseInfo;
            dataFormatted.push(section);
        }
    }
    return dataFormatted;
}

function speak(words, rate = 1.6) {
    audios = [];
    loadedCount = 0;
    for (var i = 0; i < words.length; i++) {
        var audio = new Audio('/tts/' + words[i] + '.wav');
        audios.push(audio);
        audio.addEventListener('loadedmetadata', function() {
            loadedCount++;

            if (loadedCount >= words.length) {
                startSpeaking(audios, rate)
            }
        });
    }
}

function startSpeaking(audios, rate) {
    var accDuration = 0;
    for (var i = 0; i < audios.length; i++) {
        audios[i].playbackRate = rate;
        delayedSpeak(audios[i], accDuration / rate * 1000 - i * 60 / rate);
        accDuration += audios[i].duration;
    }
}

function delayedSpeak(audio, delay) {
    setTimeout(function(){
        audio.play();
    }, delay);
}

function getCoursesSpeakList(speakList, data) {
    for (var i = 0; i < data.length; i++) {
         speakList.push('subj/' + data[i].course.name);
         speakList.push('section');
         speakList.push('num/' + data[i].section_number);
     }
     return speakList;
}

function getSectionsSpeakList(speakList, data) {
    for (var i = 0; i < data.length; i++) {
        //  speakList.push('subj/' + data[i].course.name);
         speakList.push('section');
         speakList.push('num/' + data[i].section_number);
         speakList.push('study');
         speakList.push('day/' + data[i].day);
         speakList.push('time/' + data[i].time);
     }
     return speakList;
}

// $(document).ready(function() {
//     //แยก user กับ admin
//     var test1 = "user";
//     if (test1 == "admin") {
//         $('#all').hide();
//     }
//
//     //เรื่มอัดเสียง
//     $("#record").click(function() {
//         $("#bottom").empty();
//         $("#wavAPI").append("<div>ส่วนของ Wav API</div>");
//         $("#bottom").append("<button class =\"w3-btn w3-hover-opacity w3-indigo w3-section w3-round w3-border-bottom w3-border-indigo\" id = \"submit1\">submit</button>&nbsp;");
//
//         $("#submit1").click(function() {
//             /*
//             function recognize(input) {
//             $.ajax({
//             type: "POST",
//             url: 'http://127.0.0.1:8000/api/recognize/function',
//             data: xxx.wav ,
//             contentType : "wav",
//             success: function(response){
//
//             },
//             error: function(error) {
//                 console.log(error);
//             }
//             });
//             }  */
//             $("#top").append("<h2>คำสั่งที่ท่านพูด คือ</h2>");
//             $("#top").append("<h2>แสดงรายวิชาทั้งหมด</h2>");
//             $("#bottom").empty();
//             $("#bottom").append("<button class =\"w3-btn w3-hover-opacity w3-indigo w3-section w3-round w3-border-bottom w3-border-indigo\" id = \"submit2\">submit</button>&nbsp;");
//
//             $("#submit2").click(function() {
//                 $("#top").empty();
//                 $("#mid").empty();
//                 $("#bottom").empty();
//                 $("#wavAPI").empty();
//                 $("#top").append("<h2>ผลลัพธ์ที่ได้จากการทำงาน </h2>");
//                 $("#bottom").append("<button class =\"w3-btn w3-hover-opacity w3-indigo w3-section w3-round w3-border-bottom w3-border-indigo\" id = \"reset\">เริ่มใหม่</button>&nbsp;");
//                 $("#reset").click(function() {
//                     location.reload();
//                 });
//                 //test call
//                 function_name = "get_all_courses";
//                 if (function_name == "get_all_courses") {
//                     $.ajax({
//                         type: "GET",
//                         url: 'http://localhost:8000/api/courses/all',
//                         contentType: "application/json",
//                         success: function(data) {
//                             result = JSON.stringify(data, null, 4)
//                             console.log(result);
//                             $("#mid").append("<p>" + result + "</p>");
//                         },
//                         error: function(error) {
//                             console.log(error);
//                         }
//                     });
//                 }
//             });
//         });
//     });
//
//
//
//
//     $("#mainPage").click(function() {
//     });
//
//
//
//     //ส่งเสียง wav ไปบัง asr
//
// });
