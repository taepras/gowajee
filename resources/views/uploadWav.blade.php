@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>test function choose</h3>
            <form action="/api/recognize/function" method="post" enctype="multipart/form-data">
                <label class="control-label">Select File</label>
                <input id="input-1" type="file" name="wavfile">
                <button type="submit">upload</button>
            </form>  
            <br>
            <br>     
            <h3>test confirm</h3>     
            <form action="/api/recognize/confirm" method="post" enctype="multipart/form-data">
                <label class="control-label">Select File</label>
                <input id="input-1" type="file" name="wavfile">
                <button type="submit">upload</button>
            </form> 
        </div>
    </div>
</div>

<script type="text/javascript">
</script>
@endsection
