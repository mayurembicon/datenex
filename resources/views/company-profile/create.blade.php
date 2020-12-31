<DOCTYPE html>

    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--begin::Head-->
    <head>
        <base href="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8"/>
        <title>Embicon | Profile</title>
        <meta name="description" content="Singin page example"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
        <!--end::Fonts-->


        <!--begin::Page Custom Styles(used by this page)-->
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/pages/login/login-4.css'}}"
              rel="stylesheet" type="text/css"/>
        <!--end::Page Custom Styles-->

        <!--begin::Global Theme Styles(used by all pages)-->
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/global/plugins.bundle.css'}}"
              rel="stylesheet" type="text/css"/>
        <link
            href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/prismjs/prismjs.bundle.css'}}"
            rel="stylesheet" type="text/css"/>
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/style.bundle.css'}}"
              rel="stylesheet"
              type="text/css"/>
        <!--end::Global Theme Styles-->

        <!--begin::Layout Themes(used by all pages)-->

        <link
            href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/themes/layout/header/base/light.css'}}"
            rel="stylesheet" type="text/css"/>
        <link
            href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/themes/layout/header/menu/light.css'}}"
            rel="stylesheet" type="text/css"/>
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/themes/layout/brand/dark.css'}}"
              rel="stylesheet" type="text/css"/>
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/themes/layout/aside/dark.css'}}"
              rel="stylesheet" type="text/css"/>        <!--end::Layout Themes-->

        <link rel="shortcut icon"
              href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/favicon.ico'}}"/>

    </head>
    <!--end::Head-->

    <!--begin::Body-->
    <body id="kt_body"
          class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed subheader-mobile-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid wizard" id="kt_login">
            <!--begin::Content-->
            <div
                class="login-container d-flex flex-center flex-row flex-row-fluid order-2 order-lg-1  bg-white py-lg-15 pb-lg-15 pt-15 pb-15">
                <!--begin::Container-->
                <div class="login-content login-content-signup d-flex flex-column">
                    <!--begin::Aside Top-->
                    <div class="d-flex flex-column-auto flex-column px-10">
                        <!--begin::Aside header-->
                        <a href="#" class="login-logo pb-lg-4 pb-10">
                            <img
                                src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/logo-4.png'}}"
                                class="max-h-70px" alt=""/>
                        </a>
                        <!--end::Aside header-->

                        <!--begin: Wizard Nav-->
                        <div class="wizard-nav pt-5 pt-lg-15 pb-10">
                            <!--begin::Wizard Steps-->
                            <div class="wizard-steps d-flex flex-column flex-sm-row">
                                <!--begin::Wizard Step 1 Nav-->
                                <div class="wizard-step flex-grow-1 flex-basis-0" data-wizard-type="step"
                                     data-wizard-state="current">
                                    <div class="wizard-wrapper pr-7">
                                        <div class="wizard-icon">
                                            <i class="wizard-check ki ki-check"></i>
                                            <span class="wizard-number">1</span>
                                        </div>
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">
                                                Company
                                            </h3>
                                            <div class="wizard-desc">
                                                Create Company
                                            </div>
                                        </div>
                                        <span class="svg-icon pl-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <rect fill="#000000" opacity="0.3"
                                                  transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) "
                                                  x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                            <path
                                                d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
                                                fill="#000000" fill-rule="nonzero"
                                                transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                        </g>
                                    </svg>
                                </span>
                                    </div>
                                </div>
                                <!--end::Wizard Step 1 Nav-->

                                <!--begin::Wizard Step 2 Nav-->
                                <div class="wizard-step flex-grow-1 flex-basis-0" data-wizard-type="step">
                                    <div class="wizard-wrapper pr-7">
                                        <div class="wizard-icon">
                                            <i class="wizard-check ki ki-check"></i>
                                            <span class="wizard-number">2</span>
                                        </div>
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">
                                                Address
                                            </h3>
                                            <div class="wizard-desc">
                                                Residential address
                                            </div>
                                        </div>
                                        <span class="svg-icon pl-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <rect fill="#000000" opacity="0.3"
                                                  transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) "
                                                  x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                            <path
                                                d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
                                                fill="#000000" fill-rule="nonzero"
                                                transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                        </g>
                                    </svg>
                                </span>
                                    </div>
                                </div>
                                <div class="wizard-step flex-grow-1 flex-basis-0" data-wizard-type="step">
                                    <div class="wizard-wrapper pr-7">
                                        <div class="wizard-icon">
                                            <i class="wizard-check ki ki-check"></i>
                                            <span class="wizard-number">3</span>
                                        </div>
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">
                                                User
                                            </h3>
                                            <div class="wizard-desc">
                                                Create User
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Wizard Step 2 Nav-->

                                <!--begin::Wizard Step 3 Nav-->
                                <!--end::Wizard Step 3 Nav-->
                            </div>
                            <!--end::Wizard Steps-->
                        </div>
                        <!--end: Wizard Nav-->
                    </div>
                    <!--end::Aside Top-->

                    <!--begin::Signin-->
                    <div class="login-form">
                        <!--begin::Form-->
                        <form action="{{route('profiles.store')}}" method="post" class="form px-10"
                              novalidate="novalidate"
                              id="kt_login_signup_form" enctype="multipart/form-data">
                        @csrf
                        <!--begin: Wizard Step 1-->


                            <div class=" " data-wizard-type="step-content" data-wizard-state="current">
                                <!--begin::Title-->
                                <!--begin::Title-->
                                <div class="form-group ">
                                    <label class="font-size-h6 font-weight-bolder text-dark" for="image">Company
                                        Logo</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline " id="kt_image_3">
                                            <div class="image-input-wrapper"></div>
                                            <label
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title=""
                                                data-original-title="Change avatar">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="c_logo" value="" accept=".png, .jpg, .jpeg"/>
                                                <input type="hidden" name="profile_avatar_remove"/>
                                            </label>

                                            <span
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                                        </div>
                                        <span class="form-text text-muted">Allowed file types:  png, jpg, jpeg.</span>
                                    </div>
                                </div>

                                <!--begin::Form Group-->
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Company Name</label>
                                    <input type="text"
                                           class="form-control "
                                           name="company_name" placeholder="Company Name" value=""/>
                                </div>
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Phone No</label>
                                    <input type="text"
                                           class="form-control"
                                           name="phone_no" placeholder="+91 00000 00000" value=""/>
                                </div>
                                <!--end::Form Group-->

                                <!--begin::Form Group-->
                            {{--                            <div class="form-group">--}}
                            {{--                                <label class="font-size-h6 font-weight-bolder text-dark" for="image">Company Logo</label>--}}
                            {{--                                <input--}}
                            {{--                                       class="form-control form-control-solid h-auto py-7 px-6 border-0 rounded-lg font-size-h6"--}}
                            {{--                                       name="c_logo" placeholder="Company Logo" value="" id="image" type="file"/>--}}
                            {{--                            </div>--}}
                            <!--end::Form Group-->

                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">GST IN</label>
                                    <input type="text"
                                           class="form-control "
                                           name="gst_in" placeholder="Gst In" value=""/>
                                </div>

                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Invoice Prefix</label>
                                    <input type="text"
                                           class="form-control "
                                           name="invoice_prefix" placeholder="Invoice Prefix" value=""/>
                                </div>

                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Quotation Prefix</label>
                                    <input type="text"
                                           class="form-control "
                                           name="quotation_prefix" placeholder="Invoice Prefix" value=""/>
                                </div>
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Po Prefix</label>
                                    <input type="text"
                                           class="form-control"
                                           name="po_prefix" placeholder="PO Prefix" value=""/>
                                </div>
                            </div>
                            <div class="" data-wizard-type="step-content">
                                <!--begin::Title-->
                                <div class="pt-lg-0 pt-5 pb-15">
                                    <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Address
                                        Details</h3>
                                    {{--                                <div class="text-muted font-weight-bold font-size-h4">--}}
                                    {{--                                    Have a Different Address ?--}}
                                    {{--                                    <a href="#" class="text-primary font-weight-bolder">Add Address</a>--}}
                                    {{--                                </div>--}}
                                </div>
                                <!--begin::Title-->

                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label class="font-size-h6 font-weight-bolder text-dark">Address</label>
                                            <input type="text"
                                                   class="form-control "
                                                   name="address1" placeholder="Address " value=""/>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label class="font-size-h6 font-weight-bolder text-dark">Address 2</label>
                                            <input type="text"
                                                   class="form-control "
                                                   name="address2" placeholder="Address 2" value=""/>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label class="font-size-h6 font-weight-bolder text-dark">Address 3</label>
                                            <input type="text"
                                                   class="form-control "
                                                   name="address3" placeholder="Address 2 " value=""/>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <!--begin::Select-->
                                        <div class="form-group">
                                            <label class="font-size-h6 font-weight-bolder text-dark">Country</label>
                                            <br>

                                            <select
                                                class="form-control  country-select2"
                                                name="country">

                                            </select>

                                        </div>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label class="font-size-h6 font-weight-bolder text-dark">State</label>
                                            <select
                                                class="form-control  state-select2"
                                                name="state">

                                            </select>
                                        </div>
                                        <!--end::Input-->
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label class="font-size-h6 font-weight-bolder text-dark">City</label>
                                            <input type="text"
                                                   class="form-control "
                                                   name="city" placeholder="City" value=""/>

                                        </div>
                                        <!--end::Input-->
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label class="font-size-h6 font-weight-bolder text-dark">Pin Code</label>
                                            <input type="text"
                                                   class="form-control  font-size-h6"
                                                   name="pincode" placeholder="Pin Code " value=""/>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                </div>

                                <!--end::Row-->
                            </div>

                            <div class="" data-wizard-type="step-content">
                                <!--begin::Title-->
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">User Name</label>
                                    <input type="email"
                                           class="form-control   @error('email') is-invalid @enderror"
                                           name="email" placeholder="Enter Your Email" value=""/>
                                    @error('email')
                                    <div class="invalid-feedback"
                                         role="alert">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Password</label>
                                    <input type="text"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="password" placeholder=" Enter Your Password" value=""/>
                                    @error('password')
                                    <div class="invalid-feedback"
                                         role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="d-flex justify-content-between pt-7">
                                <div class="mr-2">
                                    <button type="button"
                                            class="btn btn-light-primary font-weight-bolder font-size-h6 pr-8 pl-6 py-4 my-3 mr-3"
                                            data-wizard-type="action-prev">
                                <span class="svg-icon svg-icon-md mr-2"><!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Left-2.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <rect fill="#000000" opacity="0.3"
              transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) "
              x="14" y="7" width="2" height="10" rx="1"/>
        <path
            d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
            fill="#000000" fill-rule="nonzero"
            transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
    </g>
</svg><!--end::Svg Icon--></span> Previous
                                    </button>
                                </div>
                                <div>

                                    <button type="submit"
                                            class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3"
                                            data-wizard-type="action-submit" type="submit"
                                            id="kt_login_signup_form_submit_button">
                                        Submit
                                        <span class="svg-icon svg-icon-md ml-2"><!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg--><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <rect fill="#000000" opacity="0.3"
              transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5"
              y="7.5" width="2" height="9" rx="1"/>
        <path
            d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
            fill="#000000" fill-rule="nonzero"
            transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
    </g>
</svg><!--end::Svg Icon--></span></button>

                                    <button type="button"
                                            class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3"
                                            data-wizard-type="action-next">
                                        Next
                                        <span class="svg-icon svg-icon-md ml-2"><!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg--><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <rect fill="#000000" opacity="0.3"
              transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5"
              y="7.5" width="2" height="9" rx="1"/>
        <path
            d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z"
            fill="#000000" fill-rule="nonzero"
            transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
    </g>
</svg><!--end::Svg Icon--></span></button>
                                </div>
                            </div>

                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Signin-->
                </div>
                <!--end::Container-->
            </div>
            <!--begin::Content-->

            <!--begin::Aside-->
            <div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
                <div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom"
                     style="    background-image: url({{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/svg/illustrations/login-visual-4.svg'}});">
                    <!--begin::Aside title-->
                    <h3 class="pt-lg-40 pl-lg-20 pb-lg-0 pl-10 py-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">
                        We Got<br/>
                        A Surprise<br/>
                        For You
                    </h3>
                    <!--end::Aside title-->
                </div>
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Login-->
    </div>
    <!--end::Main-->

    <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <!--end::Global Config-->

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/global/plugins.bundle.js'}}"></script>
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/prismjs/prismjs.bundle.js'}}"></script>
    <script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/scripts.bundle.js'}}"></script>
    <!--end::Global Theme Bundle-->


    <!--begin::Page Scripts(used by this page)-->
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/custom/login/login-4.js'}}"></script>
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/file-upload/image-input.js'}}"></script>
    <script>
        $(document).ready(function () {
            $('.country-select2').select2({
                placeholder: "Select ...",
                allowClear: true
            });
            $(".country-select2").change(function () {
                let countryID = $(this).val();
                $(".state-select2").select2({
                    placeholder: "Select..", ajax: {
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ url('state-list')}}",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: params.term,
                                country_id: countryID,
                            };
                        }, processResults: function (response) {
                            return {results: response};
                        }, cache: true
                    }
                });
            });
            $(".country-select2").select2({
                placeholder: "Select..", ajax: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('country-list')}}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term
                        };
                    }, processResults: function (response) {
                        return {results: response};
                    }, cache: true
                }
            });
        });


    </script>

    <!--end::Page Scripts-->
    </body>
    <!--end::Body-->
    </html>
</DOCTYPE>

