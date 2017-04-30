@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                    <h1>input</h1>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <a id="get-all" class="api btn btn-primary btn-block">get all courses</a>
                    <hr>

                    <a id="get-enrolled" href="http://localhost:8000/api/courses" data-method="get" data-use-data="false" class="api btn btn-primary btn-block">get enrolled courses</a>
                    <hr>

                    <select class="form-control" id="get-day">
                        <option>mon</option>
                        <option>tue</option>
                        <option>wed</option>
                        <option>thu</option>
                        <option>fri</option>
                        <option>sat</option>
                        <option>sun</option>
                    </select>
                    <select class="form-control" id="get-time">
                        <option>morning</option>
                        <option>afternoon</option>
                    </select>
                    <button id="get-by-time" class="api btn btn-primary btn-block">get by time</button>
                    <hr>

                    <select class="form-control" id="get-course-id">
                        <option>0123101</option>
                        <option>2110432</option>
                        <option>2313213</option>
                        <option>2502390</option>
                        <option>2604362</option>
                        <option>3700105</option>
                        <option>3743422</option>
                    </select>
                    {{-- <input class="form-control" type="text" id="get-course-id" placeholder="course-id"> --}}
                    <button id="get-id" class="api btn btn-primary btn-block">get course</button>
                    <hr>

                    <input class="form-control" type="text" id="reg-course-id" placeholder="course-id">
                    <input class="form-control" type="text" id="reg-section-id" placeholder="section-id">
                    <button id="register" class="api btn btn-primary btn-block">register</button>

                    <hr>
                    <input class="form-control" type="text" id="wit-course-id" placeholder="course-id">
                    <button id="withdraw" class="api btn btn-primary btn-block">withdraw</button>
                    <hr>
                </div>
                <div class="col-sm-6">
                    <h1>output</h1>
                    <pre id="output"></pre>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
$(function () {
    $('#get-all').click(function(e) {
        e.preventDefault();
        fireAjax('http://localhost:8000/api/courses/all', 'get')
    })
    $('#get-enrolled').click(function(e) {
        e.preventDefault();
        fireAjax('http://localhost:8000/api/courses/', 'get')
    })
    $('#get-by-time').click(function(e) {
        e.preventDefault();
        var day = $('#get-day').val()
        var time = $('#get-time').val()
        fireAjax('http://localhost:8000/api/courses/' + day + '/' + time, 'get')
    })
    $('#get-id').click(function(e) {
        e.preventDefault();
        var id = $('#get-course-id').val()
        fireAjax('http://localhost:8000/api/courses/' + id, 'get')
    })
    $('#register').click(function(e) {
        e.preventDefault();
        var course = $('#reg-course-id').val()
        var section = $('#reg-section-id').val()
        fireAjax('http://localhost:8000/api/courses/', 'post', {
            course: course,
            section: section
        })
    })
    $('#withdraw').click(function(e) {
        e.preventDefault();
        var course = $('#wit-course-id').val()
        fireAjax('http://localhost:8000/api/courses/', 'delete', {
            course: course
        })
    })
})

function fireAjax(url, method, data = null) {
    console.log(url)
    console.log(method)
    // if (data)
    //     data._token = $('input[name=_token]').val();
    console.log(data)
    $.ajax({
        url: url,
        method: method,
        data: data
    }).done(function (data) {
        console.log(data)
        $('#output').html(JSON.stringify(data, null, 4))
        window.scrollTo(0, 0)
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        console.log(jqXHR)
        console.log(textStatus)
        console.log(errorThrown)
        $('#output').html(errorThrown)
        window.scrollTo(0, 0)
    })
}
</script>
@endsection
