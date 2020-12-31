@extends('layouts.main-app')
@section('content')


    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Tenant</h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Master </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Tenant</a>
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
                    <a href="{{route('tenant.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
                        <i class="ki ki-solid-plus"></i> Add New
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
        <div class="container">
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
                                <th>Tenant</th>
                                <th>Domain</th>
                                <th>Delete</th>



{{--                                <th>Update</th>--}}
{{--                                <th>Delete</th>--}}


                            </tr>

                            <tbody>
                            @foreach($tenant as $key=>$value)
                                <tr>
                                    <td>{{$value->id}}</td>
                                    <td>{{$value->domain}}</td>

{{--                                    <td>--}}

{{--                                        <a href="{{route('tenant.edit',$value->id)}}" class="btn btn-warning btn-sm">--}}
{{--                                            <i class="fa fa-edit"></i></a>--}}
{{--                                    </td>--}}
                                    <td>


                                            <form action="{{route('tenant.destroy',$value->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure  want to delete?')"   >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('styles')
    <link href="{{ env('ASSET_URL').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}" rel="stylesheet"
          type="text/css"/>
@endpush
@push('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{env('ASSET_URL').'assets/js/pages/crud/forms/widgets/select2.js'}}"></script>
    <script src="{{ env('ASSET_URL').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>

    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script>
        $('#kt_datatable').DataTable({});

    </script>

@endpush



