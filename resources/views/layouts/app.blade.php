<DOCTYPE html>

    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--begin::Head-->
    <head>
        <base href="">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8"/>
        <title>Embicon </title>
        <meta name="description" content="Updates and statistics"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
        <!--end::Fonts-->
    @stack('styles')
    <!--begin::Page Vendors Styles(used by this page)-->
        <link
            href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/fullcalendar/fullcalendar.bundle.css'}}"
            rel="stylesheet"
            type="text/css"/>
        <!--end::Page Vendors Styles-->


        <!--begin::Global Theme Styles(used by all pages)-->
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/global/plugins.bundle.css'}}"
              rel="stylesheet" type="text/css"/>
        <link
            href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/prismjs/prismjs.bundle.css'}}"
            rel="stylesheet" type="text/css"/>
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/style.bundle.css'}}"
              rel="stylesheet" type="text/css"/>
        <!--end::Global Theme Styles-->

        <!--begin::Layout Themes(used` by all pages)-->

        <link
            href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/themes/layout/header/base/light.css'}}"
            rel="stylesheet" type="text/css"/>
        <link
            href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/themes/layout/header/menu/light.css'}}"
            rel="stylesheet" type="text/css"/>
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/themes/layout/brand/dark.css'}}"
              rel="stylesheet" type="text/css"/>
        <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/themes/layout/aside/dark.css'}}"
              rel="stylesheet" type="text/css"/>
        <!--end::Layout Themes-->

        <link rel="shortcut icon"
              href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/favicon.ico'}}"/>

    </head>
    <!--end::Head-->

    <!--begin::Body-->
    <body id="kt_body"
          class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed subheader-mobile-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading page-loading-enabled page-loading header-fixed header-mobile-fixed page-loading">
    <div class="page-loader page-loader-logo">
        <img alt="Logo" class="max-h-150px"
             src="{{ 'http://datenex.com/laravel/public/profile/'.$companyInfo->c_logo}}"/>
        <div class="spinner spinner-primary"></div>
    </div>
    <!--begin::Main-->
    <!--begin::Header Mobile-->
    <div id="kt_header_mobile" class="header-mobile align-items-center  header-mobile-fixed ">
        <!--begin::Logo-->
        <a href="{{url('/')}}">
{{--            <img alt="Logo"--}}
{{--                 src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/logo-letter-1.png'}}"--}}
{{--                 class="logo-default max-h-30px"/>--}}
{{--            <img alt="Logo"--}}
{{--                 src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/logo-letter-1.png'}}"--}}
{{--                 class="logo-default max-h-30px"/>--}}
        </a>
        <!--end::Logo-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Aside Mobile Toggle-->
            <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                <span></span>
            </button>
            <!--end::Aside Mobile Toggle-->

            <!--begin::Header Menu Mobile Toggle-->
{{--            <button class="btn p-0 burger-icon ml-4" id="kt_header_mobile_toggle">--}}
{{--                <span></span>--}}
{{--            </button>--}}
            <!--end::Header Menu Mobile Toggle-->

            <!--begin::Topbar Mobile Toggle-->
            <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
			<span class="svg-icon svg-icon-xl"><!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg--><svg
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path
            d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
            fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path
            d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
            fill="#000000" fill-rule="nonzero"/>
    </g>
</svg><!--end::Svg Icon--></span></button>
            <!--end::Topbar Mobile Toggle-->
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">

            <!--begin::Aside-->
            <div class="aside aside-left  aside-fixed  d-flex flex-column flex-row-auto" id="kt_aside">
                <!--begin::Brand-->
                <div class="brand flex-column-auto " id="kt_brand">
                    <!--begin::Logo-->

                {{--                <img alt="Logo" src="{{asset('assets/media/svg/avatars/001-boy.svg')}}"--}}
                {{--                     class="h-75 align-self-end"/>--}}

                <!--end::Logo-->

                    <!--begin::Toggle-->
                    <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
				<span class="svg-icon svg-icon svg-icon-xl"><!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg--><svg
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path
            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
            fill="#000000" fill-rule="nonzero"
            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "/>
        <path
            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
            fill="#000000" fill-rule="nonzero" opacity="0.3"
            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "/>
    </g>
</svg><!--end::Svg Icon--></span></button>
                    <!--end::Toolbar-->
                </div>
                <!--end::Brand-->

                <!--begin::Aside Menu-->
                <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

                    <!--begin::Menu Container-->
                    <div
                        id="kt_aside_menu"
                        class="aside-menu my-4 "
                        data-menu-vertical="1"
                        data-menu-scroll="1" data-menu-dropdown-timeout="500">
                        <!--begin::Menu Nav-->
                        <ul class="menu-nav ">
                            <li class="menu-item  {{ request()->is('/') ? 'menu-item-active' : '' }} menu-item-active"
                                aria-haspopup="true">
                                <a href="{{url('/')}}" class="menu-link "><span
                                        class="svg-icon menu-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path
            d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z"
            fill="#000000" fill-rule="nonzero"/>
        <path
            d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z"
            fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span><span class="menu-text">Dashboard</span></a></li>

                            <li class="menu-item  menu-item-submenu {{ (request()->is('user*')||request()->is('financial-year*')||request()->is('customer*') ||request()->is('item-master*')   )  ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a
                                    href="javascript:;" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z"
            fill="#000000"/>
        <path
            d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z"
            fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span><span class="menu-text">Master</span><i class="menu-arrow"></i></a>
                                <div class="menu-submenu "><i class="menu-arrow"></i>
                                    <ul class="menu-subnav">

                                        <li class="menu-item menu-item-submenu {{ request()->is('user*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('user.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Users</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('financial-year*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('financial-year.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Financial Year</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('customer*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('customer.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Customer</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('item-master*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('item-master.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Item</span>
                                            </a>

                                        </li>


                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item  menu-item-submenu
{{ (request()->is('inquiry*')
||request()->is('online-inquiry*')
||request()->is('quotation*')
||request()->is('pi*')
||request()->is('sales*')
||request()->is('po*')
||request()->is('purchase*')
||request()->is('sales-return*')
||request()->is('outstanding*')
||request()->is('payment*')
||request()->is('journal*')
||request()->is('customer-inquiry*')||request()->is('receipt*')  ) ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a
                                    href="javascript:;" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <rect fill="#000000" opacity="0.3" x="11.5" y="2" width="2" height="4" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="11.5" y="16" width="2" height="5" rx="1"/>
        <path
            d="M15.493,8.044 C15.2143319,7.68933156 14.8501689,7.40750104 14.4005,7.1985 C13.9508311,6.98949895 13.5170021,6.885 13.099,6.885 C12.8836656,6.885 12.6651678,6.90399981 12.4435,6.942 C12.2218322,6.98000019 12.0223342,7.05283279 11.845,7.1605 C11.6676658,7.2681672 11.5188339,7.40749914 11.3985,7.5785 C11.2781661,7.74950085 11.218,7.96799867 11.218,8.234 C11.218,8.46200114 11.2654995,8.65199924 11.3605,8.804 C11.4555005,8.95600076 11.5948324,9.08899943 11.7785,9.203 C11.9621676,9.31700057 12.1806654,9.42149952 12.434,9.5165 C12.6873346,9.61150047 12.9723317,9.70966616 13.289,9.811 C13.7450023,9.96300076 14.2199975,10.1308324 14.714,10.3145 C15.2080025,10.4981676 15.6576646,10.7419985 16.063,11.046 C16.4683354,11.3500015 16.8039987,11.7268311 17.07,12.1765 C17.3360013,12.6261689 17.469,13.1866633 17.469,13.858 C17.469,14.6306705 17.3265014,15.2988305 17.0415,15.8625 C16.7564986,16.4261695 16.3733357,16.8916648 15.892,17.259 C15.4106643,17.6263352 14.8596698,17.8986658 14.239,18.076 C13.6183302,18.2533342 12.97867,18.342 12.32,18.342 C11.3573285,18.342 10.4263378,18.1741683 9.527,17.8385 C8.62766217,17.5028317 7.88033631,17.0246698 7.285,16.404 L9.413,14.238 C9.74233498,14.6433354 10.176164,14.9821653 10.7145,15.2545 C11.252836,15.5268347 11.7879973,15.663 12.32,15.663 C12.5606679,15.663 12.7949989,15.6376669 13.023,15.587 C13.2510011,15.5363331 13.4504991,15.4540006 13.6215,15.34 C13.7925009,15.2259994 13.9286662,15.0740009 14.03,14.884 C14.1313338,14.693999 14.182,14.4660013 14.182,14.2 C14.182,13.9466654 14.1186673,13.7313342 13.992,13.554 C13.8653327,13.3766658 13.6848345,13.2151674 13.4505,13.0695 C13.2161655,12.9238326 12.9248351,12.7908339 12.5765,12.6705 C12.2281649,12.5501661 11.8323355,12.420334 11.389,12.281 C10.9583312,12.141666 10.5371687,11.9770009 10.1255,11.787 C9.71383127,11.596999 9.34650161,11.3531682 9.0235,11.0555 C8.70049838,10.7578318 8.44083431,10.3968355 8.2445,9.9725 C8.04816568,9.54816454 7.95,9.03200304 7.95,8.424 C7.95,7.67666293 8.10199848,7.03700266 8.406,6.505 C8.71000152,5.97299734 9.10899753,5.53600171 9.603,5.194 C10.0970025,4.85199829 10.6543302,4.60183412 11.275,4.4435 C11.8956698,4.28516587 12.5226635,4.206 13.156,4.206 C13.9160038,4.206 14.6918294,4.34533194 15.4835,4.624 C16.2751706,4.90266806 16.9686637,5.31433061 17.564,5.859 L15.493,8.044 Z"
            fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span><span class="menu-text">Transaction</span><i class="menu-arrow"></i></a>
                                <div class="menu-submenu "><i class="menu-arrow"></i>
                                    <ul class="menu-subnav">

                                        <li class="menu-item menu-item-submenu {{ request()->is('online-inquiry*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('online-inquiry.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Online Inquiry</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('customer-inquiry*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('customer-inquiry.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Customer Inquiry</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('inquiry*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('inquiry.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Inquiry</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('quotation*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('quotation.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Quotation</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('pi*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('pi.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Proforma Invoice</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('sales*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('sales.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Sales</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('po*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('po.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Purchase Order</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('purchase*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('purchase.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Purchase </span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('sales-return*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('sales-return.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Sales Return </span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('purchase-return*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('purchase-return.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Purchase Return </span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('payment*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('payment.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Payment </span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('receipt*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('receipt.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Receipt </span>
                                            </a>

                                        </li>


                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item  menu-item-submenu {{ (request()->is('report-inquiry*')||request()->is('itemwise-sp*')||request()->is('customerwise-sp*') ||request()->is('stock*') ||request()->is('c-timeline-index*')   )  ? 'menu-item-open' : '' }}"
                                aria-haspopup="true" data-menu-toggle="hover">
                                <a
                                    href="javascript:;" class="menu-link menu-toggle"><span class="svg-icon menu-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg--><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path
            d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z"
            fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
        <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span><span class="menu-text">Report</span><i class="menu-arrow"></i></a>
                                <div class="menu-submenu "><i class="menu-arrow"></i>
                                    <ul class="menu-subnav">

                                        <li class="menu-item  menu-item-submenu {{ (request()->is('report-inquiry*')  )  ? 'menu-item-open' : '' }}"
                                            aria-haspopup="true" data-menu-toggle="hover">
                                            <a
                                                href="javascript:;" class="menu-link menu-toggle">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Inquiry </span><i class="menu-arrow"></i></a>
                                            <div class="menu-submenu "><i class="menu-arrow"></i>
                                                <ul class="menu-subnav">

                                                    <li class="menu-item menu-item-submenu {{ request()->is('report-inquiry*') ? 'menu-item-active' : '' }}"
                                                        aria-haspopup="true"
                                                        data-menu-toggle="hover">
                                                        <a href="{{ route('report-inquiry.index') }}" class="menu-link">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Inquiry Report </span>
                                                        </a>

                                                    </li>


                                                </ul>
                                            </div>
                                        </li>

                                        <li class="menu-item  menu-item-submenu {{ (request()->is('report-quotation*')  )  ? 'menu-item-open' : '' }}"
                                            aria-haspopup="true" data-menu-toggle="hover">
                                            <a
                                                href="javascript:;" class="menu-link menu-toggle">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Quotation</span><i class="menu-arrow"></i></a>
                                            <div class="menu-submenu "><i class="menu-arrow"></i>
                                                <ul class="menu-subnav">

                                                    <li class="menu-item menu-item-submenu {{ request()->is('report-quotation*') ? 'menu-item-active' : '' }}"
                                                        aria-haspopup="true"
                                                        data-menu-toggle="hover">
                                                        <a href="{{ route('report-quotation.index') }}"
                                                           class="menu-link">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Quotation Report </span>
                                                        </a>

                                                    </li>


                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item  menu-item-submenu {{ (request()->is('report-pi*')  )  ? 'menu-item-open' : '' }}"
                                            aria-haspopup="true" data-menu-toggle="hover">
                                            <a
                                                href="javascript:;" class="menu-link menu-toggle">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Pi</span><i class="menu-arrow"></i></a>
                                            <div class="menu-submenu "><i class="menu-arrow"></i>
                                                <ul class="menu-subnav">

                                                    <li class="menu-item menu-item-submenu {{ request()->is('report-pi*') ? 'menu-item-active' : '' }}"
                                                        aria-haspopup="true"
                                                        data-menu-toggle="hover">
                                                        <a href="{{ route('report-pi.index') }}" class="menu-link">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Pi Report </span>
                                                        </a>

                                                    </li>


                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item  menu-item-submenu {{ (request()->is('report-invoice*')  )  ? 'menu-item-open' : '' }}"
                                            aria-haspopup="true" data-menu-toggle="hover">
                                            <a
                                                href="javascript:;" class="menu-link menu-toggle">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Invoice</span><i class="menu-arrow"></i></a>
                                            <div class="menu-submenu "><i class="menu-arrow"></i>
                                                <ul class="menu-subnav">

                                                    <li class="menu-item menu-item-submenu {{ request()->is('report-invoice*') ? 'menu-item-active' : '' }}"
                                                        aria-haspopup="true"
                                                        data-menu-toggle="hover">
                                                        <a href="{{ route('report-invoice.index') }}" class="menu-link">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Invoice Report </span>
                                                        </a>

                                                    </li>


                                                </ul>
                                            </div>
                                        </li>


                                        <li class="menu-item menu-item-submenu {{ request()->is('itemwise-sp*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('itemwise-sp.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Item S&P</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('customerwise-sp*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('customerwise-sp.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Company S&P</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('stock*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ route('stock.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Stock</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('c-timeline-index*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ url('c-timeline-index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Company Timeline</span>
                                            </a>

                                        </li>
                                        <li class="menu-item menu-item-submenu {{ request()->is('outstanding-balance*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true"
                                            data-menu-toggle="hover">
                                            <a href="{{ url('outstanding-balance') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">Outstanding Balance</span>
                                            </a>

                                        </li>


                                    </ul>
                                </div>
                            </li>


                            <li class="menu-item {{ (request()->is('logout*')) ? 'menu-item-active'  : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('logout') }}" class="menu-link"
                                   onclick="event.preventDefault();

                     document.getElementById('logout-form').submit();">

                                            <span class="menu-text">
                                                                         <span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
											<svg xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                 viewBox="0 0 24 24" version="1.1">
												 <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
      <path
          d="M14.0069431,7.00607258 C13.4546584,7.00607258 13.0069431,6.55855153 13.0069431,6.00650634 C13.0069431,5.45446114 13.4546584,5.00694009 14.0069431,5.00694009 L15.0069431,5.00694009 C17.2160821,5.00694009 19.0069431,6.7970243 19.0069431,9.00520507 L19.0069431,15.001735 C19.0069431,17.2099158 17.2160821,19 15.0069431,19 L3.00694311,19 C0.797804106,19 -0.993056895,17.2099158 -0.993056895,15.001735 L-0.993056895,8.99826498 C-0.993056895,6.7900842 0.797804106,5 3.00694311,5 L4.00694793,5 C4.55923268,5 5.00694793,5.44752105 5.00694793,5.99956624 C5.00694793,6.55161144 4.55923268,6.99913249 4.00694793,6.99913249 L3.00694311,6.99913249 C1.90237361,6.99913249 1.00694311,7.89417459 1.00694311,8.99826498 L1.00694311,15.001735 C1.00694311,16.1058254 1.90237361,17.0008675 3.00694311,17.0008675 L15.0069431,17.0008675 C16.1115126,17.0008675 17.0069431,16.1058254 17.0069431,15.001735 L17.0069431,9.00520507 C17.0069431,7.90111468 16.1115126,7.00607258 15.0069431,7.00607258 L14.0069431,7.00607258 Z"
          fill="#000000" fill-rule="nonzero" opacity="0.3"
          transform="translate(9.006943, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-9.006943, -12.000000) "/>
        <rect fill="#000000" opacity="0.3"
              transform="translate(14.000000, 12.000000) rotate(-270.000000) translate(-14.000000, -12.000000) " x="13"
              y="6" width="2" height="12" rx="1"/>
        <path
            d="M21.7928932,9.79289322 C22.1834175,9.40236893 22.8165825,9.40236893 23.2071068,9.79289322 C23.5976311,10.1834175 23.5976311,10.8165825 23.2071068,11.2071068 L20.2071068,14.2071068 C19.8165825,14.5976311 19.1834175,14.5976311 18.7928932,14.2071068 L15.7928932,11.2071068 C15.4023689,10.8165825 15.4023689,10.1834175 15.7928932,9.79289322 C16.1834175,9.40236893 16.8165825,9.40236893 17.2071068,9.79289322 L19.5,12.0857864 L21.7928932,9.79289322 Z"
            fill="#000000" fill-rule="nonzero"
            transform="translate(19.500000, 12.000000) rotate(-90.000000) translate(-19.500000, -12.000000) "/>

    </g>
											</svg>
                                                                             <!--end::Svg Icon-->
                                        </span>
Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </li>


                        </ul>
                        <!--end::Menu Nav-->
                    </div>
                    <!--end::Menu Container-->
                </div>
                <!--end::Aside Menu-->
            </div>
            <!--end::Aside-->

            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" class="header  header-fixed ">
                    <!--begin::Container-->
                    <div class=" container-fluid  d-flex align-items-stretch justify-content-between">
                        <!--begin::Header Menu Wrapper-->
                        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                            <!--begin::Header Menu-->
                            <div id="kt_header_menu"
                                 class="header-menu header-menu-mobile  header-menu-layout-default ">
                                <!--begin::Header Nav-->

                                <!--end::Header Nav-->
                            </div>
                            <!--end::Header Menu-->
                        </div>
                        <!--end::Header Menu Wrapper-->

                        <!--begin::Topbar-->
                        <div class="topbar">
                            <!--begin::Search-->
                            <!--end::Search-->

                            <!--begin::Notifications-->
                            <!--end::Notifications-->

                            <!--begin::Quick Actions-->
                            <!--end::Quick Actions-->

                            <!--begin::Cart-->
                            <!--end::Cart-->

                            <!--begin::Quick panel-->
                            <!--end::Quick panel-->

                            <!--begin::Chat-->
                            <!--end::Chat-->

                            <!--begin::Languages-->
                            <!--end::Languages-->

                            <!--begin::User-->
                            <div class="topbar-item">
                                <div
                                    class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2"
                                    id="kt_quick_user_toggle">
                                <span
                                    class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                                    <span
                                        class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{Auth::user()->name}}</span>
                                    <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
		                        <span
                                    class="symbol-label font-size-h5 font-weight-bold">{{ Auth::user()->name[0]}}</span>
		                    </span>
                                </div>
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Topbar-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->

                <!--begin::Content-->
                <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Subheader-->

                    <!--end::Subheader-->

                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container-fluid">
                            <!--begin::Dashboard-->
                            <!--begin::Row-->
                        @yield('content')
                        <!--end::Row-->
                            <!--end::Dashboard-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->

                <!--begin::Footer-->

                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->


    <!-- begin::User Panel-->
    <div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
            <h3 class="font-weight-bold m-0">
                User Profile
{{--                <small class="text-muted font-size-sm ml-2">12 messages</small>--}}
            </h3>
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
        <!--end::Header-->

        <!--begin::Content-->
        <div class="offcanvas-content pr-5 mr-n5">
            <!--begin::Header-->
            <div class="d-flex align-items-center mt-5">
                <div class="symbol symbol-100 mr-5">
                    <div class="symbol-label"
                         style="background-image: url({{ 'http://datenex.com/laravel/public/profile/'.$companyInfo->c_logo}})"></div>
                    <i class="symbol-badge bg-success"></i>
                </div>
                <div class="d-flex flex-column">
                    <a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
                        {{ Auth::user()->name }}
                    </a>
{{--                    <div class="text-muted mt-1">--}}
{{--                        Application Developer--}}
{{--                    </div>--}}
                    <div class="navi mt-2">
                        <a href="#" class="navi-item">
								<span class="navi-link p-0 pb-2">
									<span class="navi-icon mr-1">
										<span class="svg-icon svg-icon-lg svg-icon-primary">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-notification.svg-->
											<svg xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                 viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24"/>
													<path
                                                        d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z"
                                                        fill="#000000"/>
													<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5"/>
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
									</span>
									<span
                                        class="navi-text text-muted text-hover-primary">{{ \Illuminate\Support\Facades\Auth::user()->email}}</span>
								</span>
                        </a>
                        <a href="{{ route('logout') }}"
                           class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5"
                           onclick="event.preventDefault();

                     document.getElementById('logout-form').submit();"> {{ __('Logout') }}
                        </a>


                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>

                </div>
            </div>
            <!--end::Header-->

            <!--begin::Separator-->
            <div class="separator separator-dashed mt-8 mb-5"></div>
            <!--end::Separator-->

            <!--begin::Nav-->
            <div class="navi navi-spacer-x-0 p-0">
                <!--begin::Item-->
                <a href="{{url('edit-profile')}}" class="navi-item">
                    <div class="navi-link">
                        <div class="symbol symbol-40 bg-light mr-3">
                            <div class="symbol-label">
							<span class="svg-icon svg-icon-md svg-icon-success"><!--begin::Svg Icon | path:assets/media/svg/icons/General/Notification2.svg--><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z"
            fill="#000000"/>
        <circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5"/>
    </g>
</svg><!--end::Svg Icon--></span></div>
                        </div>
                        <div class="navi-text">
                            Edit Profile
                        </div>
                    </div>
                </a>



            </div>
            <!--end::Nav-->

            <!--begin::Separator-->
            <div class="separator separator-dashed my-7"></div>
            <!--end::Separator-->

            <!--begin::Notifications-->
            <!--end::Notifications-->
        </div>
        <!--end::Content-->
    </div>
    <!-- end::User Panel-->


    <!--begin::Quick Cart-->
    <!--end::Quick Cart-->

    <!--begin::Quick Panel-->
    <!--end::Quick Panel-->

    <!--begin::Chat Panel-->
    <!--end::Chat Panel-->

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
	<span class="svg-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg--><svg
            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
            viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1"/>
        <path
            d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
            fill="#000000" fill-rule="nonzero"/>
    </g>
</svg><!--end::Svg Icon--></span></div>
    <!--end::Scrolltop-->


    <!--begin::Sticky Toolbar-->

    <!--end::Sticky Toolbar-->
    <!--begin::Demo Panel-->
    <div id="kt_demo_panel" class="offcanvas offcanvas-right p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between pb-7">
            <h4 class="font-weight-bold m-0">
                Select A Demo
            </h4>
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_demo_panel_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
        <!--end::Header-->

        <!--begin::Content-->

        <!--end::Content-->
    </div>
    <!--end::Demo Panel-->

    <script>var HOST_URL = "{{ url('/') }}";</script>
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

    <!--begin::Page Vendors(used by this page)-->
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/fullcalendar/fullcalendar.bundle.js'}}"></script>
    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/widgets.js'}}"></script>
    @stack('scripts')

    <script>
        $('.example-copy').hide();
        {{--$(document).ready(function () {--}}
        {{--    $('#server-side-table').DataTable({--}}
        {{--        "ajax": "<?php echo !empty($AJAX_PATH) ? $AJAX_PATH : ''; ?>"--}}
        {{--    });--}}
        {{--});--}}
    </script>
    <!--end::Page Scripts-->
    </body>
    <!--end::Body-->
    </html>
</DOCTYPE>
