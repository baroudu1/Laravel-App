@if ($message = Session::get('success'))
    <div class="text-success ">

        <li>{{ $message }}</li>
    </div>
@endif


@if ($message = Session::get('error'))
    <div class="text-danger ">

        <li>{{ $message }}</li>
    </div>
@endif


@if ($message = Session::get('warning'))
    <div class="text-warning ">

        <li>{{ $message }}</li>
    </div>
@endif


@if ($message = Session::get('info'))
    <div class="text-info ">

        <li>{{ $message }}</li>
    </div>
@endif


@if ($errors->any())
    <div class="text-danger">

        Please check the form below for errors
    </div>
@endif
