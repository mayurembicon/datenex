@extends('layouts.app')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Payment </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Transaction </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Payment </a>
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


                            <a href="{{route('payment.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
                                <i class="flaticon-add-circular-button"></i> Add New
                            </a>




                <!--end::Actions-->

                    <!--begin::Dropdown-->
                    <!--end::Dropdown-->
                </div>

                <!--end::Toolbar-->
            </div>
        </div>

        <!--begin::Container-->
        <div class="container-fluid">
        @include('layouts.flash-message')

        <!--begin::Notice-->
            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <form action="{{ url('search-py') }}" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="form-group row">

                            <div class="col-lg-2">
                                <label class="col-form-label ">Transaction Type</label>
                                <select class="form-control select-chosen" name="transaction_type" id="transaction_type">
                                    <option value=""></option>

                                    <option
                                        value="C" {{ !empty($search_item AND ($search_item['transaction_type'] == 'C'))?'selected':'' }}>
                                        Cash
                                    </option>
                                    <option
                                        value="B" {{ !empty($search_item AND ($search_item['transaction_type'] == 'B'))?'selected':'' }}>
                                        Bank
                                    </option>

                                </select>

                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label">Company Name</label>
                                <select
                                    class="form-control customer-select2 @error('customer_id') is-invalid @enderror "
                                    name="company_name">
                                    <?php if(!empty($selectedCustomer)){ ?>
                                    <option
                                        value="<?php echo $selectedCustomer; ?>"><?php echo $selectedCustomer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>


                        </div>
                        <div class="card-footer mb-0">


                            <button type="submit" name="name"
                                    class="btn btn-primary btn-sm @error('name') is-invalid @enderror">
                                <i class="flaticon-search"></i> Search</button>
                            <a href="{{ url('clear-py') }}"
                               class="btn btn-sm btn-danger @error('name') is-invalid @enderror">
                                <i class="fas fa-eraser"></i> Clear</a>

                        </div>
                    </form>

                </div>
            </div>
            <br>
            <div class="card card-custom">
                <div class="card-body">

                    <div class="table-responsive">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable mt-11 col-form-label" >
                            <thead>

                            <tr>

                                <th>Transaction Type</th>
                                <th>Date</th>
                                <th>Company Name</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payment as $key=>$value)
                                <tr>
                                    <td>
                                        @if($value->transaction_type=='B')
                                            <span
                                                class="label label-xl label-outline-warning label-inline font-weight-bold mr-2">


                                        <i class="fas fa-landmark" style="color:orange"></i> &nbsp; Bank
                                    </span>
                                        @else

                                            <span
                                                class="label label-xl label-outline-danger   label-inline font-weight-bold mr-2">

                                        <i class="fas  fa-rupee-sign" style="color:red "></i> &nbsp;Cash

                                    </span>

                                        @endif
                                    </td>
                                    <td>{{date('d-m-Y',strtotime($value->date))}}</td>
                                    <td>{{$value->company_name}}</td>

                                    <td>{{$value->grand_total}}</td>
                                    <td>{{$value->description}}</td>
                                    <td>

                                        <a href="{{route ('payment.edit',$value->journal_id)}}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i> </a>

                                    <td>

                                        <form action="{{route('payment.destroy',$value->journal_id)}}" method="post">
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
                </div>
            </div>
        </div>
    </div>
    <!--end::Card-->


    <!--end::Container-->

@endsection
@push('styles')
    <link href="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}" rel="stylesheet"
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
    <script>
        $(".customer-select2").select2({
            placeholder: "Select..", ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'customer',
                        field_name: 'company_name'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
    </script>

@endpush


