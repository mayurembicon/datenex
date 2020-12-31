
<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->
<head><base href="../../../../">
    <meta charset="utf-8" />
    <title>Embicon  | Forgot Password</title>
    <meta name="description" content="Forgot password page example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/pages/login/login-2.css'}}" rel="stylesheet" type="text/css"/>
    <!--end::Page Custom Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/global/plugins.bundle.css'}}" rel="stylesheet" type="text/css"/>
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/prismjs/prismjs.bundle.css'}}" rel="stylesheet" type="text/css"/>
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/style.bundle.css'}}" rel="stylesheet" type="text/css"/>
    <!--end::Layout Themes-->
{{--    <link rel="shortcut icon" href="{{asset('assets/media/logos/embicon.png')}}" />--}}
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-2 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        <div class="login-aside order-2 order-lg-1 d-flex flex-row-auto position-relative overflow-hidden">
            <!--begin: Aside Container-->
            <div class="d-flex flex-column-fluid flex-column justify-content-between py-9 px-7 py-lg-13 px-lg-35">
                <!--begin::Logo-->
                <a href="{{url('/')}}" class="text-center pt-2">
                    <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/logo.png'}}" class="max-h-75px" alt=""/>
                </a>
                <!--end::Logo-->

                <!--begin::Aside body-->
                <div class="d-flex flex-column-fluid flex-column flex-center">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                @endif
                        <!--begin::Form-->
                        <form class="form" id="kt_login_forgot_form" method="POST" action="{{ route('password.email') }}">
                        @csrf
                            <!--begin::Title-->
                            <div class="text-center pb-8">

                                <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Forgotten Password ?</h2>
                                <p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
                            </div>
                            <!--end::Title-->

                            <!--begin::Form group-->
                            <div class="form-group">
                                <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @error('email') is-invalid @enderror" type="email" placeholder="Email" name="email" autocomplete="off"/>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!--end::Form group-->

                            <!--begin::Form group-->
                            <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
                                <button type="submit"  class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Submit</button>
                                <a  href="{{url('login')}}" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancel</a>
                            </div>
                            <!--end::Form group-->
                        </form>
                        <!--end::Form-->

                </div>
                <!--end::Aside body-->

                <!--begin: Aside footer for desktop-->

                <!--end: Aside footer for desktop-->
            </div>
            <!--end: Aside Container-->
        </div>
        <!--begin::Aside-->

        <!--begin::Content-->
        <div class="content order-1 order-lg-2 d-flex flex-column w-100 pb-0" style="background-color: #B1DCED;">
            <!--begin::Title-->
            <div class="d-flex flex-column justify-content-center text-center pt-lg-40 pt-md-5 pt-sm-5 px-lg-0 pt-5 px-7">
                <h3 class="display4 font-weight-bolder my-7 text-dark" style="color: #986923;">Amazing Wireframes</h3>
                <p class="font-weight-bolder font-size-h2-md font-size-lg text-dark opacity-70">
                    User Experience & Interface Design, Product Strategy<br/>
                    Web Application SaaS Solutions
                </p>
            </div>
            <!--end::Title-->

            <!--begin::Image-->
            <div class="content-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url({{asset('assets/media/svg/illustrations/login-visual-2.svg)')}}"></div>
            <!--end::Image-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
<script>var HOST_URL = "{{url('/')}}"</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/global/plugins.bundle.js?v=7.0.5'}}"></script>
<script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.5'}}"></script>
<script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/scripts.bundle.js?v=7.0.5'}}"></script>
<!--end::Global Theme Bundle-->
<!--begin::Page Scripts(used by this page)-->


{{--<script src="assets/js/pages/custom/login/login-3.js?v=7.0.5"></script>--}}
<!--end::Page Scripts-->
</body>
<!--end::Body-->
</html>
