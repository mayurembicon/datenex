@extends('layouts.app')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Setting </h5>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">

                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Master </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Setting </a>

                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                    <a href="{{route('setting.index')}}" class="btn  font-weight-bolder btn-sm">
                    </a>
                    <!--end::Actions-->
                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->
                <!--end::Toolbar-->
            </div>
        </div>
        <div class="container">
            @include('layouts.flash-message')
            <div class="row">
                <div class="col-lg-6">
                    <!--begin::Card-->
                    <!--begin::Form-->
                    <form action="{{ (isset($setting))?route('setting.update',$setting->id):route('setting.store')}}"
                          method="post">
                        @csrf
                        @if(isset($setting))
                            @method('PUT')
                        @endif
                        <div class="card card-custom gutter-b example example-compact">
                            <div class="card-header">
                                <h3 class="card-title">Setting</h3>
                                <div class="card-toolbar">
                                    <div class="example-tools justify-content-center">
                                            <span class="example-copy" data-toggle="tooltip" title=""
                                                  data-original-title="Copy code"></span>
                                    </div>
                                </div>
                            </div>
                            <!--begin::Form-->
                            <div class="card-body">
                                <div class="mb-6">
                                    <div class="example-preview">
                                        <ul class="nav nav-light-primary nav-pills" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home">
																	<span class="nav-icon">
																		<i class="flaticon2-chat-1"></i>
																	</span>
                                                    <span class="nav-text">Trade India</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link " id="india-tab" data-toggle="tab" href="#india">
																	<span class="nav-icon">
																		<i class="flaticon2-chat-1"></i>
																	</span>
                                                    <span class="nav-text"> India Mart</span>
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                                                   aria-controls="profile">
																	<span class="nav-icon">
																		<i class="flaticon2-layers-1"></i>
																	</span>
                                                    <span class="nav-text">Telegram</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact"
                                                   aria-controls="contact">
																	<span class="nav-icon">
																		<i class="flaticon2-user-1"></i>
																	</span>
                                                    <span class="nav-text">Email</span>
                                                </a>
                                            </li>

                                        </ul>

                                        <div class="tab-content mt-5" id="myTabContent">
                                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                 aria-labelledby="home-tab">

                                                {{--                                                <div class="form-group row">--}}
                                                {{--                                                    <label class="col-lg-3 col-form-label">India mart <span class="text-danger">*</span></label>--}}
                                                {{--                                                    <div class="col-lg-5">--}}
                                                {{--                                                        <input type="text" name="india_mart"--}}
                                                {{--                                                               class="form-control @error('india_mart') is-invalid @enderror"--}}
                                                {{--                                                               placeholder="Indiamart"--}}
                                                {{--                                                               value="{{(isset($setting))?$setting->india_mart:old('india_mart')}}">--}}
                                                {{--                                                        @error('india_mart')--}}
                                                {{--                                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>--}}
                                                {{--                                                        @enderror--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                </div>--}}
                                                {{--                                                <div class="form-group row">--}}
                                                {{--                                                    <label class="col-lg-3 col-form-label">Trade India <span class="text-danger">*</span></label>--}}
                                                {{--                                                    <div class="col-lg-5">--}}
                                                {{--                                                        <input type="text" name="trade_india"--}}
                                                {{--                                                               class="form-control @error('trade_india') is-invalid @enderror"--}}
                                                {{--                                                               placeholder="Tradeindia"--}}
                                                {{--                                                               value="{{(isset($setting))?$setting->trade_india:old('trade_india')}}">--}}
                                                {{--                                                        @error('trade_india')--}}
                                                {{--                                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>--}}
                                                {{--                                                        @enderror--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                </div>--}}
                                                {{--                                                <div class="form-group row">--}}
                                                {{--                                                    <label class="col-lg-3 col-form-label">Sync Time <span class="text-danger">*</span></label>--}}
                                                {{--                                                    <div class="col-lg-5">--}}
                                                {{--                                                        <input type="number" name="indiamart_sync_time_limit"--}}
                                                {{--                                                               class="form-control @error('indiamart_sync_time_limit') is-invalid @enderror"--}}
                                                {{--                                                               placeholder="Sync Time"--}}
                                                {{--                                                               value="{{(isset($setting))?$setting->indiamart_sync_time_limit:old('indiamart_sync_time_limit')}}">--}}
                                                {{--                                                        @error('indiamart_sync_time_limit')--}}
                                                {{--                                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>--}}
                                                {{--                                                        @enderror--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                </div>--}}
                                                <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">User ID <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-lg-5">
                                                    <input type="text" name="trade_user_id"
                                                           class="form-control @error('trade_user_id') is-invalid @enderror"
                                                           placeholder="User ID"
                                                           value="{{(isset($setting))?$setting->trade_user_id:old('trade_user_id')}}">
                                                    @error('trade_user_id')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Profile ID <span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" name="trade_profile_id"
                                                               class="form-control @error('trade_profile_id') is-invalid @enderror"
                                                               placeholder="Profile ID"
                                                               value="{{(isset($setting))?$setting->trade_profile_id:old('trade_profile_id')}}">
                                                        @error('trade_profile_id')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Key <span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" name="trade_key"
                                                               class="form-control @error('trade_key') is-invalid @enderror"
                                                               placeholder="Key"
                                                               value="{{(isset($setting))?$setting->trade_key:old('trade_key')}}">
                                                        @error('trade_key')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Indiamart Sync Time Limit <span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-lg-5">
                                                        <input type="number" name="indiamart_sync_time_limit"
                                                               class="form-control @error('indiamart_sync_time_limit') is-invalid @enderror"
                                                               placeholder="00"
                                                               value="{{(isset($setting))?$setting->indiamart_sync_time_limit:old('indiamart_sync_time_limit')}}">
                                                        @error('indiamart_sync_time_limit')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>



                                            </div>
                                            <div class="tab-pane fade" id="india" role="tabpanel"
                                                 aria-labelledby="india-tab">


                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Mobile No <span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" name="india_mobile_no"
                                                               class="form-control @error('india_mobile_no') is-invalid @enderror"
                                                               placeholder="Mobile No"
                                                               value="{{(isset($setting))?$setting->india_mobile_no:old('india_mobile_no')}}">
                                                        @error('india_mobile_no')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Key <span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" name="india_key"
                                                               class="form-control @error('india_key') is-invalid @enderror"
                                                               placeholder="Key"
                                                               value="{{(isset($setting))?$setting->india_key:old('india_key')}}">
                                                        @error('india_key')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>



                                            </div>


                                        <div class="tab-pane fade" id="profile" role="tabpanel"
                                             aria-labelledby="profile-tab">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Telegram API ID <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-lg-5">
                                                    <input type="text" name="telegram_api"
                                                           class="form-control @error('telegram_api') is-invalid @enderror"
                                                           placeholder="Telegram Api"
                                                           value="{{(isset($setting))?$setting->telegram_api:old('telegram_api')}}">
                                                    @error('telegram_api')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel"
                                             aria-labelledby="contact-tab">
                                            <div class="form-group row">
                                                <label class="col-lg-12  col-form-label">Body</label>
                                                <div class="col-lg-12">
                                                    <textarea name="mail_body" id="mail_body"
                                                              class="mail_body">{{(isset($setting))?$setting->mail_body:old('mail_body')}}</textarea>
                                                    <div id="remark_alert" class="invalid-feedback" role="alert"></div>
                                                    @error('mail_body')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary mr-2"><i
                                            class="fas fa-save"></i>Save
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
        </div>
    </div>
    </div>

@endsection
@push('scripts')
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/editors/ckeditor-classic.js'}}"></script>
    <script>
        var arrows;
        if (KTUtil.isRTL()) {
            arrows = {
                leftArrow: '<i class="la la-angle-right"></i>',
                rightArrow: '<i class="la la-angle-left"></i>'
            }
        } else {
            arrows = {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }
        $('#kt_datepicker_3').datepicker({
            rtl: KTUtil.isRTL(),
            todayBtn: "linked",
            format: 'dd-mm-yyyy',
            clearBtn: true,
            todayHighlight: true,
            templates: arrows
        });

    </script>

@endpush
@push('scripts')
    <script>
        $('#kt_select2_1').select2();
    </script>
    <script>
        $('.select2-control').select2({
            allowClear: true,
            placeholder: 'Select ',
        });
    </script>
@endpush
