
{{--@if ($message = Session::get('success'))--}}
{{--    <script>--}}
{{--        Swal.fire({--}}
{{--            position: "center",--}}
{{--            icon: "success",--}}
{{--            title: "{{$message}}",--}}
{{--            showConfirmButton: false,--}}
{{--            timer: 1500--}}
{{--        });--}}
{{--    </script>--}}
{{--@endif--}}
{{--@if ($message = Session::get('error'))--}}
{{--    <script>--}}
{{--        Swal.fire({--}}
{{--            position: "center",--}}
{{--            icon: "success",--}}
{{--            title: "{{$message}}",--}}
{{--            showConfirmButton: false,--}}
{{--            timer: 1500--}}
{{--        });--}}
{{--    </script>--}}
{{--@endif--}}

{{--@if ($message = Session::get('warning'))--}}
{{--    <script>--}}
{{--        Swal.fire({--}}
{{--            position: "center",--}}
{{--            icon: "success",--}}
{{--            title: "{{$message}}",--}}
{{--            showConfirmButton: false,--}}
{{--            timer: 1500--}}
{{--        });--}}
{{--    </script>--}}
{{--@endif--}}

{{--@if ($message = Session::get('info'))--}}
{{--    <script>--}}
{{--        Swal.fire({--}}
{{--            position: "center",--}}
{{--            icon: "success",--}}
{{--            title: "{{$message}}",--}}
{{--            showConfirmButton: false,--}}
{{--            timer: 1500--}}
{{--        });--}}
{{--    </script>--}}
{{--@endif--}}

@if ($errors->any())
    <div class="alert alert-custom alert-notice alert-light-info fade show" role="alert">
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text">{!! implode('', $errors->all('<p>:message</p>')) !!}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
@endif


@if ($message = Session::get('success'))
    <div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
        <div class="alert-icon"><i class="flaticon2-check-mark"></i></div>
        <div class="alert-text">{{$message}}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
        <div class="alert-icon"><i class="flaticon2-trash"></i></div>
        <div class="alert-text">{{$message}}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-custom alert-notice alert-light-warning fade show" role="alert">
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text">{{$message}}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-custom alert-notice alert-light-info fade show" role="alert">
        <div class="alert-icon"><i class="flaticon-bell-1"></i></div>
        <div class="alert-text">{{$message}}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
@endif

{{--@if ($errors->any())--}}
{{--    <div class="alert alert-custom alert-notice alert-light-info fade show" role="alert">--}}
{{--        <div class="alert-icon"><i class="flaticon-warning"></i></div>--}}
{{--        <div class="alert-text">{!! implode('', $errors->all('<p>:message</p>')) !!}</div>--}}
{{--        <div class="alert-close">--}}
{{--            <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
{{--                <span aria-hidden="true"><i class="ki ki-close"></i></span>--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}

