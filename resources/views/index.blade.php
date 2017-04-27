@extends('layouts.app')

@section('content')
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

<?php include(base_path().'/resources/views/templates/tableTemplate.handlebars'); ?>
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
@endsection
