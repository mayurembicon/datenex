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
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title"> Company Profile</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">

                                <span class="example-copy" data-toggle="tooltip" title=""
                                      data-original-title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <div data-scroll="true" data-height="350">
                        <!--begin::Form-->
                        <div class="card-body px-0">
                            <div data-scroll="true">
                            <form action="{{ (isset($companyInfo))?route('profiles.update',1):route('profiles.store')}}"
                                  method="post" enctype="multipart/form-data">
                                @csrf
                                @if(isset($companyInfo))
                                    @method('PUT')
                                @endif

                                   <div class="tab-content">

                                        <!--begin::Tab-->
                                    <div class="tab-pane show active px-7" id="kt_user_edit_tab_1" role="tabpanel">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <div class="col-xl-2"></div>
                                            <div class="col-xl-7 my-2">
                                                <!--begin::Row-->
                                                <div class="row">
                                                    <label class="col-3"></label>
                                                    <div class="col-9">
                                                        <h6 class=" text-dark font-weight-bold mb-10">Company Info:</h6>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Company
                                                        Logo</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="image-input image-input-outline image-input"
                                                             id="kt_image_3">
                                                            <div class="image-input-wrapper"
                                                                 style="background-image: url({{ 'http://datenex.com/laravel/public/profile/'.$companyInfo->c_logo}})"></div>
                                                            <label
                                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                                data-action="change" data-toggle="tooltip" title=""
                                                                data-original-title="Change avatar">
                                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                                <input type="file" name="c_logo" value=""
                                                                       accept=".png, .jpg, .jpeg"/>
                                                                <input type="hidden" name="profile_avatar_remove"/>
                                                            </label>

                                                            <span
                                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                                data-action="cancel" data-toggle="tooltip"
                                                                title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                                                        </div>
                                                        <span class="form-text text-muted">Allowed file types:  png, jpg, jpeg.</span>
                                                    </div>
                                                </div>
                                                <!--end::Row-->
                                                <!--begin::Group-->


                                                <div class="form-group row">
                                                    <label class="col-form-label col-3 text-lg-right text-left">Company
                                                        Name </label>
                                                    <div class="col-9">
                                                        <input class="form-control "
                                                               type="text"
                                                               name="company_name"
                                                               value=" {{(isset($companyInfo))?$companyInfo->company_name:old('company_name')}} "/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-3 text-lg-right text-left">Phone
                                                        Number </label>
                                                    <div class="col-9">
                                                        <input class="form-control "
                                                               type="text"
                                                               name="phone_no"
                                                               value=" {{(isset($companyInfo))?$companyInfo->phone_no:old('phone_no')}} "/>
                                                    </div>
                                                </div>
                                                <!--end::Group-->
                                                <!--begin::Group-->
                                                <div class="form-group row">
                                                    <label class="col-form-label col-3 text-lg-right text-left">GST
                                                        IN</label>
                                                    <div class="col-9">
                                                        <input class="form-control "
                                                               type="text"
                                                               name="gst_in"
                                                               value="{{(isset($companyInfo))?$companyInfo->gst_in:old('gst_in')}}"/>
                                                    </div>
                                                </div>
                                                <!--end::Group-->
                                                <!--begin::Group-->
                                                <div class="form-group row">
                                                    <label
                                                        class="col-form-label col-3 text-lg-right text-left">Address</label>
                                                    <div class="col-9">
                                                        <input class="form-control "
                                                               type="text"
                                                               name="address1"
                                                               value="{{(isset($companyInfo))?$companyInfo->address1:old('address1')}}"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        class="col-form-label col-3 text-lg-right text-left">Address</label>
                                                    <div class="col-9">
                                                        <input class="form-control "
                                                               type="text"
                                                               name="address2"
                                                               value="{{(isset($companyInfo))?$companyInfo->address2:old('address2')}}"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        class="col-form-label col-3 text-lg-right text-left">Address</label>
                                                    <div class="col-9">
                                                        <input class="form-control "
                                                               type="text"
                                                               name="address3"
                                                               value="{{(isset($companyInfo))?$companyInfo->address3:old('address3')}}"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        class="col-form-label col-3 text-lg-right text-left">Country</label>
                                                    <div class="col-9">
                                                        <select
                                                            class="form-control country-select2"
                                                            name="country_id">
                                                            @if(!empty($companyInfo))
                                                                <option
                                                                    value="{{(isset($companyInfo))?$companyInfo->country_id:(old('country_id')?old('country_id'):0)}}">
                                                                    {{ isset($companyInfo->country_id)?$companyInfo->getcountry->country_name:''}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        class="col-form-label col-3 text-lg-right text-left">State</label>
                                                    <div class="col-9">
                                                        <select
                                                            class="form-control  state-select2"
                                                            name="state_id">
                                                            @if(!empty($companyInfo))
                                                                <option
                                                                    value="{{(isset($companyInfo))?$companyInfo->state_id:(old('state_id')?old('state_id'):0)}}">
                                                                    {{ isset($companyInfo->state_id) ?$companyInfo->getstate->state_name:''}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        class="col-form-label col-3 text-lg-right text-left">City</label>
                                                    <div class="col-9">
                                                        <input class="form-control "
                                                               type="text"
                                                               name="city_name"
                                                               value="{{(isset($companyInfo))?$companyInfo->city_name:old('city_name')}}"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        class="col-form-label col-3 text-lg-right text-left">Pin
                                                        code</label>
                                                    <div class="col-9">
                                                        <input class="form-control "
                                                               type="text"
                                                               name="pincode"
                                                               value="{{(isset($companyInfo))?$companyInfo->pincode:old('pincode')}}"/>
                                                    </div>
                                                </div>
                                                <!--end::Group-->
                                                <!--begin::Group-->
                                                <div class="form-group row">
                                                    <label class="col-form-label col-3 text-lg-right text-left">Invoice
                                                        Prefix</label>
                                                    <div class="col-9">
                                                        <input class="form-control"
                                                               name="invoice_prefix"
                                                               type="text"
                                                               value="{{(isset($companyInfo))?$companyInfo->invoice_prefix:old('invoice_prefix')}}"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-3 text-lg-right text-left">Quotation
                                                        Prefix</label>
                                                    <div class="col-9">
                                                        <input class="form-control"
                                                               name="quotation_prefix"
                                                               type="text"
                                                               value="{{(isset($companyInfo))?$companyInfo->quotation_prefix:old('quotation_prefix')}}"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-3 text-lg-right text-left">PO
                                                        Prefix</label>
                                                    <div class="col-9">
                                                        <input class="form-control"
                                                               name="po_prefix"
                                                               type="text"
                                                               value="{{(isset($companyInfo))?$companyInfo->po_prefix:old('po_prefix')}}"/>
                                                    </div>
                                                </div>
                                                <!--end::Group-->
                                            </div>
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <div class="card-footer mb-0">

                                        <div class="row">
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-4">
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

    </div>
@endsection
@push('styles')


@endpush
@push('scripts')

    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/file-upload/image-input.js'}}"></script>
    <script>
        $(document).ready(function () {
            $('.select2-control').select2({
                placeholder: "Select ...",
                allowClear: false
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
@endpush















