@extends('layouts.app')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-2 mr-5">Company Timeline</h5>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->

                <!--end::Toolbar-->
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 text-center">
                    <div class="card card-custom gutter-b example example-compact ">
                        <div class="card-header">
                            <h3 class="card-title">Company Timeline</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">

                                    <span class="example-copy" data-toggle="tooltip" title=""
                                          data-original-title="Copy code"></span>
                                </div>
                            </div>
                        </div>

                        <!--begin::Form-->
                        <form action="{{url('c-timeline-create')}}" method="post">
                            @csrf

                            <div class="card-body">
                                <div class="mb-1">

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Company Name</label>
                                        <div class="col-lg-6">
                                            <select
                                                class="form-control select2-control @error('customer_id') is-invalid @enderror"
                                                name="customer_id">
                                                <option value=""> Select</option>
                                                @foreach($customer as $key=>$value)
                                                    <option
                                                        value="{{$value->customer_id}}" {{(isset($CustomerID) &&  $value->customer_id==$CustomerID)?'selected':''}} >
                                                        {{$value->company_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{--                                    <div class="form-group row">--}}
                                    {{--                                        <label class="col-lg-3 col-form-label">Date Range</label>--}}
                                    {{--                                        <div class="col-lg-6">--}}
                                    {{--                                            <input class="form-control "--}}
                                    {{--                                                   name="date_range" id="kt_daterangepicker_1"--}}
                                    {{--                                                   readonly="readonly" placeholder="Select time"--}}
                                    {{--                                                   value="{{(isset($date_range))?$date_range:''}}  {{ (old('date_range')?old('date_range'): '') }}"--}}
                                    {{--                                                   type="text">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}


                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-lg-1"></div>
                                            <div class="col-lg-1"></div>
                                            <button type="submit" class="btn btn-primary mr-3"><i
                                                    class="ki ki-bold-check icon-md"></i>Submit
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


            @if(!empty($inquiry))
                <div class="row">
                    <!--begin::Notice-->
                    <!--end::Notice-->
                    <!--begin::Card-->
                    <div class="col-lg-8">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-checkable mt-10"
                                           id="kt_datatable">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Inquiry Form</th>
                                            <th>Company Name</th>
                                            <th>Contact Person</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Status</th>


                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($inquiry as $key=>$value)
                                            <tr>

                                                <td>{{date('d-m-Y',strtotime($value->date))}}</td>
                                                <td>{{$value->inquiry_from}}</td>
                                                <td>{{$value->company_name }}</td>
                                                <td>{{$value->contact_person}}</td>
                                                <td>{{$value->phone_no}}</td>
                                                <td>{{$value->email}}</td>
                                                <td class="text-center">


                                                    <a href="{{url('timeline', $value->inquiry_id)}}" target="_blank"
                                                       class="btn btn-light-warning font-weight-bolder btn-sm">TimeLine</a>


                                                </td>


                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <!--end: Datatable-->
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            @endif
                </div>
        </div>






    <!--end::Card-->
@endsection

@push('styles')
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}" rel="stylesheet"
          type="text/css"/>
@endpush
@push('scripts')
    <script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>
    <script>
        $('#kt_daterangepicker_1').daterangepicker({
            buttonClasses: ' btn',
            format: 'dd-mm-YYYY',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            locale: {
                format: 'DD/MM/YYYY'
            }
        }, function (start, end, label) {
            $('#kt_daterangepicker_1 .form-control').val(start.format('DD-MM-YYYY') + ' / ' + end.format('DD-MM-YYYY'));
        });
    </script>
    <script>
        var table = $('#kt_datatable');

        // begin first table
        table.DataTable({});

    </script>


@endpush
@push('scripts')
    <script>
        $('.select2-control').select2({
            placeholder: "Select..",
            allowClear: true
        });
    </script>
@endpush

