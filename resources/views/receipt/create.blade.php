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
                            Receipt </h5>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">

                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Transaction </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Receipt  </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Create  </a>
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
                        <a href="{{route('receipt.index')}}" class="btn  font-weight-bolder btn-sm">
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
                    </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <!--begin::Card-->
                    <!--begin::Form-->
                    <form
                        action="{{ (isset($receipt))?route('receipt.update',$receipt->journal_id):route('receipt.store')}}"
                        method="post" id="my-form">
                        @csrf
                        @if(isset($receipt))
                            @method('PUT')
                        @endif
                        <div class="card card-custom gutter-b example example-compact">
                            <div class="card-header">
                                <h3 class="card-title">Receipt</h3>
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
                                    <input type="hidden" name="type"
                                           value="{{(isset($receipt))?$receipt->type:$type}}">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label"> Transaction Type <span class="text-danger">*</span></label>

                                        <div class="col-lg-5">
                                        <select
                                            class="form-control @error('transaction_type')is-invalid @enderror"
                                            name="transaction_type">
                                            <option
                                                value="C" {{(isset($receipt) && $receipt->transaction_type=='C')?'selected':old('C')}}>
                                                Cash
                                            </option>

                                            <option
                                                value="B" {{(isset($receipt) && $receipt->transaction_type=='B')?'selected':old('B')}}>
                                                Bank
                                            </option>
                                        </select>
                                        @error('transaction_type')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label"> Date <span class="text-danger">*</span></label>
                                        <div class="col-lg-5">
                                            <div class="input-group date">
                                                <input type="text" class="form-control
                                                        @error('date') is-invalid @enderror"
                                                       placeholder="dd-mm-YYYY" readonly="readonly"
                                                       name="date"
                                                       value="{{ !empty(old('date'))?old('date'):(!empty($receipt->date)?date('d-m-Y',strtotime($receipt->date)):date('d-m-Y')) }}"
                                                       id="kt_datepicker_3"/>

                                                <div class="input-group-append">
                                                        <span class="input-group-text">
                                                                    <i class="la la-calendar"></i>
                                                                </span>
                                                </div>
                                                @error('date')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label ">Company Name<span
                                                class="text-danger">*</span> </label>
                                        <div class="col-lg-5">
                                            <select class="form-control select2-control
                                        @error('customer_id') is-invalid @enderror"
                                                    name="customer_id">
                                                <option value="">select company</option>
                                                @foreach($customers as $customer)
                                                    <option
                                                        value="{{$customer->customer_id}}" {{(isset($receipt) && $receipt->customer_id==$customer->customer_id)?'selected':''}} ]
                                                        {{ ((old('customer_id')==$customer->customer_id)?'selected': '') }}>
                                                        {{$customer->company_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <span id="tcreat"></span>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Amount <span class="text-danger">*</span></label>
                                        <div class="col-lg-5">
                                            <input type="number" name="grand_total"
                                                   class="form-control @error('grand_total') is-invalid @enderror"
                                                   placeholder="0.00"
                                                   value="{{(isset($receipt))?$receipt->grand_total:old('grand_total')}}">
                                            @error('grand_total')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Description</label>
                                        <div class="col-lg-6">
                                                <textarea name="description" placeholder="Description"
                                                          class="form-control "> {{(isset($receipt))?$receipt->description:old('description')}}</textarea>
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
                                                class="ki ki-bold-close icon-md"></i>Cancel
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
    <script type="text/javascript">

        $(document).ready(function () {

            $("#my-form").submit(function (e) {

                $("#submit").attr("disabled", true);

                return true;

            });
        });

    </script>

@endpush
