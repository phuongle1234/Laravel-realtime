@if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-dark"><span>{{ $error }}</span>
                <button class="btn-close" type="button" onclick="$(this).parent().remove()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                        <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                        <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                        <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                        <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
                    </svg>
                </button>
            </div>
        @endforeach
@endif

@if (session('message'))
    <div class="alert alert-dark"><span>{{ session('message') }}</span>
        <button class="btn-close" type="button" onclick="$(this).parent().remove()">
            <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
            </svg>
        </button>
    </div>
@endif
