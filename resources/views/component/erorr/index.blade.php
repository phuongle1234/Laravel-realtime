@if ($errors->any())
    <div class="alert-wrapper" style="position: absolute!important">
        @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
        @endforeach
    </div>
@endif

@if (session('message'))
        <div class="alert-wrapper" style="position: absolute!important">
            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
        </div>
@endif