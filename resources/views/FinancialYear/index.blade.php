@extends('layouts.app')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class="  container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Financial Year </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a  class="text-muted">
                                    Master </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a  class="text-muted">
                                    Financial Year </a>
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

                        <a href="{{route('financial-year.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
                            <i class="flaticon-add-circular-button"></i>   Add New
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
        <!--begin::Container-->
        <div class="container-fluid">
        @include('layouts.flash-message')

            <!--begin::Notice-->

            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable mt-10" id="kt_datatable">
                            <thead>

                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Current Year</th>
                                <th>Default</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($financial_year as $key=>$value)
                                <tr>
                                    <input type="hidden" class="serdelete_val_id" value="{{$value->id}}">
                                    <td>{{date('d-m-Y',strtotime($value->start_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($value->end_date))}}</td>
                                    <td>{{$value->current_year}}</td>
                                    <td>{{$value->is_default=='Y'?'Yes':'No'}}</td>
                                    <td>

                                        <a href="{{route ('financial-year.edit',$value->financial_year_id)}}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i> </a>
                                    <td>

                                        <form action="{{route('financial-year.destroy',$value->financial_year_id)}}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure  want to delete?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>


                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end: Datatable-->
                </div>

            </div>
            <!--end::Card-->
        </div>

    <!--end::Container-->

@endsection
@push('styles')
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}" rel="stylesheet"
          type="text/css"/>
@endpush
@push('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>
    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script>
        $('#kt_datatable').DataTable();
    </script>
@endpush
