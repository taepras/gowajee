const STATE_IDLE = 0;
const STATE_WAIT_CONFIRM = 1;
const STATE_RECORDING_FUNCTION = 2;
const STATE_RECORDING_CONFIRM = 3;
const STATE_RECOGNIZING_FUNCTION = 4;
const STATE_RECOGNIZING_CONFIRM = 5;

var currentState = STATE_IDLE;
var pendingFunction = function() {};
var pendingParams = null;
var courseName = "";
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
    uploadButton();

    //speak(['กรุณาพูดใหม่อีกครั้ง']);
});
// end main ######################################################

function uploadButton(){
    $("input[type=file]").change(function(){
        //left bar
        var file_name = $('#wavfile').val();
        if(file_name!=""){
            var file_name_index = file_name.lastIndexOf("\\") + 1;
            file_name = file_name.substring(file_name_index, file_name.length);
            $("#showInput").attr("placeholder", file_name);
        }
        //show when confirm
        file_name = $('#wavfile2').val();
        if(file_name!=""){
            var file_name_index = file_name.lastIndexOf("\\") + 1;
            file_name = file_name.substring(file_name_index, file_name.length);
            $("#showInput2").attr("placeholder", file_name);
        }

    });
}

function initTemplate() {
    var source = $('#table-template').html();
    queryTemplate = Handlebars.compile(source);
    Handlebars.registerHelper('thai', function(str){
        var wordMap = {
            'mon': 'วันจันทร์',
            'tue': 'วันอังคาร',
            'wed': 'วันพุธ',
            'thu': 'วันพฤหัสบดี',
            'fri': 'วันศุกร์',
            'morning': 'เช้า',
            'afternoon': 'บ่าย'
        };
        return wordMap[str];
    });
}

function registerListeners() {
    $(".course-id").off();
    $(".course-id").click(function() {
        var id = $(this).data('course-id');
        console.log('1')
        showCourseById({
            course: id
        });
    });

    $('#filter').off();
    $('#filter').submit(function(e) {
        e.preventDefault();
        var day = $('#select-day').val();
        var time = $('#select-time').val();
        console.log(day, time)
        if (day && time) {
            showCoursesByDayTime({
                day: day,
                time: time
            });
        } else {
            alert('กรุณากรอกข้อมูลให้รครบถ้วน')
        }
    });

    $(".register").off();
    $(".register").click(function() {
        var cid = '' + $(this).data('course-id');
        var sno = '' + $(this).data('section-number');
        register({
            course: cid,
            section: sno
        });
    });

    $(".withdraw").off();
    $(".withdraw").click(function() {
        var cid = '' + $(this).data('course-id');
        withdraw({
            course: cid
        });
    });

    $('#wavfile, #wavfile2').off();
    $('#wavfile, #wavfile2').change(function () {
        console.log('sending file...')
        $(this).trigger('blur');
        var uploadForm = $(this).closest('form')
        uploadForm.submit();
        uploadForm.get(0).reset();
    });

    $('.fader').off();
    $('.fader').click(function (e) {
        console.log(e.target, this, e.target == this)
        if (e.target == this)
            setState(STATE_IDLE);
    });
}

function setState(nextState) {
    console.log('changing from state', currentState, 'to', nextState)
    if (currentState === nextState) {
        return;
    }
    else if (currentState === STATE_IDLE) {
        if (nextState === STATE_RECORDING_FUNCTION) {
            $('#recording').removeClass('panel-hide');
        } else if(nextState === STATE_RECOGNIZING_FUNCTION){
            $("#ajaxBusy").removeClass('panel-hide');
        } else return;
    }
     else if (currentState === STATE_RECORDING_FUNCTION) {
        $('#recording').addClass('panel-hide');
        if (nextState === STATE_RECOGNIZING_FUNCTION) {
            $("#ajaxBusy").removeClass('panel-hide');
        } else return;
    }
    else if (currentState === STATE_RECOGNIZING_FUNCTION) {
        $("#ajaxBusy").addClass('panel-hide');
        if (nextState === STATE_IDLE) {
            $('#recording').addClass('panel-hide');
        } else if (nextState === STATE_WAIT_CONFIRM) {
            $("#confirm").removeClass('panel-hide');
        } else return;
    }
    else if (currentState === STATE_WAIT_CONFIRM) {
        $("#confirm").addClass('panel-hide');
        if (nextState === STATE_RECORDING_CONFIRM) {
            $('#recording').removeClass('panel-hide');
        } else if(nextState === STATE_RECOGNIZING_CONFIRM){
            $("#ajaxBusy").removeClass('panel-hide');
        } else if (nextState === STATE_IDLE) {
            $('#recording').addClass('panel-hide');
        } else return;
    }
    else if (currentState === STATE_RECORDING_CONFIRM) {
        $('#recording').addClass('panel-hide');
        if (nextState === STATE_RECOGNIZING_CONFIRM) {
           $("#ajaxBusy").removeClass('panel-hide');
        } else return;
    }
    else if (currentState === STATE_RECOGNIZING_CONFIRM) {
        if (nextState === STATE_WAIT_CONFIRM) {
            $("#ajaxBusy").addClass('panel-hide');
            $("#confirm").removeClass('panel-hide');
        } else if (nextState === STATE_IDLE) {
            $("#ajaxBusy").addClass('panel-hide');
            $('#recording').addClass('panel-hide');
        } else return;
    }
    else {
        return;
    }
    currentState = nextState;
}

function execute(blob) {
    // try {
    if (currentState === STATE_RECORDING_FUNCTION || currentState === STATE_IDLE) {
        setState(STATE_RECOGNIZING_FUNCTION);
        console.log("before function: "+ currentState);
        recognizeFunction(blob);
    } else if (currentState === STATE_RECORDING_CONFIRM || currentState === STATE_WAIT_CONFIRM) {
        setState(STATE_RECOGNIZING_CONFIRM);
        recognizeConfirm(blob);
    }
    // } catch (e) {
    //     alert('อัดเสียงผิดพลาด')
    //     setState(STATE_IDLE);
    // }
}

function record() {
    // stopSpeaking();
    if (currentState === STATE_IDLE) {
        startRecording();
        setState(STATE_RECORDING_FUNCTION);
    } else if (currentState === STATE_WAIT_CONFIRM) {
        startRecording();
        setState(STATE_RECORDING_CONFIRM);
    }
}

function registerSoundRecordingCommands() {
    $('#record').mousedown(record);
    $('#record2').mousedown(record);

	$(document).mouseup(function() {
		if(currentState === STATE_RECORDING_CONFIRM || currentState === STATE_RECORDING_FUNCTION)
			stopRecording(execute);
    })
    $('#stop').mousedown(function() {
        stopRecording(execute);
    })



    $(document).keydown(function(e) {
        if (e.keyCode == 32) {
            e.preventDefault();
            record();
        }
    });
    $(document).keyup(function(e) {
        if (e.keyCode == 32) {
            e.preventDefault();
            stopRecording(execute);
        }
    });
}

function startRecording() {
    console.log('recording')
    doRecord();
}



function recognizeFunction(blob) {
    console.log('recognizeFunction')
    var formData = new FormData();
    formData.append('wavfile', blob, 'sound.wav');
    $.ajax({
        url: 'http://localhost:8000/api/recognize/function',
        method: 'post',
        contentType: false,
        processData: false,
        data: formData
    }).done(function(data) {
        console.log(data)

        //for test

        //pendingFunction = showAllCourses; // test function
        // data.needsConfirm = true;
        //data.needsConfirm = false;
        //data.sentence ="teststttts"
        //
        // data.functionName = 'get_course_by_id'; //register
        // data.params = {course : "2502390"}; //getCourseByID
        // data.params = {day : "fri",time : "morning"   } ; //getCourseByDayTime

        //data.params = {course:"0123101" ,section : "2"} ; //register
        //data.functionName = 'register_course'

         //data.params = {course:"3743422"} ; //withdraw
        //data.functionName = 'get_all_courses'; //withdraw

        //end test

        //display recognition result
        $("#confirmFunc").text(data.sentence);
        $("#lastReg").text("ผลการ Recognition ล่าสุด : "+data.sentence);

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
                pendingFunction = showCoursesByDayTime;
                break;
            case 'register_course':
                pendingFunction = register;
                break;
            case 'withdraw_course':
                pendingFunction = withdraw;
                break;
            default:
                pendingFunction = null;   // reset
                break;
        }

        // TODO make sure the format is compatible
        pendingParams = data.params || null;

        if (!pendingFunction) {
            speak(['say_again']);
            setState(STATE_IDLE);
            return;
        }

        if (data.needsConfirm) {
            if (data.params.course == "0123101") {courseName = "PARAGRAP WRITING"; }
            else if (data.params.course == "2313213"){ courseName = "DIGITAL PHOTO"; }
            else if (data.params.course == "2502390") courseName = "INTRO PACK DESIGN";
            else if (data.params.course == "2604362") courseName = "PERSONAL FINANCE";
            else if (data.params.course == "3700105") courseName = "FOOD SCI ART";
            else if (data.params.course == "3743422") courseName = "WEIGHT CONTROL";
            else if (data.params.course == "2110432") courseName = "AUTO SPEECH RECOG";
            else {
                speak(['say_again']); //อัดเสียง - ไม่มีรายวิชานี้ในระบบ
                setState(STATE_IDLE);
                return;
            }
            if (data.functionName == 'register_course') {
                speak(['register', 'subj/' + courseName, 'section', 'num/' + data.params.section, 'are_you_sure']);
            } else if (data.functionName == 'withdraw_course') {
                speak(['withdraw', 'subj/' + courseName, 'are_you_sure']);
            } else {
                console.log("wrong function");
            }
            setState(STATE_WAIT_CONFIRM);
            console.log("check state :"+currentState);
        } else {
            pendingFunction(pendingParams);
            setState(STATE_IDLE);
        }
    }).fail(function(j, t, e) {
        alert(e)
        setState(STATE_IDLE);
    });
}
//
function recognizeConfirm(blob) {
    console.log('recognizeConfirm')
    var formData = new FormData();
    formData.append('wavfile', blob, 'sound.wav');
    $.ajax({
        url: 'http://localhost:8000/api/recognize/confirm',
        method: 'post',
        contentType: false,
        processData: false,
        data: formData
    }).done(function(data) {
        // // for test
        //data.result = 'confirm';
        // // end test
        console.log(data);
        $("#lastReg").text("ผลการ Recognition ล่าสุด : "+data.sentence); //display recognition result
        if (data.result == 'confirm') {
            console.log("pass");
            pendingFunction(pendingParams);
            setState(STATE_IDLE);
        } else if (data.result == 'cancel') {
            if (pendingFunction === register) {
                speak(['cancel_registering']);
            } else {
                speak(['cancel_withdrawing']);
            }
            setState(STATE_IDLE);
        } else { // if (data.result == 'unknown') {
            setState(STATE_WAIT_CONFIRM);
            speak(['say_again']);
        }
    }).fail(function(j, t, e) {
        console.log("pass2");
        alert(e)
        setState(STATE_IDLE);
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
            $("#contain").html(queryTemplate({
                section: dataFormatted
            }));
            registerListeners();
        },
        error: function(error) {
            console.log(error);
        }
    });

}

function showEnrolledCourses(params, shouldSpeak = true) {

    $("#contain").empty();
    $("#topBar").text("รายวิชาที่ลงทะเบียน");
    $.ajax({
        type: "GET",
        url: 'http://localhost:8000/api/courses/',
        contentType: "application/json",
        success: function(data) {
            console.log(data);
            $("#contain").html(queryTemplate({
                section: data,
                hideFilter: true
            }));
            registerListeners();

            if (shouldSpeak) {
                if (data.length > 0)
                    speak(getCoursesSpeakList(['registered_list'], data));
                else
                    speak(['no_courses_registered'])
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function showCourseById(params, shouldSpeak = true) {
    var id = params.course;
    if (id == "0123101") cName = "PARAGRAP WRITING";
    else if (id == "2313213") cName = "DIGITAL PHOTO";
    else if (id == "2502390") cName = "INTRO PACK DESIGN";
    else if (id == "2604362") cName = "PERSONAL FINANCE";
    else if (id == "3700105") cName = "FOOD SCI ART";
    else if (id == "3743422") cName = "WEIGHT CONTROL";
    else if (id == "2110432") cName = "AUTO SPEECH RECOG";
    $("#contain").empty();
    $("#topBar").text("รายวิชา " + cName);
    $.ajax({
        type: "GET",
        url: 'http://localhost:8000/api/courses/' + id,
        contentType: "application/json",
        success: function(data) {
            console.log(data);
            var dataFormatted = flipCourseSection([data]);
            console.log(dataFormatted);
            $("#contain").html(queryTemplate({
                section: dataFormatted,
                hideFilter: true
            }));
            registerListeners();

            if (shouldSpeak) {
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
    if(day == "mon") dayShow ="จันทร์"
    else if(day == "tue") dayShow ="อังคาร"
    else if(day == "wed") dayShow ="พุธ"
    else if(day == "thu") dayShow ="พฤหัสบดี"
    else if(day == "fri") dayShow ="ศุกร์"
    else if(day == "sat") dayShow ="เสาร์"
    else if(day == "sun") dayShow ="อาทิตย์"
    if(time == "morning") timeShow ="เช้า"
    else if(time == "afternoon") timeShow ="บ่าย"
    $("#contain").empty();
    $("#topBar").text("รายวิชา วัน" + dayShow + " " + timeShow);
    $.ajax({
        type: "GET",
        url: 'http://localhost:8000/api/courses/' + day + '/' + time,
        contentType: "application/json",
        success: function(data) {
            console.log(data);
            // var dataFormatted = flipCourseSection(data);
            // console.log(dataFormatted);
            $("#contain").html(queryTemplate({
                section: data,
                day: day,
                time: time
            }));
            registerListeners();

            if (shouldSpeak) {
                if (data.length > 0)
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
    $.ajax({
        url: 'http://localhost:8000/api/courses/',
        method: 'post',
        // contentType: "application/json",
        data: params
    }).done(function(data) {
        console.log(data)
        showEnrolledCourses(params, false);
        if (shouldSpeak) {
            if (data.success)
                speak(['register', 'subj/' + data.section.course.name, 'section', 'num/' + data.section.section_number, 'success']);
            else {
                if(data.message == "Course already registered.") speak(['you_register' , 'subj/' +courseName , "already"]);
                else speak(['say_again']);
            }
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        showCourseById({
            course: pendingParams.course
        }, false);
        if (courseName == "PARAGRAP WRITING" ) speak(['subj/' + courseName,"can_register",'num/1' ,'num/2','num/3','num/4','num/5','num/6','only']);
        else if (courseName == "DIGITAL PHOTO") speak(['subj/' + courseName,"can_register",'num/1' ,'num/2','num/3','num/4','num/5','only']);
        else if (courseName == "INTRO PACK DESIGN")speak(['subj/' + courseName,"can_register",'num/1' ,'num/2','only']);
        else if (courseName == "PERSONAL FINANCE")speak(['subj/' + courseName,"can_register",'num/1' ,'num/2','only']);
        else if (courseName == "FOOD SCI ART")speak(['subj/' + courseName,"can_register",'num/1','only']);
        else if (courseName == "WEIGHT CONTROL")speak(['subj/' + courseName,"can_register",'num/1' ,'num/2','only']);
        else if (courseName == "AUTO SPEECH RECOG")speak(['subj/' + courseName,"can_register",'num/1' ,'only']);
        console.log(jqXHR)
        console.log(textStatus)
        console.log(errorThrown)
        //alert('registration error: ' + errorThrown)
    })
}

function withdraw(params, shouldSpeak = true) {
    console.log('withdraw')
    console.log(params)
    $.ajax({
        url: 'http://localhost:8000/api/courses',
        method: 'delete',
        data: params
    }).done(function(data) {
        console.log(data)
        showEnrolledCourses(params, false);
        if (shouldSpeak) {
            if (data.success)
                speak(['withdraw', 'subj/' + data.course.name, 'success']);
            else {
                if(data.message == "Course not registered.") speak(['not_register' , 'subj/' +courseName ]);
                else speak(['say_again']);
            }
         }

    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR)
        console.log(textStatus)
        console.log(errorThrown)
        alert('withdrawal error: ' + errorThrown)
    })
}



// misc

function flipCourseSection(data) {
    // flip course-section inside-out to make section-course structure
    var dataFormatted = [];
    for (var i = 0; i < data.length; i++) {
        var courseInfo = data[i];
        for (var j = 0; j < data[i].sections.length; j++) {
            var section = data[i].sections[j];
            section.course = courseInfo;
            dataFormatted.push(section);
        }
    }
    return dataFormatted;
}

var audioQueue = [];
function speak(words, rate = 1.6) {
    audios = [];
    loadedCount = 0;
    for (var i = 0; i < words.length; i++) {
        var audio = new Audio('/tts/' + words[i] + '.wav');
        audioQueue.push(audio);
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
    setTimeout(function() {
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
