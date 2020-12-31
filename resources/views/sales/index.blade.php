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
                            Sales </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Transaction </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Sales </a>
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
                    <a href="{{route('sales.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
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

                    <form action="{{ url('search-sales') }}" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="form-group row">

                            <div class="col-lg-2">
                                <label class="col-form-label">Company Name</label>
                                <select
                                    class="form-control customer-select2 @error('customer_id') is-invalid @enderror "
                                    name="customer_id">
                                    <?php if(!empty($selectedCustomer)){ ?>
                                    <option
                                        value="<?php echo $selectedCustomer; ?>"><?php echo $selectedCustomer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="col-form-label ">Product Name</label>
                                <select
                                    class="item-clr form-control item-select2 @error('item_id') is-invalid @enderror "
                                    name="item_id">
                                    <?php if(!empty($selectedItem)){ ?>
                                    <option value="<?php echo $selectedItem; ?>"><?php echo $selectedItem; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label ">Sales No</label>
                                <select
                                    class="item-clr form-control @error('item_id') is-invalid @enderror "
                                    id="invoice_no"
                                    name="invoice_no">
                                    <?php if(!empty($selectedSaleseNo)){ ?>
                                    <option
                                        value="<?php echo $selectedSaleseNo; ?>"><?php echo $selectedSaleseNo; ?></option>
                                    <?php } ?>
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-2">


                            <button type="submit" name="name"
                                    class="btn btn-primary btn-sm @error('name') is-invalid @enderror">
                                <i class="flaticon-search"></i> Search
                            </button>
                            <a href="{{ url('clear-sales') }}"
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
                        <table class="table table-bordered table-hover table-checkable mt-11 col-form-label">
                            <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Order No</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Company Name</th>
                                <th>Print</th>
                                <th>Telegram</th>
                                <th>Mail</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($sales as $key=>$value)
                                <tr>

                                    <td>{{$value->invoice_no}}</td>
                                    <td>{{$value->ref_order_no}}</td>
                                    <td>{{date('d-m-Y',strtotime($value->invoice_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($value->due_date))}}</td>
                                    <td>{{$value->company_name}}</td>
                                    {{--                                    <td>{{$value->sales_person}}</td>--}}
                                    {{--                                    <td>{{isset($value->paymentterms)?$value->paymentterms->payment_terms:''}}</td>--}}
                                    {{--                                    <td>{{$value->subject}}</td>--}}
                                    </td>
                                    <td>
                                        <a href="{{url('print-sales',$value->invoice_id)}}" target="_blank"
                                           class="btn btn-info btn-sm"><i
                                                class="fa fa-print"></i>
                                        </a>
                                    </td>

                                    <td>
                                        <a href="{{url('send-sales-telegram-msg',$value->invoice_id)}}"
                                          >
                                            <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/telegram.svg'}}" width="40" height="40" ></a>
                                    </td>
                                    <td>
                                        <a href="#" data-toggle="modal"
                                           data-id="{{ $value->invoice_id }}"
                                           data-target="#mailModel"
                                           class="btn btn-info btn-sm view-mail">
                                            <i class="fa fa-envelope"></i></a>
                                    </td>
                                    <td>

                                        <a href="{{route('sales.edit',$value->invoice_id)}}"
                                           class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>
                                        </a>

                                    <td>

                                        <form action="{{route('sales.destroy',$value->invoice_id) }}" method="post">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm "
                                                    onclick="return confirm('Are you sure  want to delete?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mailModel" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mail

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{ url('send-sales-email') }}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="mail_invoice_id" name="mail_invoice_id" value="">

                        <div class="form-group row">
                            <label class="col-lg-12 col-form-label">Email </label>
                            <div class="col-lg-10">
                                <input type="email" id="email" required
                                       class="form-control select2-control email"
                                       name="email" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12 col-form-label ">Title</label>
                            <div class="col-lg-10">
                                <input type="text" name="mail_title" required
                                       class="form-control mail_title" id="title"
                                       value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12  col-form-label">Body</label>
                            <div class="col-lg-12">
                                <textarea name="mail_body" id="mail_body"
                                          class="mail_body">{{$setting->mail_body}}</textarea>
                                <div id="remark_alert" class="invalid-feedback" role="alert"></div>
                                @error('remark')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Attachment With Pdf</label>
                            <div class="col-2">
                            <span class="switch switch-outline switch-icon switch-primary">
								<label>
									<input type="checkbox" checked
                                           class=""
                                           name="attachment"
                                           id="attachment">
									<span></span>
								</label>
							</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary font-weight-bold">Send

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link
        href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}"
        rel="stylesheet"
        type="text/css"/>
@endpush
@push('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/select2.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/editors/ckeditor-classic.js'}}"></script>
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
        $(".item-select2").select2({
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
                        table_name: 'itemmaster',
                        field_name: 'name'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });


        $("#order_no").select2({
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
                        table_name: 'invoice',
                        field_name: 'ref_order_no'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
        $("#invoice_no").select2({
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
                        table_name: 'invoice',
                        field_name: 'invoice_no'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
    </script>

    <script>
        $(document).on("click", ".view-mail", function () {
            var ID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('sales-customer-email')}}',
                type: 'POST',

                data: {invoice_id: ID},
                success: function (data) {
                    $("#mail_invoice_id").val(data.invoice_id);
                    $("#email").val(data.email);

                }
            });
        });
        function QuotationMail() {
            var mail_title = $('.mail_title').val();
            var mail_body = $('.mail_body').html();
            alert(mail_body);
            return false;
            var email = $('.email').val();
            var mail_invoice_id = ($('input[name="mail_invoice_id"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('send-sales-email') }}',
                data: {
                    mail_title: mail_title,
                    mail_body: mail_body,
                    email: email,
                    mail_invoice_id: mail_invoice_id,
                },
                success: function (data) {
                    if (data.error) {
                        $.each(data.error, function (key, value) {
                            $("#" + key).toggleClass("is-invalid");
                            $("#" + key + '_alert').html(value);
                        });
                    } else {
                        if (data.success) {
                            $('#exampleModal').modal('toggle');
                            $('#mail_title').val('');
                            $('#mail_body').val('');
                            $('#mail_invoice_id').val('');
                            // Swal.fire({
                            //     position: "center",
                            //     icon: "success",
                            //     title: "Email Send successfully.",
                            //     showConfirmButton: false,
                            //     timer: 1500
                            // });

                            // $("#customer-model-body #customer_name").val();
                            // $("#customer-model-body #company_name").val();
                        }

                    }

                }
            });
        }
    </script>
@endpush



