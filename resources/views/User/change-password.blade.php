@extends('layouts.app')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Change Password </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Master </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Change Password</a>
                            </li>

                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->

                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->

                    <!--end::Actions-->
                    <!--begin::Dropdown-->
                    <!--end::Dropdown-->
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--begin::Container-->
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Change Password </h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">

                                    <span class="example-copy" data-toggle="tooltip" title=""
                                          data-original-title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form method="post"
                              action="{{ url('change-password') }}">

                                {{ method_field('PUT') }}

                            {{ csrf_field() }}

                            <div class="card-body">
                                <div class="mb-1">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Current Password<span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-5">
                                            <input type="password" name="current_password" autocomplete="off"
                                                   class="form-control  @error('current_password') is-invalid @enderror"
                                                   placeholder="Current Password"
                                                   value="">
                                            @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label"> New Password<span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-5">
                                            <input type="password" name="new_password" autocomplete="off"
                                                   class="form-control  @error('new_password') is-invalid @enderror"
                                                   placeholder="New Password"
                                                   value="">
                                            @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label"> Confirm Password<span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-5">
                                            <input type="password" name="new_confirm_password" autocomplete="off"
                                                   class="form-control  @error('new_confirm_password') is-invalid @enderror"
                                                   placeholder="Confirm Password"
                                                   value="">
                                            @error('new_confirm_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-1">

                                            </div>
                                            <button type="submit" class="btn btn-primary mr-2" id="submit"><i
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
    </div>
@endsection











