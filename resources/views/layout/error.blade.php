@if(count($errors))
    <div class=" xuan" role="alert">
        @foreach($errors->all() as $error)
            <li class="alert alert-danger">{{$error}}</li>
        @endforeach
    </div>
@endif