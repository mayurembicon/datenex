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

                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Financial Year </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Master </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Financial Year </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Create </a>
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
                    <a href="{{route('financial-year.index')}}" class="btn  font-weight-bolder btn-sm">
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

        <!--end::Subheader-->
        <!--begin::Entry-->

        <!--end::Entry-->

        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Financial Year</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">

                                    <span class="example-copy" data-toggle="tooltip" title=""
                                          data-original-title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form
                            action="{{(isset($financial_year))?route('financial-year.update',$financial_year->financial_year_id):route('financial-year.store')}}"
                            method="post">

                            @csrf
                            @if(isset($financial_year))
                                @method('PUT')
                            @endif


                            <div class="card-body">
                                <div class="form-group mb-6">

                                </div>
                                <div class="card-body">

                                    <div class="mb-10">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">From Date<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <div class="input-group date">
                                                    <input type="text"
                                                           class="form-control @error('start_date') is-invalid @enderror"
                                                           placeholder="dd-mm-yyyy " readonly="readonly"
                                                           name="start_date"
                                                           value="{{ !empty(old('start_date'))?old('start_date'):(!empty($financial_year->start_date)?date('d-m-Y',strtotime($financial_year->start_date)):date('d-m-Y')) }}"
                                                           id="kt_datepicker_3"/>

                                                    <div class="input-group-append">

															<span class="input-group-text">
																<i class="la la-calendar"></i>
															</span>

                                                    </div>
                                                    @error('start_date')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">End Date<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-lg-6 ">
                                                <div class="input-group date">
                                                    <input type="text"
                                                           class="form-control @error('end_date') is-invalid @enderror"
                                                           placeholder="dd-mm-yyyy" readonly="readonly" name="end_date"
                                                           value="{{(isset($financial_year))?date('d-m-Y',strtotime($financial_year->end_date)):old('end_date')}}"
                                                               id="kt_datepicker_3"/>

                                                    <div class="input-group-append">

                                    <span class="input-group-text">
                                        <i class="la la-calendar"></i>
                                    </span>
                                                    </div>
                                                    @error('end_date')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Default</label>
                                            <div class=" col-lg-6 ">
                                                <select class="form-control @error('is_default') is-invalid @enderror"
                                                        name="is_default">
                                                    <option
                                                        value="Y" {{(isset($financial_year) && $financial_year->is_default=='Y')?'selected':old('Y')}}>
                                                        Yes
                                                    </option>
                                                    <option
                                                        value="N" {{(isset($financial_year) && $financial_year->is_default=='N')?'selected':old('N')}}>
                                                        No
                                                    </option>
                                                </select>
                                                @error('is_default')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--begin: Code-->
                                    </div>  <!--end: Code-->
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
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')

    <script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5'}}"></script>


@endpush
