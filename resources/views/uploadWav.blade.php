@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form action="/api/recognize/function" method="post" enctype="multipart/form-data">
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
