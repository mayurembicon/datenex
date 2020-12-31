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
                            Customer </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Master </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Customer </a>
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
                    <a href="{{route('customer.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
                        <i class="flaticon-add-circular-button"></i> Add New
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
                <div class="card-body" style="padding:1px 10px;!important;">

                <form action="{{ url('search-customer') }}" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="form-group row">
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

                            <div class="col-lg-2">
                                <label class="col-form-label ">Contact Person</label>
                                <select
                                    class="item-clr form-control @error('item_id') is-invalid @enderror "
                                    id="contact_person"
                                    name="customer_name">
                                    <?php if(!empty($selectedContactPer)){ ?>
                                    <option
                                        value="<?php echo $selectedContactPer; ?>"><?php echo $selectedContactPer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>



                            <div class="col-lg-2">
                                <label class="col-form-label">Email</label>
                                <select
                                    class="item-clr form-control @error('email') is-invalid @enderror " id="email"
                                    name="email">
                                    <?php if(!empty($selectedEmail)){ ?>
                                    <option value="<?php echo $selectedEmail; ?>"><?php echo $selectedEmail; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label ">Phone No </label>
                                <select
                                    class="item-clr form-control @error('phone_no') is-invalid @enderror " id="phone_no"
                                    name="f_phone_no">
                                    <?php if(!empty($selectedPhone)){ ?>
                                    <option value="<?php echo $selectedPhone; ?>"><?php echo $selectedPhone; ?></option>
                                    <?php } ?>
                                </select>

                            </div>



                            </div>
                        <div class="card-footer mb-0">


                            <button type="submit" name="name"
                                    class="btn btn-primary btn-sm @error('name') is-invalid @enderror">
                                <i class="flaticon-search"></i> Search</button>
                            <a href="{{ url('clear-customer') }}"
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
                        <table class="table table-bordered table-hover table-checkable mt-10 col-form-label">
                            <thead>
                            <tr>


                                <th>Company Name</th>
                                <th>Contact Person</th>
                                <th>Email Address</th>
                                <th>Phone No</th>
                                <th class="">Opening Balance</th>
                                <th>Type</th>
                                <th>Payment Terms</th>
                                <th>Update</th>
                                <th>Delete</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($customer as $key=>$value)
                                <tr>

                                    <td>{{$value->company_name}}</td>
                                    <td>{{$value->customer_name}}</td>

                                    <td>{{$value->email}}</td>
                                    <td>{{$value->f_phone_no}}</td>

                                    <td> {{isset($value->getOpeningBalance)?$value->getOpeningBalance->opening_balance:''}}</td>
                                    <td>{{isset($value->getOpeningBalance)?$value->getOpeningBalance->opening_balance_type=="C"?"Credit":"Debit":""}}</td>


                                    <td>{{isset($value->paymentterms)?$value->paymentterms->payment_terms:''}}</td>
                                    <td>
                                        <a href="{{route('customer.edit',$value->customer_id)}}"
                                           class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>
                                        </a>
                                    <td>
                                        <form action="{{route('customer.destroy',$value->customer_id) }}" method="post">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm " onclick="return confirm('Are you sure  want to delete?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $customer->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('styles')
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}" rel="stylesheet"
          type="text/css"/>
@endpush

@push('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/select2.js'}}"></script>
    <script src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>

    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script>
        $('#kt_datatable').DataTable({});

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

        $("#contact_person").select2({
            placeholder: "Select..",
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'customer',
                        field_name: 'customer_name'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

        $("#email").select2({
            placeholder: "Select..",
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'customer',
                        field_name: 'email'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
        $("#phone_no").select2({
            placeholder: "Select..",
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'customer',
                        field_name: 'f_phone_no'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

    </script>

@endpush



