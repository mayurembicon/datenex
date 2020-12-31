@extends('layouts.app')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class=" container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">Profile Edit
                        </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->

                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                    <a href="{{url('/')}}" class="btn  font-weight-bolder btn-sm">
                        <span class="svg-icon svg-icon-warning svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo9\dist/../src/media/svg/icons\Code\Backspace.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M8.42034438,20 L21,20 C22.1045695,20 23,19.1045695 23,18 L23,6 C23,4.8954305 22.1045695,4 21,4 L8.42034438,4 C8.15668432,4 7.90369297,4.10412727 7.71642146,4.28972363 L0.653241109,11.2897236 C0.260966303,11.6784895 0.25812177,12.3116481 0.646887666,12.7039229 C0.648995955,12.7060502 0.651113791,12.7081681 0.653241109,12.7102764 L7.71642146,19.7102764 C7.90369297,19.8958727 8.15668432,20 8.42034438,20 Z"
            fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                        Back
                    </a>
                    <!--end::Actions-->

                    <!--begin::Dropdown-->
                    <!--end::Dropdown-->
                </div>
                <!--end::Toolbar-->
            </div>


        </div>
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class=" container ">
                <!--begin::Profile Account Information-->
                <div class="d-flex flex-row">
                    <!--begin::Aside-->
                    <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
                        <!--begin::Profile Card-->
                        <div class="card card-custom card-stretch">
                            <!--begin::Body-->
                            <div class="card-body pt-4">
                                <!--begin::Toolbar-->
                                <!--end::Toolbar-->

                                <!--begin::User-->
                                <div class="d-flex align-items-center">
                                    <div
                                        class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                                        <div class="symbol-label"
                                             style="background-image:url({{ 'http://datenex.com/laravel/public/profile/'.$companyInfo->c_logo}})"></div>
                                        <i class="symbol-badge bg-success"></i>
                                    </div>
                                    <div>
                                        <a href="#"
                                           class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">
                                            {{Auth::user()->name}}
                                        </a>

                                    </div>
                                </div>
                                <!--end::User-->

                                <!--begin::Contact-->
                                <div class="py-9">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Email:</span>
                                        <a href="#"
                                           class="text-muted text-hover-primary">  {{ \Illuminate\Support\Facades\Auth::user()->email}}</a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="font-weight-bold mr-2">Phone:</span>
                                        <span
                                            class="text-muted">{{(isset($companyInfo))?$companyInfo->phone_no:old('phone_no')}}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="font-weight-bold mr-2">Location:</span>
                                        <span
                                            class="text-muted"> {{ isset($companyInfo->State)?$companyInfo->State->state_name:''}}</span>
                                    </div>
                                </div>
                                <!--end::Contact-->

                                <!--begin::Nav-->
                                <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                                    <div class="navi-item mb-2">
                                        <a href="{{url('edit-profile')}}"
                                           class="navi-link py-4 ">
                    <span class="navi-icon mr-2">
                        <span class="svg-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path
            d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
            fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path
            d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
            fill="#000000" fill-rule="nonzero"/>
    </g>
</svg><!--end::Svg Icon--></span>                    </span>
                                            <span class="navi-text font-size-lg">
                        Personal Information
                    </span>
                                        </a>
                                    </div>
                                    <div class="navi-item mb-2">
                                        <a href="{{route('user-profile.create')}}"
                                           class="navi-link py-4 active">
                    <span class="navi-icon mr-2">
                        <span class="svg-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
            fill="#000000" opacity="0.3"/>
        <path
            d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
            fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>                    </span>
                                            <span class="navi-text font-size-lg">
                      Edit User Profile
                    </span>
                                        </a>
                                    </div>
                                    <div class="navi-item mb-2">
                                        <a href="{{url('password-change')}}"
                                           class="navi-link py-4 ">
                    <span class="navi-icon mr-2">
                        <span class="svg-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Shield-user.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z"
            fill="#000000" opacity="0.3"/>
        <path
            d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z"
            fill="#000000" opacity="0.3"/>
        <path
            d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z"
            fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>                    </span>
                                            <span class="navi-text font-size-lg">
                        Change Password
                    </span>

                                        </a>
                                    </div>
                                    <div class="navi-item mb-2">
                                        <a href="{{ route('setting.edit',1) }}"
                                           class="navi-link py-4 ">
                    <span class="navi-icon mr-2">
                        <span class="svg-icon"><!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z"
            fill="#000000" opacity="0.3"/>
        <path
            d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z"
            fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>                    </span>
                                            <span class="navi-text font-size-lg">
                         Settings
                    </span>
                                        </a>
                                    </div>
                                </div>
                                <!--end::Nav-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Profile Card-->
                    </div>
                    <!--end::Aside-->
                    <div class="flex-row-fluid ml-lg-8">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <!--begin::Header-->

                            <div class="card-header py-3">
                                <div class="card-title align-items-start flex-column">
                                    <h3 class="card-label font-weight-bolder text-dark">User Profile</h3>
                                    <span class="text-muted font-weight-bold font-size-sm mt-1">Change User Profile</span>
                                </div>

                            </div>
                            <form method="post"
                                  action="{{($action=='INSERT')?route('user.store'): route('user-profile.update',$user->id) }}">
                                @if ($action=='UPDATE')
                                    {{ method_field('PUT') }}
                                @endif
                                {{ csrf_field() }}

                                <div class="card-body">
                                    <div class="mb-1">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label ">Name<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-5">
                                                <input type="text" name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       placeholder="Name"
                                                       value="{{(isset($user))?$user->name:old('name')}}">
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Email<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-5">
                                                <input type="text" name="email" autocomplete="off"
                                                       class="form-control  @error('email') is-invalid @enderror"
                                                       placeholder="Email"
                                                       value="{{(isset($user))?$user->email:old('email')}}">
                                                @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Telegram Id<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-5">
                                                <input type="text" name="telegram_id" autocomplete="off"
                                                       class="form-control  @error('email') is-invalid @enderror"
                                                       placeholder="Telegram Id"
                                                       value="{{(isset($user))?$user->telegram_id:old('telegram_id')}}">
                                                @error('telegram_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <a class="btn btn-success  btn-sm small max-h-30px max-w-50px"
                                               data-toggle="modal"
                                               data-target="#exampleModal"> Info</a>

                                        </div>


                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-1">

                                                </div>
                                                <button type="submit" class="btn btn-primary mr-2" id="submit"><i
                                                        class="fas fa-save"></i>Update Profile
                                                </button>
                                                <button type="reset" class="btn btn-secondary"><i
                                                        class="ki ki-bold-close icon-md"></i>Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--end::Card-->
                    </div>


                </div>
                <!--end::Profile Account Information-->
            </div>
            <!--end::Container-->
        </div>
    </div>
    <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog  modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Info

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="wizard wizard-3" id="kt_wizard_v3" data-wizard-state="step-first"
                         data-wizard-clickable="true">
                        <!--begin: Wizard Nav-->
                        <div class="wizard-nav">
                            <div class="wizard-steps px-8 py-8 px-lg-15 py-lg-3">
                                <!--begin::Wizard Step 1 Nav-->
                                <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                                    <div class="wizard-label">
                                        <h3 class="wizard-title">
                                            <span>1.</span>
                                        </h3>
                                        <div class="wizard-bar"></div>
                                    </div>
                                </div>
                                <!--end::Wizard Step 1 Nav-->

                                <!--begin::Wizard Step 2 Nav-->
                                <div class="wizard-step" data-wizard-type="step">
                                    <div class="wizard-label">
                                        <h3 class="wizard-title">
                                            <span>2.</span>
                                        </h3>
                                        <div class="wizard-bar"></div>
                                    </div>
                                </div>
                                <!--end::Wizard Step 2 Nav-->

                                <!--begin::Wizard Step 3 Nav-->
                                <div class="wizard-step" data-wizard-type="step">
                                    <div class="wizard-label">
                                        <h3 class="wizard-title">
                                            <span>3.</span>
                                        </h3>
                                        <div class="wizard-bar"></div>
                                    </div>
                                </div>
                                <!--end::Wizard Step 3 Nav-->

                                <!--begin::Wizard Step 4 Nav-->
                                <div class="wizard-step" data-wizard-type="step">
                                    <div class="wizard-label">
                                        <h3 class="wizard-title">
                                            <span>4.</span>
                                        </h3>
                                        <div class="wizard-bar"></div>
                                    </div>
                                </div>
                                <!--end::Wizard Step 4 Nav-->

                                <!--begin::Wizard Step 5 Nav-->
                                <div class="wizard-step" data-wizard-type="step">
                                    <div class="wizard-label">
                                        <h3 class="wizard-title">
                                            <span>5</span>
                                        </h3>
                                        <div class="wizard-bar"></div>
                                    </div>
                                </div>
                                <!--end::Wizard Step 5 Nav-->
                            </div>
                        </div>
                        <!--end: Wizard Nav-->

                        <!--begin: Wizard Body-->
                        <div class="row justify-content-center py-10 px-8 py-lg-12 px-lg-10">
                            <div class="col-xl-12 col-xxl-7">
                                <!--begin: Wizard Form-->
                                <form class="form" id="kt_form">
                                    <!--begin: Wizard Step 1-->
                                    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                        <h4 class="mb-10 font-weight-bold text-dark">Search userinfobot On Telegram</h4>
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/search userinfobot.png'}}">
                                        </div>


                                    </div>

                                    <div class="pb-5" data-wizard-type="step-content">
                                        <h4 class="mb-10 font-weight-bold text-dark">Click Start Button </h4>
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/Start Bot.png'}}" class="logo-default max-h-700px">
                                        </div>

                                    </div>

                                    <div class="pb-5" data-wizard-type="step-content">
                                        <h4 class="mb-10 font-weight-bold text-dark"> Generate Your Telegram ID</h4>
                                        <div class="form-group">
                                            <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/generate your id-.jpeg'}}">
                                        </div>

                                    </div>

                                    <div class="pb-5" data-wizard-type="step-content">
                                        <h4 class="mb-10 font-weight-bold text-dark">Search Our Channel @datenex_bot </h4>
                                        <div class="my-5">
                                            <div class="form-group">
                                                <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/search channel.png'}}">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="pb-5" data-wizard-type="step-content">
                                        <!--begin::Section-->
                                        <h4 class="mb-10 font-weight-bold text-dark">Click Start Button Join Our Channel</h4>
                                        <div class="form-group">
                                            <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/Start Channel.png'}}">
                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                        <div class="mr-2">
                                            <button type="button"
                                                    class="btn btn-light-primary font-weight-bold text-uppercase px-9 py-4"
                                                    data-wizard-type="action-prev">
                                                Previous
                                            </button>
                                        </div>
                                        <div>
                                            <button type="submit" onclick="{{route('user-profile.create')}}"
                                                    class="btn btn-success font-weight-bold text-uppercase px-9 py-4"
                                                    data-wizard-type="action-submit">
                                                Done
                                            </button>
                                            <button type="button"
                                                    class="btn btn-primary font-weight-bold text-uppercase px-9 py-4"
                                                    data-wizard-type="action-next">
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                    <!--end: Wizard Actions-->
                                </form>
                                <!--end: Wizard Form-->
                            </div>
                        </div>
                        <!--end: Wizard Body-->
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@push('styles')
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/pages/wizard/wizard-3.css'}}"
          rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/custom/wizard/wizard-3.js'}}"></script>
@endpush








